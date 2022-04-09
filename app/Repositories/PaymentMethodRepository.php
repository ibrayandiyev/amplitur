<?php

namespace App\Repositories;

use App\Enums\PaymentMethod as EnumsPaymentMethod;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\Package;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Libs\Itau\Itau;
use Libs\Itau\Itaucripto;

class PaymentMethodRepository extends Repository
{
    /**
     * @var Package
     */
    protected $package;

    protected $packagePivotColumns = [
        'id',
        'processor',
        'tax',
        'discount',
        'limiter',
        'max_installments',
        'first_installment_billet',
        'first_installment_billet_method_id',
        'is_active',
    ];

    public function __construct(PaymentMethod $model)
    {
        $this->model = $model;
    }

    /**
     * [getInternationals description]
     *
     * @return  Collection[return description]
     */
    public function getNationals(): ?Collection
    {
        if (!empty($this->package)) {
            $query = $this->package->paymentMethods()->withPivot($this->packagePivotColumns);
        } else {
            $query = $this->model;
        }

        $query = $query->where('category', 'national');

        return $query->get();
    }

    /**
     * [getInternationals description]
     *
     * @return  Collection[return description]
     */
    public function getInternationals(): ?Collection
    {
        if (!empty($this->package)) {
            $query = $this->package->paymentMethods()->withPivot($this->packagePivotColumns);
        } else {
            $query = $this->model;
        }

        $query = $query->where('category', 'international');

        return $query->get();
    }

    /**
     * [setPackage description]
     *
     * @param   Package                  $package  [$package description]
     *
     * @return  PaymentMethodRepository            [return description]
     */
    public function setPackage(Package $package): PaymentMethodRepository
    {
        $this->package = $package;

        return $this;
    }

    /**
     * [getBilletPaymentMethods description]
     *
     * @return  Collection[return description]
     */
    public function getBilletPaymentMethods(): Collection
    {
        $paymentMethods = $this->model
            ->whereIn('code', ['boleto-bancario-bradesco', 'boleto-bancario-itau'])
            ->get();

        return $paymentMethods;
    }

    /**
     * [getShoplineHash description]
     *
     * @param   Booking      $booking      [$booking description]
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function getShoplineHash(Booking $booking, BookingBill $bookingBill)
    {
        try {
            $itau = new Itaucripto;

            $order = sprintf("%02d", $bookingBill->ct) . sprintf("%06d", $booking->id);
            $comments = "Reserva {$booking->id}";
            $subscriptionNumber = "01";

            $taxvat = str_replace(".", "", $booking->getClientDocument());
            $taxvat = str_replace("-", "", $taxvat);
            
            $neighborthood = ($booking->bookingClient->address_neighborthood != null)?$booking->bookingClient->address_neighborthood:"";
            $hash = $itau->geraDados(
                Itau::$codEmp,
                $order,
                moneyDecimal($bookingBill->getBrlTotal()),
                $comments,
                Itau::$chave,
                $booking->getClientName(),
                $subscriptionNumber,
                $taxvat,
                $booking->bookingClient->address,
                $neighborthood,
                str_replace('-', '', str_replace('.', '', $booking->bookingClient->address_zip)),
                $booking->bookingClient->address_city,
                $booking->bookingClient->address_state,
                Carbon::now()->addDays(EnumsPaymentMethod::PM_BILLET_DUEDAYS)->format('dmY'),
                Itau::$urlRetorno,
                '',
                '',
                ''
            );
            return $hash;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}