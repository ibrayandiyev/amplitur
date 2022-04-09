<?php

namespace App\Services\Imports;

use App\Base\BaseService;
use App\Enums\PersonType;
use App\Enums\ProcessStatus;
use App\Models\Booking;
use App\Models\BookingLegacies;
use App\Models\BookingVoucherFile;
use App\Models\Promocode;
use App\Repositories\BookingBillRefundRepository;
use App\Repositories\BookingBillRepository;
use App\Repositories\BookingClientRepository;
use App\Repositories\BookingLogRepository;
use App\Repositories\BookingPassengerRepository;
use App\Repositories\BookingRepository;
use App\Repositories\PromocodeRepository;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class ImportReservationsService extends BaseService
{
    use ImportLegacyTrait;

    private $_countries;
    private $_countriesDdi;
    private $_countriesId;
    
    private $currencyId = 1;
    
    /**
     * @var BookingVoucherRepository
     */
    protected $repository;

    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * @var BookingBillRepository
     */
    protected $bookingBillRepository;

    /**
     * @var BookingBillRefundRepository
     */
    protected $bookingBillRefundRepository;

    /**
     * @var BookingClientRepository
     */
    protected $bookingClientRepository;

    /**
     * @var BookingPassengerRepository
     */
    protected $bookingPassengerRepository;

    /**
     * @var BookingLogRepository
     */
    protected $bookingLogRepository;

    /**
     * @var PromocodeRepository
     */
    protected $promocodeRepository;

    public function __construct(
    BookingRepository $bookingRepository,
    BookingBillRepository $bookingBillRepository,
    BookingBillRefundRepository $bookingBillRefundRepository,
    BookingClientRepository $bookingClientRepository,
    BookingPassengerRepository $bookingPassengerRepository,
    BookingLogRepository $bookingLogRepository,
    PromocodeRepository $promocodeRepository
    )
    {
        $this->bookingRepository            = $bookingRepository;
        $this->bookingBillRepository        = $bookingBillRepository;
        $this->bookingBillRefundRepository  = $bookingBillRefundRepository;
        $this->bookingClientRepository      = $bookingClientRepository;
        $this->bookingPassengerRepository   = $bookingPassengerRepository;
        $this->bookingLogRepository         = $bookingLogRepository;
        $this->promocodeRepository          = $promocodeRepository;
    }

    /**
     * [run description]
     *
     * @return  [type]  [return description]
     */
    public function run()
    {
        $this->loadCountries();
        $this->loadCountriesDdi();
        $this->loadOldCountries();
        $this->loadOldPackages();
        $this->reservationsImport();
    }

    /**
     * [reservationsImport description]
     *
     * @param   UploadedFile            $file
     * @param   BookingVoucherFile      $bookingVoucherFile
     *
     * @return  BookingVoucherFile
     */
    protected function reservationsImport()
    {
        $time_start     = microtime(true);
        $processed      = 0;

        $id             = 20106;
        $_entities = DB::connection('mysql2')->table("am_reservas")
        ->selectRaw('am_reservas.*,
        am_reservascontratante.nome, am_reservascontratante.data_nascimento, am_reservascontratante.documento,
        am_reservascontratante.rg, am_reservascontratante.est_emissor,
        am_reservascontratante.passaporte, am_reservascontratante.cpf, am_reservascontratante.logradouro,
        am_reservascontratante.numero, am_reservascontratante.complemento, am_reservascontratante.bairro,
        am_reservascontratante.cidade, am_reservascontratante.estado, am_reservascontratante.cep,
        am_reservascontratante.pais, am_reservascontratante.ddi, am_reservascontratante.ddd, am_reservascontratante.fone,
        am_reservascontratante.email, am_reservas_cotacoes.cotacoes')
        ->join("am_reservascontratante", "am_reservas.id", "=", "am_reservascontratante.reserva_id")
        ->leftJoin("am_reservas_cotacoes", "am_reservas.id", "=", "am_reservas_cotacoes.reserva_id")
        ->leftJoin("am_paises", "am_reservascontratante.pais", "=", "am_paises.id")
        ->orderBy("am_reservas.datacad", "asc")
        //->where("am_reservas.id", $id)
        ->get()
        ->skip($this->skip)
        ;
        $this->line("Starting ");

        foreach($_entities as $entity){
            try{
                $id             = $entity->id;
                $providerId     = null;
                $packageId      = null;
                $status         = $this->convertStatus($entity->status);
                $starts_at      = $entity->data_saida;
                $client_id      = $entity->responsavel_id;
                if($starts_at == '0000-00-00'){
                    $starts_at = '2010-01-01';
                }
                $this->line("Migrando: ". $id);

                $this->convertPackage($entity->pacote_id, $packageId, $providerId);
                if($packageId == null){
                    $this->line("Pacote antigo não convertido: ". $entity->pacote_id);
                    if($client_id == null){ continue;}
                    $this->addBookingLegacy([
                        "name"      => convertFixUtf8($this->getOldPackageName($entity->pacote_id)),
                        "status"    => $status,
                        "starts_at" => $starts_at,
                        "booking_id"=> $id,
                        "client_id" => $client_id
                    ]);
                    continue;
                }
                $payment_status = $this->convertStatus($entity->status_pagamento);
                $document_status= $this->convertStatus($entity->status_documentacao);
                $voucher_status = $this->convertStatus($entity->status_voucher);

                $offer_id       = 1113;
                $product_id     = 0;
                $product_type   = "App\Models\ShuttleBoardingLocation";
                $product_dates  = [];
                $this->currencyId = $currencyId     = $this->getCurrencyId($entity->moeda);
                $promocode_id   = null;
                if($entity->promocode != null){
                    $entity->currency_id = $currencyId;
                    $promocode_id = $this->addPromocodeLegacy($entity);
                }
                $passengers     = $entity->numpass;
                $subtotal       = $entity->valor_totalterrestre + $entity->valor_totaltransporte;
                $discount       = 0;
                $discount_promocode = $entity->valor_descontoterrestre;
                $discount_promocode_provider = null;
                $tax            = 0;
                $total          = $entity->valor_totalgeral;
                $installments   = 0;
                $quotations     = $entity->cotacoes;
                $ip             = $entity->responsavel_ip;
                $check_contract = null;
                $comments       = null;
                $refunded_at      = null;
                $canceled_at      = null;
                $expired_at      = $entity->data_vencimento;
                $created_at      = $entity->datacad;
                $updated_at      = $entity->datacad;
                $_reservation = [
                    'id'            => $id,
                    'package_id'     => $packageId,
                    'offer_id'       => $offer_id,
                    'product_id'     => $product_id,
                    'product_type'   => $product_type,
                    'product_dates'  => $product_dates,
                    'client_id'      => $client_id,
                    'currency_id'    => $currencyId,
                    'promocode_id'   => $promocode_id,
                    'passengers'     => $passengers,
                    'status'         => $status,
                    'payment_status' => $payment_status,
                    'document_status'=> $document_status,
                    'voucher_status' => $voucher_status,
                    'subtotal'       => $subtotal,
                    //'discount'       => $discount,
                    'discount_promocode' => $discount_promocode,
                    'discount_promocode_provider' => $discount_promocode_provider,
                    //'tax'            => $tax,
                    'total'          => $total,
                    'installments'   => $installments,
                    'quotations'     => $quotations,
                    'ip'             => $ip,
                    'check_contract' => $check_contract,
                    'comments'       => $comments,
                    // 'starts_at'      => $starts_at,
                    'refunded_at'    => $refunded_at,
                    'canceled_at'    => $canceled_at,
                    'expired_at'     => $expired_at,
                    'created_at'     => $created_at,
                    'updated_at'     => $updated_at
                ];
                $entityCreated   = app(Booking::class)->where("id", $id)->first();
                // This was created to prevent old bookings already imported to change product_id and product_type
                if($entityCreated){
                    unset($_reservation['product_id']);
                    unset($_reservation['product_type']);
                    unset($_reservation['product_dates']);
                    unset($_reservation['offer_id']);
                }
                $resource   = $this->bookingRepository->make($_reservation);

                $booking    = $this->bookingRepository->updateOrCreate(
                    $resource,
                    [   "id"    => $id],
                    $_reservation);
                $booking->refresh();
                if($booking){
                    switch($entity->documento){
                        case "passaporte":
                            $primary_document = "passport";
                            break;
                        case "rg":
                            $primary_document = "identity";
                            break;
                        default:
                            $primary_document = "document";
                            break;
                    }
                    if(validateDate($entity->data_nascimento)){
                        $birthdate      = $entity->data_nascimento;
                    }else{
                        $birthdate      = "1900-01-01";
                    }
                    $name           = $entity->nome;
                    $company_name   = $legal_name  = null;
                    $email          = $entity->email;
                    $address        = $entity->logradouro;
                    $number         = $entity->numero;
                    $neighborhood   = $entity->bairro;
                    
                    $type           = $this->getType($entity->responsavel_tipo);
                    switch($type){
                        case PersonType::LEGAL:
                            $_registry      = $this->consultResponsible($entity->responsavel_id);
                            if($_registry){
                                $legal_name     = $_registry->razao;
                                $cpf            = $_registry->cnpj;
                            }
                            break;
                        case PersonType::FISICAL:
                            $cpf            = $entity->cpf;
                            break;
                    }
                    
                    $complement     = $entity->complemento;
                    $identity       = $entity->rg;
                    $uf             = $entity->estado;
                    $zip            = $entity->cep;
                    $city           = $entity->cidade;
                    $state          = $entity->estado;
                    $passport       = $entity->passaporte;
                    $countryId      = $this->getCountry("$entity->pais", "iso2");
                    $phone          = $this->handlePhone($entity);
                    $language       = $this->getLanguage($entity->idioma_id);
                    $gender         = $this->getGender(null);

                    // Client
                    $_client = [
                        'booking_id'    => $booking->id,
                        'client_id'     => $client_id,
                        'name'          => utf8_encode($name),
                        'company_name'  => utf8_encode($company_name),
                        'legal_name'    => utf8_encode($legal_name),
                        'email'         => $email,
                        'phone'         => $phone,
                        'language'      => $language,
                        'birthdate'     => $birthdate,
                        'type'          => $type,
                        'identity'      => $identity,
                        'uf'            => $uf,
                        'primary_document'  => $primary_document,
                        'document'      => $cpf,   // CPF
                        'passport'      => $passport,
                        'registry'      => $cpf,
                        'gender'        => $gender,
                        'address'       => utf8_encode($address),
                        'address_number'=> $number,
                        'address_neighborhood' => utf8_encode($neighborhood),
                        'address_complement' => utf8_encode($complement),
                        'address_city' => utf8_encode($city),
                        'address_state' => utf8_encode($state),
                        'address_zip' => utf8_encode($zip),
                        'address_country' => $countryId,
                        'created_at'    => $created_at,
                        'updated_at'    => $created_at
                    ];

                    $resourceClient       = $this->bookingClientRepository->make($_client);
                    $bookingClient  = $this->bookingRepository->updateOrCreate(
                        $resourceClient,
                        [
                            "booking_id"    => $booking->id],
                        $_client);

                    // Passengers
                    $this->migratePassengers($resource, $entity);

                    // Bills
                    $this->migrateBills($resource, $client_id, $installments);
                    $_reservation["installments"] = $installments;
                    $booking    = $this->bookingRepository->updateOrCreate(
                        $resource,
                        [   "id"    => $id],
                        $_reservation);

                    // Bill Refunds
                    $this->migrateBillRefunds($resource, $resourceClient->id);

                    // Logs
                    DB::statement("DELETE from booking_logs where target_booking_id='{$resource->id}'");
                    $_logs = DB::connection('mysql2')->table("am_reservashistorico")
                    ->selectRaw('am_reservashistorico.*')
                    ->where("reserva_id", "=", $id)
                    ->orderBy("datacad", "asc")
                    ->get();;
                    if($_logs->count()){
                        foreach($_logs as $log){
                            $userId = $this->getUserByName($log->nome);
                            $_bookingLog = [
                                "target_client_id"  => $client_id,
                                "target_booking_id"  => $resource->id,
                                "provider_id"   => null,
                                "user_id"       => $userId,
                                "type"          => ($userId != 1)?"manual":"system",
                                "operation"     => null,
                                "level"         => 1,
                                "ip"            => $ip,
                                "message"       => convertFixUtf8($log->texto),
                                "created_at"    => $log->datacad,
                                "updated_at"    => $log->datacad
                            ];
                            $this->bookingLogRepository->store($_bookingLog);
                        }
                    }
                }
                $this->line("Imported {$id} ");
                $processed++;
            }catch(Exception $e){
                $message = $e->getMessage();
                $this->line($message);
            }
        }
        $elapsed    = number_format((microtime(true) - $time_start),2);
		$this->line("Finished Reservation Import. Processed: {$processed}. Elapsed time: {$elapsed}");
    }

    private function migrateBills($resource, $clientId, &$installments){
        DB::statement("DELETE from booking_bills where booking_id='{$resource->id}'");
        $_bills = DB::connection('mysql2')->table("am_recebiveis")
        ->selectRaw('am_recebiveis.*')
        ->where("origem_id", "=", $resource->id)
        ->orderBy("datacad", "asc")
        ->get();;
        $installments = 0;
        if($_bills->count()){
            foreach($_bills as $bill){
                $data_vencimento    = $bill->data_vencimento;
                if($data_vencimento == '0000-00-00'){
                    $data_vencimento = '2010-01-01 00:00:00';
                }
                $valor  = $bill->valor;
                if($bill->taxa_servico >0){
                    $valor      = $valor + (($bill->taxa_servico/100)*$valor);
                }
                $status = ($bill->pago==1)?ProcessStatus::PAID:ProcessStatus::PENDING;
                $_bookingBill = [
                    "booking_id"        => $resource->id,
                    "client_id"         => $clientId,
                    "payment_method_id" => $this->converPaymentMethod($bill->formapag_id),
                    "currency_id"       => $this->currencyId,
                    "total"             => $valor,
                    "tax"               => $bill->taxa_servico,
                    "status"            => $status,
                    "installment"       => $bill->parcela_controle,
                    "ct"                => $bill->parcela,  // Esse campo está invertido porque na época que fizemos essa implementação, não podíamos usar o campo "parcela" e criamos esse "ct"
                    "url"               => null,
                    "processor"         => $bill->processador,
                    "quotations"        => null,
                    "expires_at"        => $data_vencimento,
                    "paid_at"           => null,
                    "canceled_at"       => null,
                    "created_at"        => $bill->datacad,
                    "updated_at"        => $bill->datacad
                ];
                $this->bookingBillRepository->store($_bookingBill);
                $installments++;
            }
        }
    }

    private function migratePassengers($resource, $entity){
        //DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        //DB::statement("DELETE from booking_passengers where booking_id='{$resource->id}'");
        //DB::statement(" SET FOREIGN_KEY_CHECKS=1;");
        $_oldPassengers = DB::connection('mysql2')->table("am_reservaspassageiros")
        ->selectRaw('am_reservaspassageiros.*')
        ->where("reserva_id", "=", $resource->id)
        ->orderBy("id", "asc")
        ->get();;
        $address        = $entity->logradouro;
        $number         = $entity->numero;
        $neighborhood   = $entity->bairro;
        $type           = $this->getType($entity->responsavel_tipo);
        $complement     = $entity->complemento;
        $identity       = $entity->rg;
        $uf             = $entity->estado;
        $zip            = $entity->cep;
        $city           = $entity->cidade;
        $state          = $entity->estado;
        $created_at     = $entity->datacad;
        $updated_at     = $entity->datacad;
        $countryId      = $this->getCountry("$entity->pais", "id");
        foreach($_oldPassengers as $passenger){
            $passengerId    = $passenger->id;
            $name           = $passenger->nome;
            $email          = $passenger->email;
            $identity       = $passenger->rg;
            $cpf            = $passenger->cpf;
            $passport       = $passenger->passaporte;

            $phone          = $this->handlePhone($passenger);
            $gender         = $this->getGender(null);
            switch($passenger->documento){
                case "passaporte":
                    $primary_document = "passport";
                    break;
                case "rg":
                    $primary_document = "identity";
                    break;
                default:
                    $primary_document = "identity";
                    break;
            }
            if(validateDate($passenger->data_nascimento)){
                $birthdate      = $passenger->data_nascimento;
            }else{
                $birthdate      = "1900-01-01";
            }
            if($cpf == null){
                $cpf = "";
            }
            if($passport == null){
                $passport = "";
            }
            if($identity == null){
                $identity = "";
            }
            $name = utf8_encode($name);
            $_passenger = [
                'id'            => $passengerId,
                'booking_id'    => $resource->id,
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone,
                'birthdate'     => $birthdate,
                'primary_document'  => $primary_document,
                'document'      => $cpf,   // CPF
                'passport'      => $passport,
                'identity'      => $identity,
                'uf'            => $uf,
                'address'       => utf8_encode($address),
                'address_number'=> $number,
                'address_neighborhood' => utf8_encode($neighborhood),
                'address_complement' => utf8_encode($complement),
                'address_city' => utf8_encode($city),
                'address_state' => utf8_encode($state),
                'address_zip' => utf8_encode($zip),
                'address_country' => $countryId,
                'created_at'    => $created_at,
                'updated_at'    => $created_at
            ];
            $resourcePassenger  = $this->bookingPassengerRepository->make($_passenger);
            $bookingPassenger   = $this->bookingPassengerRepository->updateOrCreate(
                $resourcePassenger,
                [   "booking_id"    => $resource->id,
                    "id"            => $passengerId],
                $_passenger);
        }
    }

    private function migrateBillRefunds($resource, $clientId){
        DB::statement("DELETE from booking_bill_refunds where booking_id='{$resource->id}'");
        $_billRefunds = DB::connection('mysql2')->table("am_reservasestorno")
        ->selectRaw('am_reservasestorno.*')
        ->where("reserva_id", "=", $resource->id)
        ->get();;
        if($_billRefunds->count()){
            foreach($_billRefunds as $billRefund){
                $_bookingBillRefund = [
                    "booking_id"        => $resource->id,
                    "booking_bill_id"   => null,
                    "user_id"           => 1,
                    "value"             => 0,
                    "refunded_at"       => $billRefund->data_estorno,
                    "status"            => ProcessStatus::CONFIRMED,
                    "history"           => convertFixUtf8($billRefund->historico),
                    "jon_object"        => $billRefund->objeto_json,
                    "created_at"        => $billRefund->data_estorno,
                    "updated_at"        => $billRefund->data_estorno
                ];
                $this->bookingBillRefundRepository->store($_bookingBillRefund);
            }
        }
    }

    private function addBookingLegacy($_data){
        DB::statement("DELETE from booking_legacies where booking_id='{$_data["booking_id"]}'");
        BookingLegacies::create($_data);
    }

    private function addPromocodeLegacy($entity){
        $name   = $entity->promocode ." - ". $entity->nome;
        $_promocode = [
            "promocode_group_id"    => 1,
            "payment_method_id"     => 27,
            "currency_id"           => $entity->currency_id,
            "name"                  => $name,
            "code"                  => $entity->promocode,
            "discount_value"        => $entity->valor_descontoterrestre,
            "stock"                 => 1,
            "usages"                => 1,
            "max_installments"      => 0,
            "cancels_cash_discount" => 1,
            "expires_at"            => $entity->data_vencimento
        ];
        $resourcePromocode  = $this->promocodeRepository->make($_promocode);
        $promocode          = $this->promocodeRepository->updateOrCreate(
            new Promocode(),
            [   "code"  => $entity->promocode, "name"   => $name],
            $_promocode);
        return $promocode->id;
    }

    private function consultResponsible($responsibleId){
        $_oldRegistry = DB::connection('mysql2')->table("am_cadastros")
        ->selectRaw('am_cadastros.*')
        ->where("id", "=", $responsibleId)
        ->orderBy("id", "asc")
        ->first();;
        return $_oldRegistry;
    }
}
