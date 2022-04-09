<?php

return [
    'booking-created' => 'Booking #:id created',
    'booking-updated' => 'Booking #:id updated',
    'booking-canceled' => 'Booking #:id canceled',
    'booking-deleted' => 'Booking #:id deleted',

    'booking-voucher-created' => 'Booking #:booking_id voucher #:voucher_id created',
    'booking-voucher-updated' => 'Booking #:booking_id voucher #:voucher_id updated',
    'booking-voucher-deleted' => 'Booking #:booking_id voucher #:voucher_id deleted',

    'booking-voucher-file-created' => 'Booking #:booking_id voucher file \':voucher_file_title\' created',
    'booking-voucher-file-deleted' => 'Booking #:booking_id voucher file \':voucher_file_title\' deleted',

    'booking-bill-created' => 'Booking #:booking_id bill #:bill_id created',
    'booking-bill-updated' => 'Booking #:booking_id bill #:bill_id updated',
    'booking-bill-deleted' => 'Booking #:booking_id bill #:bill_id deleted',
    'booking-bill-paid' => 'Booking #:booking_id bill #:bill_id paid',
    'booking-bill-canceled' => 'Booking #:booking_id bill #:bill_id canceled',
    'booking-bill-restored' => 'Booking #:booking_id bill #:bill_id restored',

    'booking-passenger-created' => 'Booking #:booking_id passenger #:passenger_id \':name\' created',
    'booking-passenger-updated' => 'Booking #:booking_id passenger #:passenger_id \':name\' updated',
    'booking-passenger-deleted' => 'Booking #:booking_id passenger #:passenger_id \':name\' deleted',

    'booking-passenger-additional-created' => 'Booking #:booking_id passenger #:passenger_id \':name\' additional \':additional\' created',
    'booking-passenger-additional-updated' => 'Booking #:booking_id passenger #:passenger_id \':name\' additional \':additional\' updated',
    'booking-passenger-additional-deleted' => 'Booking #:booking_id passenger #:passenger_id \':name\' additional \':additional\' deleted',

    // Itau Shopline
    'booking-bill-shopline'         => 'Reserva #:booking_id',

//Log - Bookings

'booking' => [

    'purchase' => 'Purchase registered in the system',
    'cancel' => 'Purchase Canceled',
    'refund_itens' => 'Refund Items from Reserve to stock <br> :items',
    'change_service' => 'Main service changed from :mainservicebefore to :mainserviceafter',
    'add_item' => 'Added Service Additional - :additional to passenger :name',
    'update_item' => 'Updated Service Additional - :additional to passenger :name',
    'remove_item' => 'Removed Additional Service - :additional to passenger :name',
    'pax_created' => 'New passenger added to Booking :name',
    'pax_removed' => 'Passenger excluded from Booking :name',
    'pax_updated' => 'Passenger / data - Updated :data',
],

'payment' =>[
    'shopline_boleto_paid' => 'Bank slip installment :parcela - Payment Received',
    'shopline_transfer_paid' => 'Bank transfer installment :parcela - Payment Received',
    'cc_total_paid' => 'Credit Card Payment - final :final - in the amount of :amount - Successfully Authorized - Authorization Code :authorization',
    'cc_total_installment_paid' => 'Payment of installment :parcel in the amount of :amount with Credit Card - final :final - Successfully Authorized - Authorization Code :authorization',
    'cc_dayexchange' => ':brl | :usd | :eur | :gbp',
    'change_payment' => 'Payment method changed to :paymentmethod',
    'cc_refund_total' => 'Total Cancellation in the amount of :value - Successfully effected on the final card :final - Authorization Code :authorizacao',
    'cc_refund_partial' => 'Partial Cancellation in the amount of :value - Successfully carried out on the final card :final - Authorization Code :authorization',
    'add_installment' => 'Added Payment Installment in the amount of :value',
    'remove_installment' => 'Removed Payment installment in the amount of :value',
],

'documents' =>[
    'contract_received' => 'Contract Signed Virtually - :ip',
],

'vouchers' => [
    'created' => 'Voucher Created by :provider :company',
    'deleted' => 'Voucher Deleted by :provider :company',
    'updated' => 'Voucher Updated by :provider :company',
    'file_created' => 'Voucher \':voucher_file_title\' Created by :provider :company',
    'file_deleted' => 'Voucher \':voucher_file_title\' Deleted by :provider :company',
    'file_updated' => 'Voucher \':voucher_file_title\' Updated by :provider :company',

],

'bus' => [
    'booked_seat' => 'Seat <strong>:seat</strong> Booked Successfully!',
    'unbooked_seat' => 'Armchair <strong>:seat</strong> Successfully Unbooked!',
],

//LOG - Clients and Providers

'client' =>[

'register' => 'Registered Customer',
'agreement' => 'Terms of Use and Privacy Policy - ACCEPT - IP :ip',
'validation' => 'Validated Registration - :data',
'uptade' => 'Updated registration, new data :data',
're_validation' => 'Revalidated Registration - Date :data - IP :ip'
],

'provider' =>[

    'register' => 'Provider Registration - Done',
    'agreement' => 'Terms of Use and Privacy Policy - ACCEPT - IP :ip',
    'validation' => 'Validated Registration - :data',
    'uptade' => 'Updated registration, new data :data',
    'company_register' => 'Company :Company Registered by Provider',
    'uptade_company' => 'Updated Company Registration, new data :data',
    ],
];
