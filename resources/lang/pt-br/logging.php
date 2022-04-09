<?php

return [
    'booking-created' => 'Reserva criada',
    'booking-updated' => 'Reserva atualizada',
    'booking-canceled' => 'Reserva cancelada',
    'booking-deleted' => 'Reserva apagada',

    'booking-voucher-created' => 'Voucher #:voucher_id criado',
    'booking-voucher-updated' => 'Voucher #:voucher_id atualizado',
    'booking-voucher-deleted' => 'Voucher #:voucher_id apagadao',

    'booking-voucher-file-created' => 'Voucher file \':voucher_file_title\' criado',
    'booking-voucher-file-deleted' => 'Voucher file \':voucher_file_title\' apagado',

    'booking-bill-created' => 'Reserva recebível #:bill_id criada',
    'booking-bill-updated' => 'Reserva recebível #:bill_id atualizada',
    'booking-bill-deleted' => 'Reserva recebível #:bill_id apagada',
    'booking-bill-paid' => 'Reserva recebível #:bill_id Pago',
    'booking-bill-canceled' => 'Reserva recebível #:bill_id cancelado',
    'booking-bill-restored' => 'Reserva recebível #:bill_id restaurado',

    'booking-passenger-created' => 'Reserva passageiro(a) #:passenger_id \':name\' criada(o)',
    'booking-passenger-updated' => 'Reserva passageiro(a) #:passenger_id \':name\' atualizada(o)',
    'booking-passenger-deleted' => 'Reserva passageiro(a) #:passenger_id \':name\' apagada(o)',

    'booking-passenger-additional-created' => 'Reserva passageiro(a) #:passenger_id \':name\' adicional \':additional\' criado',
    'booking-passenger-additional-updated' => 'Reserva passageiro(a) #:passenger_id \':name\' adicional \':additional\' atualizado',
    'booking-passenger-additional-deleted' => 'Reserva passageiro(a) #:passenger_id \':name\' adicional \':additional\' apagado',

    // Itau Shopline
    'booking-bill-shopline'         => 'Reserva #:booking_id',

//Log - Bookings

'booking' => [

    'purchase' => 'Compra Registrada no sistema',
    'cancel' => 'Compra Cancelada',
    //'refund_itens' => 'Estorno de Itens da Reserva para estoque <br> :itens',
    //'change_service' => 'Serviço Principal alterado de :mainservicebefore para :mainserviceafter',
    'add_item' => 'Adicionado Serviço Adicional - :additional para passageiro :name',
    'update_item' => 'Atualizado Serviço Adicional - :additional para passageiro :name',
    'remove_item' => 'Removido Serviço Adicional - :additional para passageiro :name',
    'pax_created' => 'Novo Passageiro inserido na reserva :name',
    'pax_removed' => 'Passageiro Excluido da Reserva :name',
    'pax_updated' => 'Passageiro / dados - Atualizados :dados',
],

'payment' =>[
    //'shopline_boleto_paid' => 'Boleto bancário parcela :parcela - Pagamento Recebido',
    //'shopline_transfer_paid' => 'Trasferência Bancária parcela :parcela - Pagamento Recebido',
    //'cc_total_paid' => 'Pagamento com Cartão de Crédito - final :final - no valor de :valor - Autorizado com Sucesso - Código de Autorização :autorizacao',
    //'cc_total_installment_paid' => 'Pagamento de parcela :parcela no valor de :valor com Cartão de Crédito - final :final - Autorizado com Sucesso - Código de Autorização :autorizacao',
    //'cc_dayexchange' => ':brl | :usd | :eur | :gbp',
    //'change_payment' => 'Forma de pagamento alterada para :paymentmethod',
    //'cc_refund_total' => 'Cancelamento Total no valor de :valor - Efetuado com Sucesso no cartão de final :final - Código de Autorização :autorizacao',
    //'cc_refund_partial' => 'Cancelamento Partial no valor de :valor - Efetuado com Sucesso no cartão de final :final - Código de Autorização :autorizacao',
    //'add_installment' => 'Adicionada Parcela de Pagamento no valor de :valor',
    //'remove_installment' => 'Removida parcela de Pagamento no valor de :valor',
],

'documents' =>[
    'contract_received' => 'Contrato Assinado Virtualmente - :ip',
],

'vouchers' => [
    'created' => 'Voucher Criado por :provider :company',
    'deleted' => 'Voucher Excluido por :provider :company',
    'updated' => 'Voucher Atualizado por :provider :company',
    'file_created' => 'Voucher \':voucher_file_title\' Criado por :provider :company',
    'file_deleted' => 'Voucher \':voucher_file_title\' Excluido por :provider :company',
    'file_updated' => 'Voucher \':voucher_file_title\' Atualizado por :provider :company',

],

'bus' => [
    //'booked_seat' => 'Poltrona <strong>:seat</strong> Reservada com Sucesso!',
    //'unbooked_seat' => 'Poltrona <strong>:seat</strong> Desmarcada com Sucesso!',
],

//LOG - Clients and Providers

'client' =>[

//'register' => 'Cadastro de Cliente Efetuado',
//'agreement' => 'Termo de Uso e Política de Privacidade - ACEITE - IP :ip',
//'validation' => 'Cadastro Validado - :data',
//'uptade' => 'Cadastro Atualidado, novos dados :dados',
//'re_validation' => 'Cadastro Revalidado - Data :data - IP :ip'
],

'provider' =>[

    //'register' => 'Cadastro de Provider - Efetuado',
    //'agreement' => 'Termo de Uso e Política de Privacidade - ACEITE - IP :ip',
    //'validation' => 'Cadastro Validado - :data',
    //'uptade' => 'Cadastro Atualidado, novos dados :dados',
    //'company_register' => 'Empresa :empresa Registrada pelo Provider',
    //'uptade_company' => 'Cadastro Empresa Atualizado, novos dados :dados',
    ],
];
