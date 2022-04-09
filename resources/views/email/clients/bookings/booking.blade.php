@extends('email.clients.template')

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

    .book {
        padding-top: 1%;
        padding-bottom: 1%;
        padding-left: 1%;
        padding-right: 1%;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        color: #080808;
        -webkit-box-shadow: 0px 10px 13px -7px #000000, -9px 5px 7px -3px #00000000;
        box-shadow: 0px 10px 13px -7px #806767, -9px 5px 7px -3px #03030300;
        background: #FF9708;
    }

    .Inform {
        padding-top: 3%;
        padding-bottom: 3%;
        padding-left: 2%;
        padding-right: 2%;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 18px;
        color: #ffffff;
        -webkit-box-shadow: 0px 10px 13px -7px #000000, -9px 5px 7px -3px #53444400;
        box-shadow: 0px 10px 13px -7px #000000, -9px 5px 7px -3px #03030300;
        background: #ff0808;
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

                    <P><BR><span class="link">{{__('mail.client.booking.confirm_purchase')}}</span></P>

                    <p>{{__('mail.geral.hi')}}
                        {{ $booking->client->name }}</p>

                    <p>{{__('mail.client.booking.introduce_client')}}</p>

                    <p>{{__('mail.client.booking.confirm_thanks')}}</p>

                    <CENTER>
                        <span class="book"><b>{{__('mail.client.booking.booking_code')}}</b> <strong style="color: #27880e;">{{ $booking->id }}</strong></span>
                    </CENTER>

                    <p><strong style="color: #13456e;">{{ $booking->getName() }}</strong></p>

                            @foreach ($booking->bookingPassengers as $bookingPassenger)
                            <p><b>{{__('mail.client.booking.passenger')}}:</b> {{ mb_strtoupper($bookingPassenger->name) }}</p>
                            <ul>
                                @if (empty($booking->getDates()))
                                    <LI style="font-size: 10px;">{{ mb_strtoupper($booking->getProductName()) }}:  {{ money($booking->getProductPrice(), currency(), $booking->offer->currency) }}</li>

                                    @else
                                            @foreach ($booking->getDates() as $date)
                                                <LI style="font-size: 10px;">{{ mb_strtoupper($booking->getProductName($date)) }}:  {{ money($booking->getProductPrice($date), currency(), $booking->offer->currency) }}</li>
                                            @endforeach
                                        @endif
                                            @foreach ($bookingPassenger->bookingPassengerAdditionals ?? [] as $bookingPassengerAdditional)
                                                <LI style="font-size: 10px;">{{ mb_strtoupper($bookingPassengerAdditional->additional->getTitle()) }} : {{ money($bookingPassengerAdditional->additional->getPrice()) }}</li>
                                            @endforeach
                            </ul>
                        @endforeach


                    <BR><BR><p><span class="inform"><b>{{__('mail.client.booking.important_notes')}}</b></span></p><br><br>

                    <span style="font-size: 18px;"><b>{{__('mail.client.booking.track')}}</b></span>

                    <ul>
                        <li style="text-align: justify;" >{{__('mail.client.booking.keep_track')}}
                            <a href="{{ route(getRouteByLanguage('frontend.my-account.bookings.show'), $booking) }}">
                                <span><b>{{__('mail.client.booking.my_account')}}</b></span>
                            </a>
                        </li>
                    </ul>

                    <span style="font-size: 18px;"><b>{{__('mail.client.booking.purchase_cancel')}}</b></span>

                    <ul>
                        <li style="text-align: justify;" >{{__('mail.client.booking.msg_cancel_1')}}</li>
                        <li style="text-align: justify;" >{{__('mail.client.booking.msg_cancel_2')}}</li>
                        <li style="text-align: justify;" >{{__('mail.client.booking.msg_cancel_3')}}</li>
                    </ul>

                    <span style="font-size: 18px;"><b>{{__('mail.client.booking.head_docs')}}</b></span>

                        <ul>
                            <li style="text-align: justify;" >
                                <a href="{{ route(getRouteByLanguage('frontend.my-account.bookings.showContract'), $booking) }}">
                                    <span><b> {{__('mail.client.booking.label_contract')}}</b></span>
                                </a>
                            </li>

                            <li style="text-align: justify;" >{!!__('mail.client.booking.msg_doc_1')!!}</li>
                        </ul>

                    <span style="font-size: 18px;"><b>{{__('mail.client.booking.head_voucher')}}</b></span>

                        <ul>
                            <li style="text-align: justify;" >{{__('mail.client.booking.msg_voucher_1')}}</li>
                            <li style="text-align: justify;" >{{__('mail.client.booking.msg_voucher_2')}}</li>
                        </ul>

                    <span style="font-size: 18px;"><b>{{__('mail.client.booking.more_info')}}</b></span>
                        <ul>
                            <li style="text-align: justify;" >{!!__('mail.client.booking.msg_info_1')!!}</li>
                        </ul>

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

