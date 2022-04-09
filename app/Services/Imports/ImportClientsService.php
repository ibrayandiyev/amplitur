<?php

namespace App\Services\Imports;

use App\Base\BaseService;
use App\Enums\PersonType;
use App\Models\BookingVoucherFile;
use App\Repositories\BookingVoucherRepository;
use App\Repositories\CityRepository;
use App\Repositories\ClientLogRepository;
use App\Repositories\ClientRepository;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ImportClientsService extends BaseService
{
    use ImportLegacyTrait;
    private $_countries;
    
    /**
     * @var BookingVoucherRepository
     */
    protected $repository;

    /**
     * @var BookingVoucherRepository
     */
    protected $bookingVoucherFileRepository;
    
    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    /**
     * @var ClientLogRepository
     */
    protected $clientLogRepository;


    public function __construct(
    ClientRepository $clientRepository,
    ClientLogRepository $clientLogRepository,
    CityRepository $cityRepository
    )
    {
        $this->clientRepository         = $clientRepository;
        $this->clientLogRepository      = $clientLogRepository;
        $this->cityRepository           = $cityRepository;
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
        $this->clientsImport();
    }

    /**
     * [clientsImport description]
     *
     * @param   UploadedFile            $file
     * @param   BookingVoucherFile      $bookingVoucherFile
     *
     * @return  BookingVoucherFile
     */
    protected function clientsImport()
    {
        $time_start     = microtime(true);
        $processed      = 0;

        $id             = 17356;
        $_users = DB::connection('mysql2')->table("am_cadastros")
        ->selectRaw('am_cadastros.*, 
        am_usuarios.login, am_usuarios.ativo, am_usuarios.validado,
        am_paises.iso3,
        am_cadastrosenderecos.logradouro, am_cadastrosenderecos.numero, am_cadastrosenderecos.complemento,
        am_cadastrosenderecos.bairro, am_cadastrosenderecos.cidade, am_cadastrosenderecos.estado, 
        am_cadastrosenderecos.cep, am_cadastrosenderecos.pais_id ')
        ->join("am_usuarios", "am_cadastros.id", "=", "am_usuarios.rel_id")
        ->leftJoin("am_cadastrosenderecos", "am_cadastros.id", "=", "am_cadastrosenderecos.cadastro_id")
        ->leftJoin("am_paises", "am_cadastrosenderecos.pais_id", "=", "am_paises.id")
        //->limit(10)
        //->where("am_cadastros.id", $id)
        ->orderBy("datacad", "asc")
        ->get()
        ->skip($this->skip);

        $this->line("Starting ");

        foreach($_users as $user){
            $id             = $user->id;
            $name           = $user->nome;
            $company_name   = $legal_name  = $user->razao;
            $email          = $user->email;
            $retry          = 0;
            $_phones        = null;
            if($email  == null){
                continue;
            }
            if(validateDate($user->data_nascimento)){
                $birthdate      = $user->data_nascimento;
            }else{
                $birthdate      = "1900-01-01";
            }
            switch($user->documento){
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
            $uf             = $user->est_emissor;
            $passport       = $user->passaporte;
            $registry       = null;
            $gender         = $this->getGender($user->sexo_id);
            $language       = $this->getLanguage($user->idioma_id);
            $username       = convertFixUtf8($user->login);
            $password       = Hash::make(123);
            $is_active      = $user->ativo;
            $is_valid       = $user->validado;
            $type           = $this->getType($user->tipo);
            $registry       = ($type == PersonType::LEGAL)?$user->cnpj:null;
            $document       = ($type == PersonType::FISICAL)?$user->cpf:null;
            $country        = $this->getCountry($user->iso3, "iso2");
            switch($country){
                case "BR":
                    $identity       = convertFixUtf8($user->rg);
                    break;
                default:
                    $identity       = convertFixUtf8($user->passaporte);
                    break;

            }
            $validation_token   = $remember_token = $verification_token = null;
            $responsible_name   = convertFixUtf8($user->nome_responsavel);
            $responsible_email  = $user->email;
            $validation_token   = null;
            $is_newsletter_subscriber = $user->newsletter;
            $created_at     = $updated_at = $verified_at = $user->datacad;

            $address        = $user->logradouro;
            $number         = convertFixUtf8($user->numero);
            $neighborhood   = $user->bairro;
            $complement     = $user->complemento;
            $zip            = $user->cep;
            if($user->pais_id == 30){
                // Brasil
                $city           = $this->getCity($user->cidade);
            }else{
                $city           = $user->cidade;
            }
            $state          = convertFixUtf8($user->estado);

            $_client = [
                'id'            => $id,
                'name'          => convertFixUtf8($name),
                'company_name'  => convertFixUtf8($company_name),
                'legal_name'    => convertFixUtf8($legal_name),
                'email'         => $email,
                'birthdate'     => $birthdate,
                'identity'      => $identity,
                'uf'            => $uf,
                'document'      => $document,
                'passport'      => $passport,
                'registry'      => $registry,
                'gender'        => $gender,
                'language'      => $language,
                'username'      => $username,
                'password'      => $password,
                'is_active'     => $is_active,
                'is_valid'      => $is_valid,
                'validation_token' => $validation_token,
                'is_newsletter_subscriber' => $is_newsletter_subscriber,
                'type'          => $type,
                'primary_document' => $primary_document,
                'country'       => $country,
                'responsible_name' => $responsible_name,
                'responsible_email' => $responsible_email,
                'remember_token' => $remember_token,
                'verification_token' => $verification_token,
                'created_at'    => $created_at,
                'updated_at'    => $updated_at,
                'verified_at'   => $verified_at,
                'address'   => [
                    'name'          => "From 2.0",
                    'address'       => convertFixUtf8($address),
                    'number'        => $number,
                    'neighborhood'  => convertFixUtf8($neighborhood),
                    'complement'    => convertFixUtf8($complement),
                    'zip'           => $zip,
                    'city'          => convertFixUtf8($city),
                    'state'         => $state,
                    'country'       => $country
                ]
            ];
		    $this->line("Importing {$id} - {$name}");
            try{
                $resource = $this->clientRepository->make($_client);
                $client = $this->clientRepository->updateOrCreate(
                    $resource, ["id" => $id], $_client);
            }catch(Exception $e){
                $retry=1;
                if(strstr($e->getMessage(), "clients_email_unique")){
                    $_client['email']   = "dup_". $_client['email'];
                    $this->line("Fixing email duplicated {$id} - {$_client['email']}");
                }
            }
            if($retry){
                try{
                    $resource = $this->clientRepository->make($_client);
                    $client = $this->clientRepository->updateOrCreate(
                        $resource, ["id" => $id], $_client);
                }catch(Exception $e){
                    dd($e->getMessage());
                    continue;
                }
            }
            
            $this->clientRepository->handlesAddressImport($resource, $_client['address']);

            $_phones = $this->handlePhones($user);
            $primary = 1;
            if($_phones != null){
                foreach($_phones as $phone){
                    $phone["is_primary"]   = $primary;
                    $this->clientRepository->handlesContactImport($resource, $phone);
                    $primary = 0;
                }
            }

            DB::statement("DELETE from client_logs where target_client_id='{$resource->id}'");
            $_logs = DB::connection('mysql2')->table("am_cadastroshistorico")
            ->selectRaw('am_cadastroshistorico.*')
            ->where("cadastro_id", "=", $id)
            ->orderBy("datacad", "asc")
            ->get();;
            if($_logs->count()){
                foreach($_logs as $log){
                    $userId = $this->getUserByName($log->nome);
                    $type = "system";
                    if($userId != 1){
                        $type = "manual";
                    }
                    $_clientLog = [
                        "target_client_id"  => $resource->id,
                        "user_id"   => $userId,
                        "type"      => $type,
                        "level"     => 1,
                        "message"   => convertFixUtf8($log->texto),
                        "created_at"    => $log->datacad,
                        "updated_at"    => $log->datacad
                    ];
                    $this->clientLogRepository->store($_clientLog);
                }
            }
		    $this->line("Imported {$id} - {$name}");
            $processed++;
        }
        $elapsed    = number_format((microtime(true) - $time_start),2);
		$this->line("Finished Client Import. Processed: {$processed}. Elapsed time: {$elapsed}");
    }

}
