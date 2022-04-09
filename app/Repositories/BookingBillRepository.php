<?php

namespace App\Repositories;

use App\Enums\Processor;
use App\Enums\ProcessStatus;
use App\Enums\Transactions;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Repositories\Traits\Bookings\BookingBillPayments;
use App\Repositories\Traits\Bookings\BookingBillPaypalPayments;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BookingBillRepository extends Repository
{
    use BookingBillPayments,
    BookingBillPaypalPayments;

    /**
     * @var Booking
     */
    protected $booking;

    /**
     * @var BookingLogRepository
     */
    protected $logging;

    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * @var bool
     */
    protected $skipMoneyFormat = false;

    public function __construct(BookingBill $model)
    {
        $this->model                    = $model;
        $this->logging                  = app(BookingLogRepository::class);
        $this->bookingRepository        = app(BookingRepository::class);
        $this->transactionRepository    = app(TransactionRepository::class);
    }

    protected function getAuthUser(): ?int
    {
        return !empty(user()) && (user() instanceof User) ? user()->id : null;
    }

    /**
     * [setBooking description]
     *
     * @param   Booking                     $booking  [$booking description]
     *
     * @return  BookingPassengerRepository            [return description]
     */
    public function setBooking(Booking $booking): BookingBillRepository
    {
        $this->booking = $booking;

        return $this;
    }

    /**
     * [setBookingRepository description]
     *
     * @param   BookingRepository                     $bookingRepository  [$booking description]
     *
     * @return  BookingPassengerRepository            [return description]
     */
    public function setBookingRepository(BookingRepository $booking): BookingBillRepository
    {
        $this->bookingRepository = $booking;

        return $this;
    }

    /**
     * [skipMoneyFormat description]
     *
     * @return  BookingBillRepository[return description]
     */
    public function skipMoneyFormat(bool $skipMoneyFormat = true): BookingBillRepository
    {
        $this->skipMoneyFormat = $skipMoneyFormat;

        return $this;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        if (!empty($this->booking)) {
            $attributes['booking_id'] = $this->booking->id;
            $attributes['client_id'] = $this->booking->client_id;
            $attributes['currency_id'] = $this->booking->currency_id;
            $attributes['quotations'] = $this->booking->quotations;
        }

        if (!$this->skipMoneyFormat) {
            $attributes['total'] = sanitizeMoney($attributes['total']);
            $attributes['tax'] = sanitizeMoney($attributes['tax'] ?? 0);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $this->handleBookingBillUpdate($resource->booking);

        $this->logging->bookingBillCreated($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $this->handleBookingBillUpdate($resource->booking);

        $this->logging->bookingBillUpdated($resource->booking, $resource);

        return $resource;
    }

    /**
     * @inherited
     */
    public function onAfterDelete(Model $resource): Model
    {
        $this->handleBookingBillUpdate($resource->booking);

        $this->logging->bookingBillDeleted($resource->booking, $resource);

        return $resource;
    }

    /**
     * [handleBookingBillUpdate description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    protected function handleBookingBillUpdate(Booking $booking)
    {
        app(BookingRepository::class)->update($booking, [
            //'subtotal' => $booking->getBillsTotal(), // we dont need to update the subtotal anymore.
            'total' => ($booking->subtotal + $booking->tax) - $booking->discount
        ]);
    }

    /**
     * [getBookingNextInstallment description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  int                [return description]
     */
    public function getBookingNextInstallment(Booking $booking): int
    {
        $bookingBill = $booking->bookingBills()->orderByDesc('installment')->first();

        if (empty($bookingBill)) {
            return 1;
        }

        $lastInstallment = $bookingBill->installment ?? 0;
        $nextInstallment = $lastInstallment + 1;
        
        return $nextInstallment;
    }

    /**
     * [getBookingNextCt description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  int                [return description]
     */
    public function getBookingNextCt(Booking $booking): int
    {
        return $this->getBookingNextInstallment($booking);
    }

    /**
     * [restore description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function restore(BookingBill $bookingBill)
    {
        if (!$bookingBill->canBeRestored()) {
            return;
        }

        $bookingBill->status = ProcessStatus::PENDING;
        $bookingBill->canceled_at = null;
        $bookingBill->save();

        $this->logging->bookingBillRestored($bookingBill->booking, $bookingBill);
    }

    /**
     * [setAsPaid description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function setAsPaid(BookingBill $bookingBill): BookingBill
    {
        $bookingBill->status = ProcessStatus::PAID;
        $bookingBill->paid_at = Carbon::now();
        $bookingBill->save();

        $this->logging->bookingBillPaid($bookingBill->booking, $bookingBill);

        return $bookingBill;
    }

    /**
     * [setAsCanceled description]
     *
     * @param   BookingBill  $bookingBill  [$bookingBill description]
     *
     * @return  [type]                     [return description]
     */
    public function setAsCanceled(BookingBill $bookingBill): BookingBill
    {
        $bookingBill->status = ProcessStatus::CANCELED;
        $bookingBill->canceled_at = Carbon::now();
        $bookingBill->save();

        $this->logging->bookingBillCanceled($bookingBill->booking, $bookingBill);

        return $bookingBill;
    }

    /**
     * [generate description]
     *
     * @param   Booking  $booking  [$booking description]
     *
     * @return  [type]             [return description]
     */
    public function generate(Booking $booking)
    {
        $attributes = [
            'payment_method_id' => PaymentMethod::first()->id,
            'total' => str_replace('.', ',', $booking->getUnbilledTotal()),
            'tax' => 0,
            'status' => ProcessStatus::PENDING,
            'installment' => $this->getBookingNextInstallment($booking),
            'ct' => $this->getBookingNextInstallment($booking),
            'processor' => Processor::OFFLINE,
            'quotations' => $booking->quotations,
        ];

        $bookingBill = $this->setBooking($booking)->store($attributes);

        return $bookingBill;
    }

    /**
     * Filter a resource
     *
     * @param   array  $id
     *
     * @return  Model|Collection|null
     */
    public function filter(array $params, ?int $limit = null): Collection
    {
        $query = $this->model->query();

        if (isset($params['expires_at'])) {
            if (!empty($params['expires_at'][0]) && empty($params['expires_at'][1])) {
                $query = $query->where('expires_at', $params['expires_at'][0]);
            }

            if (!empty($params['expires_at'][0]) && !empty($params['expires_at'][1])) {
                $query = $query->whereBetween('expires_at', [
                    $params['expires_at'][0],
                    $params['expires_at'][1],
                ]);
            }

            if (empty($params['expires_at'][0]) && !empty($params['expires_at'][1])) {
                $query = $query->whereDate('expires_at', $params['expires_at'][1]);
            }
        }

        if (isset($params['start_date'])) {
            $params['start_date']   = convertDate($params['start_date']);
            $query = $query->whereDate('booking_bills.expires_at', ">=", $params['start_date']);
        }

        if (isset($params['end_date'])) {
            $params['end_date']     = convertDate($params['end_date']);
            $query = $query->whereDate('booking_bills.expires_at', "<=", $params['end_date']);
        }

        if (isset($params['booking_id'])) {
            $query = $query->where('booking_bills.booking_id', $params['booking_id']);
        }

        $query = $query->join('bookings', 'booking_bills.booking_id', '=', 'bookings.id');
        if (isset($params['booking_status'])) {
            $query = $query
                        ->where('bookings.status', $params['booking_status'])
                        ;
        }

        if (isset($params['bookingBill_status'])) {
            $query = $query->where('booking_bills.status', $params['bookingBill_status']);
        }

        if (isset($params['booking_payment_status'])) {
            $query = $query->where('bookings.payment_status', $params['booking_payment_status']);
        }

        if (isset($params['payment_method_id'])) {
            $query = $query->where('booking_bills.payment_method_id', $params['payment_method_id']);
        }

        if (isset($params['processor'])) {
            $query = $query->where('booking_bills.processor', $params['processor']);
        }

        if (!empty($limit)) {
            $query = $query->limit($limit);
        }
        $query      = $query->select("booking_bills.*");
        $resources = $query->get();

        return $resources;
    }

    /**
     * [getChargeableBookingBills description]
     *
     * @return  [type]  [return description]
     */
    public function getChargeableBookingBills()
    {
        $bookingBills = $this->model
            ->where('status', 'pending')
            ->whereDate('expires_at', '<=', Carbon::today()->format('Y-m-d'))
            ->get();

        return $bookingBills;
    }

    /**
     * [getRefundsBookingBills description]
     *
     * @return  [type]  [return description]
     */
    public function getRefundsBookingBills(BookingBill $bookingBill, string $processor = null)
    {
        $totalRefunded  = 0;
        $transactions   = $this->transactionRepository
            ->getModel()
            ->where('booking_bill_id', $bookingBill->id)
            ->whereIn('operation', [Transactions::OPERATION_REFUND, Transactions::OPERATION_CANCEL]);
        if($processor != null){
            $transactions->where('gateway', $processor);
        }
        $transactions = $transactions->get();
        if($transactions){
            foreach($transactions as $t){
                $totalRefunded += $t->amount;
            }
        }
        return $totalRefunded;
    }
}
