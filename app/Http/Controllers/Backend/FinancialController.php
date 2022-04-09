<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\BookingBill;
use App\Repositories\BookingBillRepository;
use App\Repositories\PaymentMethodRepository;
use Carbon\Carbon;
use Defuse\Crypto\Crypto;
use Exception;
use Illuminate\Http\Request;

class FinancialController extends Controller
{
    /**
     * @var BookingBillRepository
     */
    protected $bookingBillRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    public function __construct(
        BookingBillRepository $bookingBillRepository,
        PaymentMethodRepository $paymentMethodRepository)
    {
        $this->bookingBillRepository = $bookingBillRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * [bills description]
     *
     * @return  [type]  [return description]
     */
    public function bills(Request $request)
    {
        $this->authorize('manage', BookingBill::class);

        try {
            $params = $request->toArray();

            if (empty($params)) {
                return redirect()->route('backend.financial.bills', [
                    'expires_at' => [
                        Carbon::now()->startOfMonth()->format('d/m/Y'),
                        Carbon::now()->endOfMonth()->format('d/m/Y'),
                    ],
                ]);
            }

            $paymentMethods['national'] = $this->paymentMethodRepository->getNationals();
            $paymentMethods['international'] = $this->paymentMethodRepository->getInternationals();

            if (isset($params['expires_at'][0])) {
                $params['expires_at'][0] = convertDate($params['expires_at'][0]);
            }

            if (isset($params['expires_at'][1])) {
                $params['expires_at'][1] = convertDate($params['expires_at'][1]);
            }

            $bookingBills = $this->bookingBillRepository->filter($params);
            $totals['paid'] = $bookingBills->where('status', 'paid')->sum('total');
            $totals['pending'] = $bookingBills->where('status', 'pending')->sum('total');

            if (isset($params['expires_at'][0])) {
                $params['expires_at'][0] = convertDate($params['expires_at'][0], true);
            }

            if (isset($params['expires_at'][1])) {
                $params['expires_at'][1] = convertDate($params['expires_at'][1], true);
            }


            return view('backend.financial.bills')
                ->with('bookingBills', $bookingBills)
                ->with('paymentMethods', $paymentMethods)
                ->with('totals', $totals)
                ->with('params', $params);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form to decrypt offline payments
     *
     * @return \Illuminate\Http\Response
     */
    public function decryptor(Request $request)
    {
        $this->authorize('manage', BookingBill::class);

        try {

            return view('backend.financial.decryptor');

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Decrypt the code and show data
     *
     * @return \Illuminate\Http\Response
     */
    public function decrypt(Request $request)
    {
        $this->authorize('manage', BookingBill::class);

        try {
            $_data          = $request->all();
            $encrypted      = isset($_data['code'])?$_data['code']:null;
            $password       = sprintf("%010s", isset($_data['booking_id'])?$_data['booking_id']:"");
            $decrypted      = Crypto::decryptWithPassword($encrypted, $password);
            return view('backend.financial.decrypted')
            ->with('booking_id', $password)
            ->with(json_decode($decrypted, true));

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            dd($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }
}
