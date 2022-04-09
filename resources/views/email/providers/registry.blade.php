@extends('email.providers.template')

@section('content')

        <STYLE type=text/css>
            .body {
                background-color: #e4e4e4;
                width: 100%;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 13px;
                margin-top: 0%;
            }

            .table {
                width: 600px;
                border-collapse: collapse;
                background-color: #fffbfb;
                margin-top: 0;
            }

            .td_head_footer {
                background-color: #0E7CB2;
            }

            .td_padding {
                padding-top: 2%;
                padding-left: 2%;
                padding-right: 2%;
                padding-bottom: 2%;
            }

            p {
                padding-top: 1%;
                padding-bottom: 1%;
                text-align: justify;
            }

            .link {
                padding-top: 1%;
                padding-bottom: 1%;
                padding-left: 1%;
                padding-right: 1%;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 18px;
                color: #ffffff;
                -webkit-box-shadow: 0px 10px 13px -7px #ffffff, -9px 5px 7px -3px #00000000;
                box-shadow: 0px 10px 13px -7px #806767, -9px 5px 7px -3px #03030300;
                background: #13456e;
            }

            a:active, a:visited, a:link  {
                text-decoration: none;
                color: #ffffff;
            }

            a:hover {
                text-decoration: underline;
	            color: #ffffff;
            }
        </style>
    </head>

    <body class="body">
        <center>
            <table class="table" border="1">

                <table class="table">
                    <tr>
                        <td class="td_head_footer td_padding">
                            <img src="https://www.amp-travels.com/images/amp-travel-front-bgblue.png"
                                alt="logo" height="120 px" width="99 px" border="0" />
                        </td>
                    </tr>
                </table>

                <table class="table">
                    <tr>
                        <td class="td_padding">

                            <P><BR><span class="link">{{__('mail.provider.registry.reg_head')}}</span></P>

                            <p>{{__('mail.provider.registry.reg_thanks')}}PASTA PROVIDERS - REGISTRY</p>

                            <p><b>{!!__('mail.provider.registry.reg_attention')!!}</b> </p>
                            <p> {{__('mail.provider.registry.valid_link')}}</p>
                            <CENTER>
                                <span class="link">{{__('mail.provider.registry.link_valid')}}</span><BR><BR>

                                <span> link por extenso</span>
                            </CENTER>

                            <P>{{__('mail.provider.registry.client_msg')}}</P>

                            <p>
                                <B>{{__('mail.provider.registry.name')}}: </B><I>NATALIA ISABEL JIMENEZ BRAGA</I><br>
                                <B>{{__('mail.provider.registry.email')}}: </B><I>taia.jimenez@gmail.com</i><br>
                                <B>{{__('mail.provider.registry.login')}}: </B><i>Natamplitur</i><br>
                                <B>{{__('mail.provider.registry.pass')}}: </B><i><u>{{__('mail.client.registry.info_pass')}}</u></i>
                            </p>

                            <p>{{__('mail.provider.registry.login_msg')}}</p>

                            <p>{{__('mail.provider.registry.doubt_msg')}}</p>

                            <p>{{__('mail.geral.att')}}</p>

                            <p>
                                <B><strong>AMP Travels </strong><br>
                                    <a href="https://www.amplitur.com" title="AMP Travels " target="_blank">www.amplitur.com</a>
                            </p>
                        </td>
                    </tr>
                </table>

                <table class="table">
                    <tr class="td_head_footer">
                        <td class="td_padding"><CENTER>
                            <span style="font-size: 11px; color: #ffffff;">{!!__('mail.geral.direitos')!!}</span><br><br>
                                    <a href="https://www.facebook.com/amplitur.operadoradeturismo" title="Facebook" target="_blank" style="text-decoration: none; padding-right: 2%; padding-left: 2%;">
                                <img src="https://www.amplitur.com/assets/dist/imagens/estrutura/facebook.png" alt="Facebook" title="Facebook" width="32">
                            </a>

                            <a href="https://instagram.com/aamplituroperadora/" title="Instagram" target="_blank" style="text-decoration: none; padding-right: 2%; padding-left: 2%;">
                                <img src="https://www.amplitur.com/assets/dist/imagens/estrutura/instagram.png" alt="Instagram" title="Instagram" width="32" >
                            </a>

                            <a href="http://www.twitter.com/ampliturturismo" title="Twitter" target="_blank" style="text-decoration: none; padding-right: 2%; padding-left: 2%;">
                                <img src="https://www.amplitur.com/assets/dist/imagens/estrutura/twitter.png" alt="Twitter" title="Twitter" width="32">
                            </a></CENTER>
                        </td>
                    </tr>
                </table>

            </table>
        </center>
    </body>
    </html>

    @endsection
