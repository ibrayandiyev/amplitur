<?php

return [

// Geral

'geral' => [
    'hi' => 'Hi,',
    'att' => 'Best regards,',
    'direitos' => " AMP Travels Ltd. © - All Copyrights Reserved {{ date('Y') }}",
],

//Client
'client' => [

    'geral' => [
        'created' => 'Your Account on the AMP Travels website',
        'validated' => 'Validated Account',
    ],

    //Client - Booking

    'booking' => [

            'confirm_purchase' => 'Purchase Confirmation',
            'introduce_client' => "Thank you for using the AMP Travels 's system!",
            'confirm_thanks' => 'Check the details of your service(s) below',
            'booking_code' => 'Booking Code',
            'passenger' => 'Passenger',
            'important_notes' => 'Important Notes',
            'track' => 'Purchase Tracking',
            'keep_track' => 'To track your purchase, print documents and vouchers, access your order details in the section',
            'my_account' => 'My Account',
            'purchase_cancel' => 'The Purchase may be CANCELED in the following cases:',
            'msg_cancel_1' => 'Unpaid installments on their due dates;',
            'msg_cancel_2' => 'No credit card debit authorization;',
            'msg_cancel_3' => 'Simultaneous purchases with the same service features and additional for the same people;',
            'head_docs' => 'Documentation to be sent',
            'label_contract' => 'Travel Contract',
            'msg_doc_1' => 'The above documents must be sent scanned to the e-mail <a href="mailto:reservas@amplitur.com">reservas@amplitur.com</a> as soon as possible for the release of the ) Service provision voucher(s)',
            'head_voucher'=> 'Services Vouchers',
            'msg_voucher_1' => "THE BOARDING VOUCHER IS MANDATORY TO PRESENT THE BOARDING VOUCHER together with the original identification document with photo of the passengers for the Provision of Service.",
            'msg_voucher_2' => 'The Voucher for boarding: will only be released AFTER 7 DAYS before boarding and after receipt of all documentation (Travel Contract and/or Debit Authorization) duly filled in and signed.',
            'more_info' => 'More Information and Questions',
            'msg_info_1' =>"Access the link <a href='{{ route(getRouteByLanguage('frontend.pages.contact')) }}'>Frequently Asked Questions</a>, or via email <a href= 'mailto:booking@amp-travels.com'>booking@amp-travels.com</a>.",

        ],

    //Client - Comunication

    'comun' => [

        'update' => 'Your booking was in progress',
        'update_msg' => 'We inform you that there was a progress in your purchase process',
        'booking_code' => 'BOOKING CODE',
        'msg_click' => 'Click on the link below to view the new information',
        'purchase_acess' => 'Access my Booking',
    ],

    // Client - Registry

    'registry' => [

            'reg_head' => 'Your Account on the AMP Travels website',
            'reg_thanks' => 'Thank you for registering on our site!',
            'reg_attention' => '<strong>Attention:</strong> your account  must be validated in order for you to make purchases on the AMP Travels  website.',
            'valid_link' => 'Click on the link below to carry out this validation:',
            'link_valid' => 'Link Validation',
            'client_msg' => 'Below, the main data filled in:',
            'name' => 'Name',
            'email' => 'Email',
            'login' => 'Login',
            'pass' => 'Password',
            'info_pass' => 'password hidden for security',
            'login_msg' => 'Log in here with your login or email to access your account and check your reservations and registration.',
            'doubt_msg' => 'If you have any questions, please contact us.',
    ],

    //Client - Valid Registry

    'valid_registry' => [
            'head' => 'Your account was successfully validated!',
            'head_msg' => 'Now you can make purchases on the AMP Travels  website',
            'link_msg' => 'Click on the link below to access the site',
            'link_acess' => 'Access the Site',
            'doubt_msg' => 'If you have any questions, please contact us',
            'not_validated' => 'There was an error validating your account, if the error persists, please contact us via E-mail <a href="mailto:booking@amp-travels.com">booking@amp-travels.com</a>',
    ],

    //Client - Recovery Username

    'recov_user' => [
        'head' => 'User Recovery',
        'recov_msg' => 'It was requested via the website to retrieve the login for the account with the e-mail',
        'recov_login' => 'Your login is',
        'end_msg' => 'If you have not requested this password change via the site, please disregard this email.',
    ],

    //Client - Recovery Password

    'recov_pass' => [
        'head' => 'Password Recovery',
        'password_changed' => 'Password Changed',
        'recov_msg' => 'Password recovery for the account was requested through the website',
        'recov_pass' => 'Click on the link below to register a new password for your account. This link will be valid for password change until',
        'change_pass_link' => 'Change Password',
        'end_msg' => 'If you have not requested this change, please disregard this email.',
        'head_confirm' => 'Password Change Confirmation',
        'recov_msg_confirm' => 'We have confirmed the change of your password to the',
        'recov_pass_confirm' => 'In your new access to our site, please use the new registered password.',
    ],

    //Client - Data Change

    'data_change' => [
        'head' => 'Change of Account Data',
        'data_msg' => 'We confirm that changes have been made to your account data.',
        'end_msg' => 'If you have not requested this change, please contact us through our service channels.',
        'click_here' => 'Click Here',
    ],

],
//Backoffice
'backoffice' => [

    //Client
    'client' => [
        'created' => 'New Registered Customer ',
    ],

    //Booking - Backoffice

    'booking' => [

    'confirm_purchase' => 'Sales Notice',
    'booking_code' => 'Booking Code',
    'head_card_decript' => 'Payment - Credit Card',
    'passenger' => 'Passenger',
    'important_notes' => 'Important Notes',
    ],
],

//Provider
'provider' => [

    'created' => 'Provider Account Confirmation',
    'validated' => 'Validated Account',


    // Booking - Provider

    'booking' => [

    'provider_confirm_purchase' => 'Sale Confirmation',
    'confirm_purchase' => 'CONGRATULATIONS YOU SOLD!',
    'introduce_client' => 'Thank you for using the AMP Travels  system!',
    'confirm_thanks' => 'Please be advised that your offer to',
    'has_sold' => 'was sold!',
    'booking_code' => 'BOOKING CODE',
    'keep_track' => 'For more details and to keep track of your sales, insertion of documents and vouchers, access your order details in your administrative area.',
    'my_account' => 'My Account',
    'important_notes' => 'Important Notes',
    'purchase_cancel' => 'Please remember that the SALE may be automatically CANCELED in the following situations:',
    'msg_cancel_1' => 'No payment confirmation by the customer;',
    'msg_cancel_2' => 'If the customer exercises the right to "regret purchase" within 7 (seven) days;',
    'msg_cancel_3' => 'If it is NOT possible to provide the service as advertised, please contact our URGENTLY support via the E-mail <a href="mailto:reservas@amplitur.com">reservas@amplitur.com< /a> , avoiding penalties.',
    'head_voucher'=> 'Services Vouchers',
    'msg_voucher_1' => 'Service vouchers must be loaded into the reservation within a maximum period of 10 (days) before the service is provided, with all the important guidelines so that the customer can use the contracted service.',
    'head_pags' => 'Payments and Penalties',
    'msg_pags_1' => 'In the absence of loading the Voucher(s), within the period described above, the Provider is subject to problems;',
    'msg_pags_2' => 'The transfers of amounts are made within 15 (fifteen) days after the completion of the service;',
    'msg_pags_3' => 'More information about tools and transfers, access the link XXXXX;', //RODRIGO INSERIR LINK
    'more_info' => 'More Information and Questions',
    'msg_info_1' =>"Via support email <a href='mailto:reservas@amplitur.com'>reservas@amplitur.com</a>",
    ],

    'registry' => [

    'reg_head' => 'Your Account on the AMP Travels website',
    'reg_thanks' => 'Thank you for registering on our site!',
    'reg_attention' => '<strong>Attention:</strong> your account needs to be validated so that you can sell your products and services on the AMP Travels  website.',
    'valid_link' => 'Click on the link below to carry out this validation:',
    'link_valid' => 'Validate my Account',
    'link_valid_type' => 'Copy and paste the address below into your browser to validate your account',
    'client_msg' => 'Below, the main data filled in:',
    'name' => 'Name',
    'email' => 'Email',
    'login' => 'Login',
    'pass' => 'Password',
    'info_pass' => 'password hidden for security',
    'login_msg' => 'Log in here with your login or email to access your account and see your offers and sales',
    'doubt_msg' => 'If you have any questions, please contact us.',
    ],

    'company' => [

        'created' => 'Company Registration Confirmation',
        'reg_head' => 'Company Registration',
        'reg_thanks' => 'Your company registration was Successful!',
        'reg_attention' => '<strong> Attention: </strong> your account will be analyzed and once the information is validated, you will be able to market your offers on our portal.',
        'client_msg' => 'The following are the main data of the registered company:',
        'name' => 'Corporate Name',
        'name_fantasy' => 'Fantasy Name',
        'address' => 'Address',
        'clique_aqui' => 'Click Here',
        'login_msg' => ' to access your account and view your offers and sales',
        'doubt_msg' => 'If you have any questions, please contact us.',
        ],

        //Provider - Valid Registry

        'valid_registry' => [
            'head' => 'Your Account was Successfully Validated!',
            'head_msg' => 'Now you can register your company, packages and offers for sale on our platform.',
            'link_msg' => 'Click on the link below to access the Login area and your Dashboard',
            'link_acess' => 'Login Site',
            'doubt_msg' => 'If you have any questions, please contact us. Good sales!',
    ],

        //Provider - Recovery Password

    'recov_pass' => [
        'head' => 'Password Recovery',
        'recov_msg' => 'Password recovery for the account was requested through the website',
        'recov_pass' => 'Click on the link below to register a new password for your account. This link will be valid for password change until',
        'minutes' => 'minutes',
        'change_pass_link' => 'Change Password',
        'link_valid_type' => 'Copy and paste the address below into your browser to change your password',
        'end_msg' => 'If you have not requested this change, please disregard this email.',
    ],

        //Client - Comunication

        'comun' => [

            'update' => 'Your Purchase was in progress',
            'update_msg' => 'We inform you that there was a progress in your purchase process',
            'booking_code' => 'BOOKING CODE',
            'msg_click' => 'Click on the link below to view the new information',
            'purchase_acess' => 'Access my Purchase',
        ],
],
];
