<?php

namespace App\Models;

use App\Base\BaseModel;
use App\Enums\Currency;
use App\Enums\DocumentType;
use App\Enums\ProcessStatus;
use App\Models\Relationships\BelongsToClient;
use App\Models\Relationships\BelongsToCurrency;
use App\Models\Relationships\BelongsToCurrencyOrigin;
use App\Models\Relationships\BelongsToOffer;
use App\Models\Relationships\BelongsToPackage;
use App\Models\Relationships\BelongsToPaymentMethod;
use App\Models\Relationships\BelongsToPromocode;
use App\Models\Relationships\HasManyBookingBillPaymentPendings;
use App\Models\Relationships\HasManyBookingBillRefunds;
use App\Models\Relationships\HasManyBookingBills;
use App\Models\Relationships\HasManyBookingLogs;
use App\Models\Relationships\HasManyBookingPassengerAdditionals;
use App\Models\Relationships\HasManyBookingPassengers;
use App\Models\Relationships\HasManyBookingProducts;
use App\Models\Relationships\HasManyBookings;
use App\Models\Relationships\HasManyBookingVoucherFiles;
use App\Models\Relationships\HasManyBookingVouchers;
use App\Models\Relationships\HasManyTransactions;
use App\Models\Relationships\HasOneBookingClient;
use App\Models\Relationships\HasOneBookingOffer;
use App\Models\Traits\HasDateLabels;
use App\Models\Traits\HasProcessStatusLabels;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use stdClass;

class Booking extends BaseModel
{
    use BelongsToPackage,
        BelongsToOffer,
        BelongsToPaymentMethod,
        BelongsToPromocode,
        BelongsToClient,
        BelongsToCurrency,
        BelongsToCurrencyOrigin,
        HasOneBookingClient,
        HasManyBookings,
        HasManyBookingLogs,
        HasManyBookingPassengers,
        HasProcessStatusLabels,
        HasOneBookingOffer,
        HasManyBookingProducts,
        HasManyBookingPassengerAdditionals,
        HasManyBookingVoucherFiles,
        HasManyBookingBills,
        HasManyBookingBillPaymentPendings,
        HasManyBookingBillRefunds,
        HasManyBookingVouchers,
        HasManyTransactions,
        HasDateLabels,
        HasFactory;

    protected $fillable = [
        'id',
        'package_id',
        'offer_id',
        'product_id',
        'product_type',
        'product_dates',
        'client_id',
        'currency_id',
        'promocode_id',
        'promocode_provider_id',
        'passengers',
        'status',
        'payment_status',
        'document_status',
        'voucher_status',
        'subtotal',
        'discount',
        'discount_promocode',
        'discount_promocode_provider',
        'tax',
        'total',
        'installments',
        'quotations',
        'ip',
        'check_contract',
        'comments',
        'expired_at',
        'starts_at',
        'refunded_at',
        'canceled_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'quotations' => 'array',
        'product_dates' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'starts_at' => 'datetime',
        'refunded_at' => 'datetime',
        'canceled_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    protected $_searchable = [
        'booking_client_name' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.name',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_client_document' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.document',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_client_email' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.email',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_client_identity' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.identity',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_client_document' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.document',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_client_birthdate' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.birthdate',
            'join_field_ct'     => '=',
            'join_find'         => '%1'
        ],
        'booking_client_is_active' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.is_active',
            'join_field_ct'     => '=',
            'join_find'         => '%1'
        ],
        'booking_client_passport' => [
            'join_table'        => 'clients as c',
            'join_from'         => 'bookings.client_id',
            'join_condition'    => '=',
            'join_to'           => 'c.id',
            'join_field'        => 'c.passport',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_client_phone' => [
            'join_table'        => 'booking_clients as bc',
            'join_from'         => 'bookings.id',
            'join_condition'    => '=',
            'join_to'           => 'bc.booking_id',
            'join_field'        => 'bc.phone',
            'join_field_ct'     => 'LIKE',
            'join_find'         => '%%1%'
        ],
        'booking_offer_provider_id' => [
            'join_table'        => 'offers as o',
            'join_from'         => 'bookings.offer_id',
            'join_condition'    => '=',
            'join_to'           => 'o.id',
            'join_field'        => 'o.provider_id',
            'join_field_ct'     => '=',
            'join_find'         => '%1'
        ],
        'booking_offer_company_id' => [
            'join_table'        => 'offers as o',
            'join_from'         => 'bookings.offer_id',
            'join_condition'    => '=',
            'join_to'           => 'o.id',
            'join_field'        => 'o.company_id',
            'join_field_ct'     => '=',
            'join_find'         => '%1'
        ]
    ];

    /**
     * [isCanceled description]
     *
     * @return  bool    [return description]
     */
    public function isCanceled(): bool
    {
        return $this->status == ProcessStatus::CANCELED;
    }

    /**
     * [canBeCanceled description]
     *
     * @return  bool    [return description]
     */
    public function canBeCanceled(): bool
    {
        return $this->status != ProcessStatus::CANCELED;
    }

    /**
     * [canBeDeleted description]
     *
     * @return  bool    [return description]
     */
    public function canBeDeleted(): bool
    {
        return $this->status != ProcessStatus::CONFIRMED || $this->status == ProcessStatus::CANCELED || $this->status == ProcessStatus::PENDING || $this->status == ProcessStatus::SUSPENDED;
    }

    /**
     * [getName description]
     *
     * @return  string  [return description]
     */
    public function getName(): ?string
    {
        return $this->package->getTitle();
    }

    /**
     * [getDates description]
     *
     * @return  array   [return description]
     */
    public function getDates(): array
    {
        if (empty($this->product_dates)) {
            return [];
        }

        if (is_string($this->product_dates)) {
            return json_decode($this->product_dates);
        }

        return $this->product_dates;
    }

    /**
     * [getFormattedDates description]
     *
     * @return  array   [return description]
     */
    public function getFormattedDates(): array
    {
        $_dates = [];
        if (empty($this->product_dates)) {
            return $_dates;
        }

        if (is_string($this->product_dates)) {
            $_productDates = json_decode($this->product_dates);
            foreach($_productDates as $productDates){
                $formattedDate = Carbon::createFromFormat("Y-m-d", $productDates)->format("d/m/Y");
                $_dates[]       = $formattedDate;
            }
        }

        return $_dates;
    }

        /**
     * [getProductByDates description]
     *
     * @return  array   [return description]
     */
    public function getProductByDates($_dates): ?Collection
    {
        $_products = $this->bookingProducts()->whereIn("date", $_dates)->get();

        return $_products;
    }

    /**
     * [getQuotations description]
     *
     * @return  array   [return description]
     */
    public function getQuotations(): array
    {
        if (empty($this->quotations)) {
            return [];
        }

        if (is_string($this->quotations)) {
            return json_decode($this->quotations, true);
        }

        return $this->quotations;
    }

    /**
     * [getClientName description]
     *
     * @return  string  [return description]
     */
    public function getClientName(): ?string
    {
        return $this->bookingClient->name ?? null;
    }

    /**
     * [getClientPhone description]
     *
     * @return  string  [return description]
     */
    public function getClientPhone(): ?string
    {
        $ddi = ddi($this->bookingClient->address_country);
        $phone = phone($this->bookingClient->phone, $ddi);

        return "{$ddi} {$phone}";
    }

    /**
     * [getClientAddress description]
     *
     * @return  string  [return description]
     */
    public function getClientAddress(): ?string
    {
        return "{$this->bookingClient->address}, {$this->bookingClient->address_number}, {$this->bookingClient->address_neighborhood}, " . city($this->bookingClient->address_city) . ", " . country($this->bookingClient->address_country) . ", {$this->bookingClient->address_zip}";
    }

    /**
     * [getDigitalSigned description]
     *
     * @return  array   [return description]
     */
    public function getDigitalSigned($type=0): string
    {
        if($this->check_contract == null){ return null;}
        $_digitalSigned = explode(",", $this->check_contract);
        switch($type){
            case 1:
                return Carbon::createFromFormat("Y-m-d H:i:s", $_digitalSigned[0])->format("d/m/Y H:i:s");
                break;
            case 2:
                return $_digitalSigned[1];
                break;
            case 3:
                $dateHour = Carbon::createFromFormat("Y-m-d H:i:s", $_digitalSigned[0])->format("d/m/Y H:i:s");
                return $dateHour ." IP:". $_digitalSigned[1];
                break;
            default:
            break;
        }
        return $this->check_contract;
    }

    /**
     * [getInclusions description]
     *
     * @return  array  [return description]
     */
    public function getInclusions(): ?array
    {
        return [];
    }

    /**
     * [getObservations description]
     *
     * @return  array  [return description]
     */
    public function getObservations(): ?array
    {
        return [];
    }

    /**
     * [getPassengers description]
     *
     * @return  array  [return description]
     */
    public function getPassengers(): ?array
    {
        return [];
    }

    /**
     * [getBills description]
     *
     * @return  array  [return description]
     */
    public function getBills()
    {
        return $this->bookingBills;
    }

    /**
     * [getTotalQuoted description]
     *
     * @return  array   [return description]
     */
    public function getTotalQuoted(): ?array
    {
        $_totalQuotes = [];
        $_quotations = json_decode($this->quotations, false);
        if(is_array($_quotations)){
            foreach($_quotations as $quote){
                $_totalQuotes[] = $quote->name ." - ". ($quote->quotation + $quote->spread) .",";
            }
        }
        return $_totalQuotes;
    }

    /**
     * [getTotalPriceCreditCardIntallments description]
     *
     * @return  float   [return description]
     */
    public function getTotalPriceCreditCardIntallments(): ?float
    {
        return null;
    }

    /**
     * [getBillsName description]
     *
     * @return  string  [return description]
     */
    public function getBillsName(): ?string
    {
        return null;
    }

    /**
     * [getBillsLastCreditCardNumbers description]
     *
     * @return  string  [return description]
     */
    public function getBillsLastCreditCardNumbers(): ?string
    {
        return null;
    }

    /**
     * [getBillsLastCreditCardNumbers description]
     *
     * @return  string  [return description]
     */
    public function getBillsCreditCardAuthNumber(): ?string
    {
        return null;
    }

    /**
     * [getBillsLastCreditCardNumbers description]
     *
     * @return  string  [return description]
     */
    public function hasBillsCreditCardAuthNumber(): bool
    {
        return true;
    }

    /**
     * [getTotalInstallmentsCreditCard description]
     *
     * @return  [type]  [return description]
     */
    public function getTotalInstallmentsCreditCard(): ?int
    {
        return null;
    }

    /**
     * [getPriceEachCreditCardInstallment description]
     *
     * @return  float   [return description]
     */
    public function getPriceEachCreditCardInstallment(): ?float
    {
        return null;
    }

    /**
     * [getTotal description]
     *
     * @return  float   [return description]
     */
    public function getTotal(): ?float
    {
        return $this->total;
    }

    /**
     * [getTotalLabel description]
     *
     * @return  float   [return description]
     */
    public function getTotalLabel(): ?string
    {
        $total = moneyDecimal($this->getTotal());
        return $total;
    }

    /**
     * [getCity description]
     *
     * @return  string  [return description]
     */
    public function getCity(): ?string
    {
        return $this->package->getCity();
    }

    /**
     * [getProductName description]
     *
     * @return  string  [return description]
     */
    public function getProductName($date = null): ?string
    {
        if (empty($this->product_type)) {
            return null;
        }

        $product = (new $this->product_type)->find($this->product_id);

        if (empty($product)) {
            return null;
        }

        if (!empty($date)) {
            $date = Carbon::createFromFormat('Y-m-d', $date);
            return $product->getTitle() . ' – ' . $date->format('d/m/Y');
        }

        return $product->getTitle();
    }

    /**
     * [getProductTypeName description]
     *
     * @return  string  [return description]
     */
    public function getProductTypeName(): ?string
    {
        if (empty($this->product_type)) {
            return null;
        }
        $title = null;

        switch($this->product_type){
            case  BustripBoardingLocation::class:
                $title = __('frontend.pacotes.bate_volta');
                break;
            case  HotelAccommodation::class:
                $title = __('frontend.pacotes.hotel');
                break;
            case  ShuttleBoardingLocation::class:
                $title = __('frontend.pacotes.shuttle');
                break;
            case  LongtripAccommodationsPricing::class:
                $title = __('frontend.pacotes.longtrip');
                break;
            default:
                $title = str_replace("App\Models\\", "", $this->product_type);
        }

        return $title;
    }

    /**
     * [getProduct description]
     *
     * @return  [type]  [return description]
     */
    public function getProduct()
    {
        if (empty($this->product_type)) {
            return null;
        }

        $product = (new $this->product_type)->find($this->product_id);

        if (empty($product)) {
            return null;
        }

        return $product;
    }

    /**
     * [getProductName description]
     *
     * @return  string  [return description]
     */
    public function getProductPrice($date = null): ?string
    {
        $product = (new $this->product_type)->find($this->product_id);

        if (empty($product)) {
            return null;
        }

        if (!empty($date)) {
            return $product->getPrice($date);
        }

        return $product->getPrice();
    }

    /**
     * [getProductPriceNet description]
     *
     * @return  string  [return description]
     */
    public function getProductPriceNet($date = null): ?string
    {
        $product = (new $this->product_type)->find($this->product_id);

        if (empty($product)) {
            return null;
        }

        if (!empty($date)) {
            return $product->getPriceNet($date);
        }

        return $product->getPriceNet();
    }

    /**
     * [getProductPriceSaleCoefficientValue description]
     *
     * @return  string  [return description]
     */
    public function getProductPriceSaleCoefficientValue($date = null): ?string
    {
        $product = (new $this->product_type)->find($this->product_id);

        if (empty($product)) {
            return null;
        }

        if (!empty($date)) {
            return $product->getPriceSaleCoefficientValue($date);
        }

        return $product->getPriceSaleCoefficientValue();
    }

    /**
     * [clientHasPassport description]
     *
     * @return  bool    [return description]
     */
    public function clientHasPassport(): bool
    {
        return $this->bookingClient->primary_document == DocumentType::PASSPORT && $this->bookingClient->passport;
    }

    /**
     * [getClientDocument description]
     *
     * @return  string  [return description]
     */
    public function getClientDocument(): ?string
    {
        return $this->bookingClient->document;
    }

    /**
     * [getClientIdentitiy description]
     *
     * @return  string  [return description]
     */
    public function getClientIdentity(): ?string
    {
        return "{$this->bookingClient->identity} - {$this->bookingClient->uf}";
    }

    public function getClientBirthdate(): ?string
    {
        return $this->bookingClient->birthdate->format('d/m/Y');
    }

    /**
     * [getClientDocument description]
     *
     * @return  string  [return description]
     */
    public function getClientPassport(): ?string
    {
        return $this->bookingClient->passport;
    }

    public function getFrontendStatusClass(): ?string
    {
        if ($this->status == ProcessStatus::PENDING) {
            return 'status-pendente';
        }

        if ($this->status == ProcessStatus::CONFIRMED) {
            return 'status-confirmada';
        }

        if ($this->status == ProcessStatus::CANCELED) {
            return 'status-cancelada';
        }
        return $this->status;
    }

    public function getFrontendVoucherStatusClass(): ?string
    {
        if ($this->voucher_status == ProcessStatus::PENDING) {
            return 'status-pendente';
        }

        if ($this->voucher_status == ProcessStatus::RELEASED) {
            return 'status-confirmada';
        }
    }

    public function getFrontendPaymentStatusClass(): ?string
    {
        return null;
    }

    public function getFrontendDocumentStatusClass(): ?string
    {
        return null;
    }

    /**
     * [getBillsTotal description]
     *
     * @return  float   [return description]
     */
    public function getBillsTotal(): float
    {
        $total = 0;

        $bookingBills = $this->bookingBills()
            ->where('status', '!=', ProcessStatus::CANCELED)
            ->get();

        foreach ($bookingBills as $bill) {
            $total += $bill->getTotalWithTaxes();
        }

        return $total;
    }

    /**
     * [getTotalPaid description]
     *
     * @return  float   [return description]
     */
    public function getBillsTotalPaid(): float
    {
        $total = 0;

        $bookingBills = $this->bookingBills()
            ->where('status', ProcessStatus::PAID)
            ->get();

        foreach ($bookingBills as $bill) {
            $total += $bill->getTotalWithTaxes();
        }

        return $total;
    }

    /**
     * [getProductsTotal description]
     *
     * @return  float   [return description]
     */
    public function getProductsTotal(): float
    {
        $total = 0;

        foreach ($this->bookingProducts as $bookingProduct) {
            $total += $bookingProduct->price;
        }

        foreach ($this->bookingPassengerAdditionals as $bookingPassengerAdditional) {
            $total += $bookingPassengerAdditional->price;
        }

        return $total;
    }

    /**
     * [getUnbilledTotal description]
     *
     * @return  float   [return description]
     */
    public function getUnbilledTotal(): float
    {
        return $this->getProductsTotal() - $this->getBillsTotal();
    }

    /**
     * [hasUnbilled description]
     *
     * @return  bool    [return description]
     */
    public function hasUnbilled(): bool
    {
        return $this->getUnbilledTotal() >= 0.05;
    }

    /**
     * [isForeigner description]
     *
     * @return  bool    [return description]
     */
    public function isForeigner(): bool
    {
        return $this->currency->code != Currency::REAL;
    }

    /**
     * [isPaid description]
     *
     * @return  bool    [return description]
     */
    public function isPaid(): bool
    {
        return $this->payment_status == ProcessStatus::CONFIRMED;
    }

    /**
     * [setStatus description]
     *
     * @return  bool    [return description]
     */
    public function setPaymentStatus($paymentStatus = ProcessStatus::PENDING): string
    {
        return $this->payment_status = $paymentStatus;
    }

    /**
     * [setStatus description]
     *
     * @return  bool    [return description]
     */
    public function setStatus($status = ProcessStatus::PENDING): string
    {
        return $this->status = $status;
    }

    /**
     * [getLongtripBoardingLocation description]
     *
     * @return  [type]  [return description]
     */
    public function getLongtripBoardingLocation()
    {
        $bookingProduct = $this->bookingProducts->where('product_type', LongtripBoardingLocation::class)->first();
        if(!$bookingProduct){ return false;}
        $longtripBoardingLocation = LongtripBoardingLocation::find($bookingProduct->product_id);

        return $longtripBoardingLocation;
    }

    /**
     * [longtripBAccommodation description]
     *
     * @return  [type]  [return description]
     */
    public function getLongtripAccommodations()
    {
        $bookingProduct = $this->bookingProducts()->where('product_type', LongtripAccommodation::class)->first();
        $longtripBAccommodation = LongtripAccommodation::find($bookingProduct->product_id);

        return $longtripBAccommodation;
    }

    /**
     * [hasPaidBills description]
     *
     * @return  bool    [return description]
     */
    public function hasPaidBills(): bool
    {
        return $this->bookingBills()->where('status', 'paid')->count() > 0;
    }

    /**
     * [canChangePaymentMethod description]
     *
     * @return  bool    [return description]
     */
    public function canChangePaymentMethod(): bool
    {
        return !$this->isCanceled() && !$this->hasPaidBills();
    }

    /**
     * [canViewInvoice description]
     *
     * @return  bool    [return description]
     */
    public function canViewInvoice(): bool
    {
        return !$this->hasPaidBills();
    }

    /**
     * [getPaymentMethods description]
     *
     * @return  bool    [return description]
     */
    public function getPaymentMethods(): object
    {
        $_paymentMethods = new stdClass();
        foreach ($this->bookingBills as $key => $bookingBill){
            $currency   = $this->currency()->first();
            $tempObject = new stdClass();
            $tempObject->booking_id         = $this->id;
            $tempObject->bill_id            = $bookingBill->id;
            $tempObject->type               = $bookingBill->paymentMethod->type;
            $tempObject->installment        = $bookingBill->installment;
            $tempObject->payment_method_id  = $bookingBill->payment_method_id;
            $tempObject->status             = $bookingBill->status;
            $tempObject->processor          = $bookingBill->processor;
            $tempObject->paid_at            = $bookingBill->paid_at;
            $tempObject->created_at         = $bookingBill->created_at;
            $tempObject->currency           = $currency->code;
            $tempObject->total              = moneyDecimal($bookingBill->total);
            $_paymentMethods->{$bookingBill->paymentMethod->type}[] = $tempObject;

        }
        return $_paymentMethods;
    }
}
