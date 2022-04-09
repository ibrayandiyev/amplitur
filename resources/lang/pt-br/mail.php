<?php

return [

// Geral

'geral' => [
    'hi' => 'Olá,',
    'att' => 'Atenciosamente,',
    'direitos' => " AMP Travels Ltd. ® - Todos os direitos reservados {{ date('Y') }}",
],

//Client
'client' => [

    'geral' => [
        'created' => 'Seu Cadastro no site da AMP Travels ',
        'validated' => 'Cadastro Validado',
    ],

    //Client - Booking

        'booking' => [

                'confirm_purchase' => 'Confirmação de Compra',
                'introduce_client' => 'Obrigado por utilizar o sistema da AMP Travels !!',
                'confirm_thanks' => 'Confira abaixo os detalhes de seu(s) serviço(s)',
                'booking_code' => 'Localizador',
                'passenger' => 'Passageiro',
                'important_notes' => 'Observações Importantes',
                'track' => 'Acompanhamento da Compra',
                'keep_track' => 'Para fazer o acompanhamento de sua compra, impressão de documentos e vouchers, acesse os detalhes do seu pedido na seção',
                'my_account' => 'Minha Conta',
                'purchase_cancel' => 'A Compra poderá ser CANCELADA nos seguintes casos:',
                'msg_cancel_1' => 'Não pagamento das parcelas em suas datas de vencimento;',
                'msg_cancel_2' => 'Não autorização de débito no cartão de crédito;',
                'msg_cancel_3' => 'Compras simultâneas com as mesmas características de serviços e adicionais para as mesmas pessoas;',
                'head_docs' => 'Documentação a ser enviada',
                'label_contract' => 'Contrato de Viagens',
                'msg_doc_1' => 'Os documentos acima deverão ser enviados digitalizados para o e-mail <a href="mailto:reservas@amplitur.com">reservas@amplitur.com</a> o quanto antes para a liberação do(s) Voucher(s) de prestação de serviço',
                'head_voucher'=> 'Vouchers de Embarque & Serviços',
                'msg_voucher_1' => "É OBRIGATÓRIO A APRESENTAÇÃO do VOUCHER de embarque junto com documento original de identificação com foto dos passageiros para a Prestação de Serviço.",
                'msg_voucher_2' => 'O Voucher para embarque: somente será liberado A PARTIR DE 7 DIAS antes do embarque e após o recebimento de toda documentação (Contrato de Viagem e/ou Autorização de Débito) devidamente preenchidas e assinadas.',
                'more_info' => 'Mais Informações e Dúvidas',
                'msg_info_1' =>"Acesse o link <a href='{{ route(getRouteByLanguage('frontend.pages.contact')) }}'>Perguntas Frequentes</a>, ou através do e-mail <a href='mailto:booking@amp-travels.com'>booking@amp-travels.com</a>.",

            ],

    //Client - Comunication

        'comun' => [

            'update' => 'Houve andamento em sua reserva',
            'update_msg' => 'Informamos que houve um andamento em seu processo de compra',
            'booking_code' => 'Localizador',
            'msg_click' => 'Clique no link abaixo para visuazalizar o Localizador',
            'purchase_acess' => 'Acessar a Compra',
        ],

    // Client - Registry

    'registry' => [

            'reg_head' => 'Seu Cadastro no site da AMP Travels ',
            'reg_thanks' => 'Obrigado por se cadastrar no nosso site!',
            'reg_attention' => '<strong>Atenção:</strong> o seu cadastro precisa ser validado para que você possa efetuar compras no site da AMP Travels .',
            'valid_link' => 'Clique no link abaixo para efetuar esta validação:',
            'link_valid' => 'Link de Validação',
            'client_msg' => 'Abaixo, os principais dados preenchidos:',
            'name' => 'Nome',
            'email' => 'E-mail',
            'login' => 'Login',
            'pass' => 'Senha',
            'info_pass' => 'senha ocultada por segurança',
            'login_msg' => 'Faça o login aqui com o seu login ou e-mail para acessar a sua conta e consultar as suas reservas e o seu cadastro.',
            'doubt_msg' => 'Caso haja qualquer dúvida, entre em contato.',
    ],

    //Client - Valid Registry

    'valid_registry' => [
            'head' => 'Seu Cadastro foi validado com sucesso!',
            'head_msg' => 'Agora você já pode efetuar compras no site da AMP Travels ',
            'link_msg' => 'Clique no link abaixo para acessar o site',
            'link_acess' => 'Acessar o Site',
            'doubt_msg' => 'Caso haja qualquer dúvida, entre em contato.',
            'not_validated' => 'Houve um erro na validação de seu cadastro, caso o erro persista, favor entrar em contato através do E-mail <a href="mailto:booking@amp-travels.com">booking@amp-travels.com</a>',
    ],

    //Client - Recovery Username

    'recov_user' => [
        'head' => 'Recuperação de Usuário',
        'recov_msg' => 'Foi solicitado através do site a recuperação de login para a conta com o e-mail',
        'recov_login' => 'O seu Login é',
        'end_msg' => 'Caso não tenha solicitado esta alteração de senha pelo site, favor desconsiderar este e-mail.',
    ],

    //Client - Recovery Password

    'recov_pass' => [
        'head' => 'Recuperação de Senha',
        'password_changed' => 'Alteração de Senha',
        'recov_msg' => 'Foi solicitado através do site a recuperação de senha para a conta',
        'recov_pass' => 'Clique no link abaixo para cadastrar uma nova senha para a sua conta. Este link será válido para alteração de senha até',
        'change_pass_link' => 'Alterar a Senha',
        'end_msg' => 'Caso não tenha solicitado esta alteração, favor desconsiderar este e-mail.',
        'head_confirm' => 'Confirmação de Alteração de Senha',
        'recov_msg_confirm' => 'Confirmamos a alteração de sua senha para o',
        'recov_pass_confirm' => 'Em seu novo acesso ao nosso site, favor utilizar a nova senha cadastrada.',
    ],

    //Client - Data Change

    'data_change' => [
        'head' => 'Alteraçao de Dados Cadastrais',
        'data_msg' => 'Confirmamos que foram feitas alterações em seus dados cadastrais.',
        'end_msg' => 'Caso não tenha solicitado esta alteração, favor entrar em contato pelos nossos canais de atendimento.',
        'click_here' => 'Clique Aqui',
    ],

],
//Backoffice
'backoffice' => [

    //Client
    'client' => [
        'created' => 'Novo Cliente Cadastrado ',
    ],

    //Booking - Backoffice

    'booking' => [

    'confirm_purchase' => 'Aviso de Venda',
    'booking_code' => 'Localizador',
    'head_card_decript' => 'Pagamento - Cartão de Crédito',
    'passenger' => 'Passageiro',
    'important_notes' => 'Observações Importantes',
    ],
],

//Provider
'provider' => [

    'created' => 'Confirmação de Cadastro de Provider',
    'validated' => 'Cadastro Validado',

    // Booking - Provider

    'booking' => [

    'provider_confirm_purchase' => 'Confirmação de Venda',
    'confirm_purchase' => 'PARABÉNS, VOCÊ VENDEU! ',
    'introduce_client' => 'Obrigado por utilizar o sistema da AMP Travels !!',
    'confirm_thanks' => 'Informamos que sua oferta para',
    'has_sold' => 'foi vendida!',
    'booking_code' => 'Localizador',
    'keep_track' => 'Para mais detalhes e fazer o acompanhamento de suas vendas, inserção de documentos e vouchers, acesse os detalhes do seu pedido em sua área administrativa.',
    'my_account' => 'Minha Conta',
    'important_notes' => 'Observações Importantes',
    'purchase_cancel' => 'Lembramos que a VENDA poderá ser automaticamente CANCELADA nas seguintes situações:',
    'msg_cancel_1' => 'Não confirmação de pagamento por parte do cliente;',
    'msg_cancel_2' => 'Caso o cliente exerça o direito de "arrependimento de compra" no prazo de 7 (sete) dias;',
    'msg_cancel_3' => 'Caso NÃO seja possível a prestação do serviço como anunciado, favor entrar em contato com URGÊNCIA nosso suporte através do E-mail <a href="mailto:reservas@amplitur.com">reservas@amplitur.com</a> , evitando penalidades.',
    'head_voucher'=> 'Vouchers de Embarque & Serviços',
    'msg_voucher_1' => 'Os voucher de serviços, devem ser carregados na reserva no prazo máximo de 10 (dias) antes da prestação de serviço, com todas as orientações importantes para que o cliente possa usufrir do serviço contratado.',
    'head_pags' => 'Pagamentos e Penalidades',
    'msg_pags_1' => 'Na ausência do carregamento do(s) Voucher(s), no prazo acima descrito, o Provider está sujeito a penalidades;',
    'msg_pags_2' => 'Os repasses de valores são efetuados em até 15 (quinze) dias após a conclusão do serviço;',
    'msg_pags_3' => 'Mais informações sobre panalidades e repasse, acesso o link XXXXX;', //RODRIGO INSERIR LINK
    'more_info' => 'Mais Informações e Dúvidas',
    'msg_info_1' =>"Através do email de suporte <a href='mailto:reservas@amplitur.com'>reservas@amplitur.com</a>",
    ],

    'registry' => [

    'reg_head' => 'Seu Cadastro no site da AMP Travels ',
    'reg_thanks' => 'Obrigado por se cadastrar no nosso site!',
    'reg_attention' => '<strong>Atenção:</strong> o seu cadastro precisa ser validado para que você possa Vender seus produtos e serviços no site da AMP Travels .',
    'valid_link' => 'Clique no link abaixo para efetuar esta validação:',
    'link_valid' => 'Validar Meu Cadastro',
    'link_valid_type' => 'Copie e cole o endereço abaixo em seu navegador para validar sua conta',
    'client_msg' => 'Abaixo, os principais dados preenchidos:',
    'name' => 'Nome',
    'email' => 'E-mail',
    'login' => 'Login',
    'pass' => 'Senha',
    'info_pass' => 'senha ocultada por segurança',
    'login_msg' => 'Faça o login aqui com o seu login ou e-mail para acessar a sua conta e consultar as suas ofertas e vendas',
    'doubt_msg' => 'Caso haja qualquer dúvida, entre em contato.',
    ],

    'company' => [

        'created' => 'Confirmação de Cadastro de Empresa',
        'reg_head' => 'Cadastro de Empresa',
        'reg_thanks' => 'O cadastro de sua empresa foi efetuado com Sucesso!',
        'reg_attention' => '<strong>Atenção:</strong> o seu cadastro passará por analise e tão logo sejam validada as informações, você estará habilitado para comercializar suas ofertas em nosso portal.',
        'client_msg' => 'Abaixo, os principais dados da Empresa cadastrada:',
        'name' => 'Razão Social',
        'name_fantasy' => 'Nome Fantasia',
        'address' => 'Endereço',
        'clique_aqui' => 'Clique Aqui',
        'login_msg' => ' para acessar a sua conta e consultar as suas ofertas e vendas',
        'doubt_msg' => 'Caso haja qualquer dúvida, entre em contato.',
        ],

        //Provider - Valid Registry

        'valid_registry' => [
            'head' => 'Seu Cadastro foi validado com sucesso!',
            'head_msg' => 'Agora você já pode efetuar cadastro de sua empresa, pacotes e ofertas para venda em nossa plataforma.',
            'link_msg' => 'Clique no link abaixo para acessar a área de Login e seu Painel de Controle',
            'link_acess' => 'Área de Login',
            'doubt_msg' => 'Caso haja qualquer dúvida, entre em contato. Boas Vendas!',
        ],

        //Provider - Recovery Password

        'recov_pass' => [
            'head' => 'Recuperação de Senha',
            'recov_msg' => 'Foi solicitado através do site a recuperação de senha para a conta',
            'recov_pass' => 'Clique no link abaixo para cadastrar uma nova senha para a sua conta. Este link será válido para alteração de senha até',
            'minutes' => 'minutos',
            'change_pass_link' => 'Alterar a Senha',
            'link_valid_type' => 'Copie e cole o endereço abaixo em seu navegador para aterar sua senha',
            'end_msg' => 'Caso não tenha solicitado esta alteração, favor desconsiderar este e-mail.',
        ],

        //Provider - Comunication

        'comun' => [

            'update' => 'Houve andamento em sua venda',
            'update_msg' => 'Informamos que houve um andamento em seu processo de venda',
            'booking_code' => 'Localizador',
            'msg_click' => 'Clique no link abaixo para visuazalizar o Localizador',
            'purchase_acess' => 'Acessar a Venda',
        ],
],
];
