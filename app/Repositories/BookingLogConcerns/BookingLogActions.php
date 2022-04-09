<?php

namespace App\Repositories\BookingLogConcerns;

use App\Enums\Bookings\BookingLogsOperation;
use App\Models\Booking;
use App\Models\BookingBill;
use App\Models\BookingPassenger;
use App\Models\BookingPassengerAdditional;
use App\Models\BookingVoucher;
use App\Models\BookingVoucherFile;
use App\Models\Client;
use App\Models\Provider;
use App\Models\User;
use App\Repositories\BookingLogRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

trait BookingLogActions
{
    protected function getLoggingRepository(): BookingLogRepository
    {
        return app(BookingLogRepository::class);
    }

    protected function getAuthUser(): ?int
    {
        return !empty(user()) && (user() instanceof User) ? user()->id : null;
    }

    protected function getAuthProvider(): ?int
    {
        return !empty(user()) && (user() instanceof Provider) ? user()->id : null;
    }

    protected function getAuthClient(): ?int
    {
        return !empty(user()) && (user() instanceof Client) ? user()->id : null;
    }

    protected function getType(): string
    {
        return (!empty($this->getAuthUser()) || !empty($this->getAuthProvider()) || !empty($this->getAuthClient())) ? 'manual' : 'system';
    }

    protected function getIpAddress(): ?string
    {
        return ip();
    }

    protected function getLevelMaster(): int
    {
        return 1;
    }

    protected function getLevelMasterClient(): int
    {
        return 2;
    }

    protected function getLevelMasterProvider(): int
    {
        return 4;
    }

    protected function getLevelMasterClientProvider(): int
    {
        return 16;
    }

    protected function getMessage(string $key, array $params): array
    {
        $languages = ['pt-br', 'en', 'es'];
        $message = [];

        foreach ($languages as $language) {
            $message[$language] = __($key, $params, $language);
        }

        return $message;
    }

/** OK */

    public function bookingCreated(Booking $booking): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking.purchase', [
                'id' => $booking->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

/** REVISAR */

    public function bookingUpdated(Booking $booking): void
    {
        if (!$booking->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking-updated', [
                'id' => $booking->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** OK */
    public function bookingDigitalSigned(Booking $booking): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.documents.contract_received', [
                'signed' => $booking->getDigitalSigned(3)
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** OK */
    public function bookingCanceled(Booking $booking): void
    {
        if (!$booking->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'operation' => BookingLogsOperation::BOOKING_LOG_OPERATION_BOOKING_CANCELLATION,
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking.cancel', [
                'id' => $booking->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** DELETAR */
    public function bookingDeleted(Booking $booking): void
    {
        if (!$booking->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking-deleted', [
                'id' => $booking->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** REVISAR E ACRECENTAR - :provider :company */
    public function bookingVoucherCreated(Booking $booking, BookingVoucher $bookingVoucher): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.vouchers.created', [
                'booking_id' => $booking->id,
                'voucher_id' => $bookingVoucher->id,
                'provider' => 'provider',
                'company' => 'company',
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** REVISAR E ACRECENTAR - :provider :company */
    public function bookingVoucherUpdated(Booking $booking, BookingVoucher $bookingVoucher): void
    {
        if (!$bookingVoucher->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.vouchers.updated', [
                'booking_id' => $booking->id,
                'voucher_id' => $bookingVoucher->id,
                'provider' => 'provider',
                'company' => 'company',
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** REVISAR E ACRECENTAR - :provider :company */
    public function bookingVoucherDeleted(Booking $booking, BookingVoucher $bookingVoucher): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.vouchers.deleted', [
                'booking_id' => $booking->id,
                'voucher_id' => $bookingVoucher->id,
                'provider' => 'provider',
                'company' => 'company',
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** REVISAR E ACRECENTAR - :provider :company */
    public function bookingVoucherFileUploaded(Booking $booking, BookingVoucherFile $bookingVoucherFile): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.vouchers.file_created', [
                'booking_id' => $booking->id,
                'voucher_file_title' => $bookingVoucherFile->title,
                'provider' => 'provider',
                'company' => 'company',
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** REVISAR E ACRECENTAR - :provider :company */
    public function bookingVoucherFileDeleted(Booking $booking, BookingVoucherFile $bookingVoucherFile): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking-voucher-file-deleted', [
                'booking_id' => $booking->id,
                'voucher_file_title' => $bookingVoucherFile->title,
                'provider' => 'provider',
                'company' => 'company',
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /**  DESNECESSÁRIO - Somente registrar no relatório de recebíveis */
    public function bookingBillCreated(Booking $booking, BookingBill $bookingBill): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking-bill-created', [
                'booking_id' => $booking->id,
                'bill_id' => $bookingBill->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** DESNECESSÁRIO - Somente registrar no relatório de recebíveis */
    public function bookingBillUpdated(Booking $booking, BookingBill $bookingBill): void
    {
        if (!$bookingBill->getChanges() || sizeof($bookingBill->getChanges()) == 2 && (array_key_exists('tax', $bookingBill->getChanges()) && array_key_exists('updated_at', $bookingBill->getChanges()))) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking-bill-updated', [
                'booking_id' => $booking->id,
                'bill_id' => $bookingBill->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

   /** DESNECESSÁRIO - Somente registrar no relatório de recebíveis*/
    public function bookingBillDeleted(Booking $booking, BookingBill $bookingBill): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking-bill-deleted', [
                'booking_id' => $booking->id,
                'bill_id' => $bookingBill->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

   /** Esse e ralativo a Cartão ou Boleto ?? */
    public function bookingBillPaid(Booking $booking, BookingBill $bookingBill): void
    {
        if (!$bookingBill->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking-bill-paid', [
                'booking_id' => $booking->id,
                'bill_id' => $bookingBill->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** Esse e ralativo a Cartão ou Boleto ?? */
    public function bookingBillCanceled(Booking $booking, BookingBill $bookingBill): void
    {
        if (!$bookingBill->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterClientProvider(),
            'message' => $this->getMessage('logging.booking-bill-canceled', [
                'booking_id' => $booking->id,
                'bill_id' => $bookingBill->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    //** Esse e ralativo a Criaçao ou o Pagamento do Boleto ?? */
    public function bookingBillShopline(Booking $booking, BookingBill $bookingBill, $data=null): void
    {
        $this->booking      = $booking;
        $this->bookingBill  = $bookingBill;
        $message = $this->getMessage('logging.booking-bill-shopline', [
            'booking_id'    => $this->booking->id,
            'bill_id'       => $this->bookingBill->id,
            'data'          => $data
        ]);
        $this->doLogging($message);

    }

    /** DESNECESSÁRIO - Somente registrar no relatório de recebíveis */
    public function bookingBillRestored(Booking $booking, BookingBill $bookingBill): void
    {
        if (!$bookingBill->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking-bill-restored', [
                'booking_id' => $booking->id,
                'bill_id' => $bookingBill->id
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /**  REVISAR - Somente quando for inserido via ADM*/
    public function passengerCreated(Booking $booking, BookingPassenger $bookingPassenger): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking.pax_created', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassenger->id,
                'name' => mb_strtoupper($bookingPassenger->name)
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    /** REVISAR - Somente quando for alterado via ADM e mostrar os dados que foram alterados*/
    public function passengerUpdated(Booking $booking, BookingPassenger $bookingPassenger): void
    {

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking.pax_updated', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassenger->id,
                'name' => mb_strtoupper($bookingPassenger->name),
                'dados' => 'dados'
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

     /** ok */
     public function passengerDeleted(Booking $booking, BookingPassenger $bookingPassenger): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking.pax_removed', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassenger->id,
                'name' => mb_strtoupper($bookingPassenger->name)
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

     /** ok */
     public function passengerAdditionalCreated(Booking $booking, BookingPassengerAdditional $bookingPassengerAdditional): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking.add_item', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassengerAdditional->bookingPassenger->id,
                'name' => $bookingPassengerAdditional->bookingPassenger->name,
                'additional' => mb_strtoupper($bookingPassengerAdditional->additional->softName)
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

     /** ok */
     public function passengerAdditionalUpdated(Booking $booking, BookingPassengerAdditional $bookingPassengerAdditional): void
    {
        if (!$bookingPassengerAdditional->getChanges()) {
            return;
        }

        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking.update_item', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassengerAdditional->bookingPassenger->id,
                'name' => $bookingPassengerAdditional->bookingPassenger->name,
                'additional' => mb_strtoupper($bookingPassengerAdditional->additional->softName)
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

     /** ok */
    public function passengerAdditionalDeleted(Booking $booking, BookingPassengerAdditional $bookingPassengerAdditional): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'message' => $this->getMessage('logging.booking.remove_item', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassengerAdditional->bookingPassenger->id,
                'name' => $bookingPassengerAdditional->bookingPassenger->name,
                'additional' => mb_strtoupper($bookingPassengerAdditional->additional->softName)
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

/** revisar essa parte abaixo para ficar dentro de uma unica caixa */

    /**
     * Used for Report Refund stock
     * @param Booking $booking
     * @param BookingPassengerAdditional $bookingPassengerAdditional
     * @return void
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function passengerAdditionalPutStock(Booking $booking, BookingPassengerAdditional $bookingPassengerAdditional, int $quantity): void
    {
        $this->getLoggingRepository()->store([
            'target_client_id' => $booking->client_id,
            'target_booking_id' => $booking->id,
            'user_id' => $this->getAuthUser(),
            'provider_id' => $this->getAuthProvider(),
            'type' => $this->getType(),
            'level' => $this->getLevelMasterProvider(),
            'operation' => BookingLogsOperation::BOOKING_LOG_OPERATION_REFUND_STOCK,
            'message' => $this->getMessage('logging.booking-passenger-additional-deleted', [
                'booking_id' => $booking->id,
                'passenger_id' => $bookingPassengerAdditional->bookingPassenger->id,
                'name' => $bookingPassengerAdditional->bookingPassenger->name,
                'additional' => mb_strtoupper($bookingPassengerAdditional->additional->softName),
                'quantity' => $quantity
            ]),
            'ip' => $this->getIpAddress(),
        ]);
    }

    public function doLogging($message=null){

        $this->getLoggingRepository()->store([
            'target_client_id'  => $this->booking->client_id,
            'target_booking_id' => $this->booking->id,
            'user_id'           => $this->getAuthUser(),
            'provider_id'       => $this->getAuthProvider(),
            'type'              => $this->getType(),
            'level'             => $this->getLevelMasterClientProvider(),
            'message'           => $message,
            'ip'                => $this->getIpAddress(),
        ]);
    }

    public function bookingPaid(Booking $booking, $data=null): void
    {
        $this->booking      = $booking;
        $message = $this->getMessage('logging.booking-paid', [
            'booking_id'    => $this->booking->id,
            'data'          => $data
        ]);
        $this->doLogging($message);

    }
}
