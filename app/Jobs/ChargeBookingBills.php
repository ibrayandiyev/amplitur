<?php

namespace App\Jobs;

use App\Repositories\BookingBillRepository;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ChargeBookingBills implements ShouldQueue
{
    use Dispatchable,
        SerializesModels;

    /**
     * @var BookingBillRepository
     */
    protected $bookingBillRepository;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BookingBillRepository $bookingBillRepository)
    {
        $this->bookingBillRepository = $bookingBillRepository;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $bookingBills = $this->bookingBillRepository->getChargeableBookingBills();

            foreach ($bookingBills as $bookingBill) {
                DB::beginTransaction();

                if ($bookingBill->paymentMethod->isCredit()) {
                    $clientPaymentDataset = $bookingBill->getClientPaymentDataset();
                    $clientPaymentDataset = json_decode($clientPaymentDataset->toArray()['payload'], true);

                    if (!$bookingBill->booking->isForeigner() && $bookingBill->paymentMethod->isNational()) {
                        app(BookingBillRepository::class)->payBookingCreditCardInstallments($bookingBill->booking, $clientPaymentDataset);
                    } elseif ($bookingBill->booking->isForeigner() || $bookingBill->paymentMethod->isInternational()) {
                        app(BookingBillRepository::class)->payBookingCreditCardRecurrent($bookingBill->booking, $clientPaymentDataset);
                    }
                }

                DB::commit();
            }
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            DB::rollBack();
            throw $ex;
        }
    }
}
