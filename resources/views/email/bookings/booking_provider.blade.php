@extends('email.bookings.template')

@section('content')

<div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #0e7cb2;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #0e7cb2;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div class="borders-div"
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:15px 10px;font-family:arial,helvetica,sans-serif;" align="left">

                      <div class="text-left">
                        <p><span style="font-size: 18px; line-height: 25.2px;"><strong><span style="color: #ffffff; line-height: 25.2px; font-size: 18px;">{{__('mail.provider.confirm_purchase')}} PASTA BOOKING - BOOKING_PROVIDER</span></strong>
                          </span>
                        </p>
                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>



  <div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #ffffff;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div class="borders-div"
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">

                      <div class="text-left">
                        <p><span style="color: #236fa1; font-size: 14px; line-height: 19.6px;"><strong><em><span style="font-size: 16px; line-height: 22.4px;">{{__('mail.provider.introduce_client')}}</span></em>
                          </strong>
                          </span>
                        </p>
                        <p><span style="color: #000000; font-size: 12px; line-height: 16.8px;"><span style="line-height: 16.8px; font-size: 12px;">{{__('mail.provider.confirm_thanks')}}</span></span>
                        </p>
                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>



  <div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="560" style="background-color: #f1c40f;width: 560px;padding: 0px;border-top: 20px solid #ffffff;border-left: 20px solid #ffffff;border-right: 20px solid #ffffff;border-bottom: 20px solid #ffffff;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #f1c40f;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div style="padding: 0px;border-top: 20px solid #ffffff;border-left: 20px solid #ffffff;border-right: 20px solid #ffffff;border-bottom: 20px solid #ffffff;">
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">

                      <div class="text-left">
                        <p><span style="font-size: 18px; line-height: 25.2px;"><strong>{{__('mail.provider.booking_code')}} </strong></span><span style="font-size: 18px; line-height: 25.2px; color: #169179;"><strong>{{ $booking->id }}</strong></span></p>
                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>



  <div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #06354d;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #06354d;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div class="borders-div"
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">

                      <div class="text-left">
                        <p><span style="color: #ffffff; font-size: 14px; line-height: 19.6px;"><em><strong><span style="font-size: 16px; line-height: 22.4px;">{{ $booking->getName() }}</span></strong>
                          </em>
                          </span>
                        </p>
                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>



  <div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #ffffff;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div class="borders-div"
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">

                            <div class="text-left">
                                @foreach ($booking->bookingPassengers as $bookingPassenger)
                                    <p style="text-align: left;"><strong>{{__('mail.provider.passenger')}}: </strong>{{ mb_strtoupper($bookingPassenger->name) }}</p>
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
                            </div>


                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>



  <div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #e03e2d;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #e03e2d;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div class="borders-div"
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">

                      <div class="text-left">
                        <p><span style="color: #ffffff; font-size: 14px; line-height: 19.6px;"><em><span style="font-size: 18px; line-height: 25.2px;"><strong>	{{__('mail.provider.important_notes')}}</strong></span></em>
                          </span>
                        </p>
                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>



  <div class="u-row-container td-u-row">
    <div class="u-row add-u-row">
      <div class="add-2">
        <!--[if (mso)|(IE)]><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="padding: 0px;background-color: transparent;" align="center"><table cellpadding="0" cellspacing="0" border="0" style="width:600px;"><tr style="background-color: transparent;"><![endif]-->

        <!--[if (mso)|(IE)]><td align="center" width="600" style="background-color: #ffffff;width: 600px;padding: 0px;border-top: 0px solid transparent;border-left: 0px solid transparent;border-right: 0px solid transparent;border-bottom: 0px solid transparent;" valign="top"><![endif]-->
        <div class="u-col u-col-100 add-3">
          <div style="background-color: #ffffff;width: 100% !important;">
            <!--[if (!mso)&(!IE)]><!-->
            <div class="borders-div"
              <!--<![endif]-->

              <table class="font-all" role="presentation" cellpadding="0" cellspacing="0" width="100%" border="0">
                <tbody>
                  <tr>
                    <td style="overflow-wrap:break-word;word-break:break-word;padding:10px;font-family:arial,helvetica,sans-serif;" align="left">

                      <div class="text-left">

                        <p style="text-align: left;"><em><strong><span style="color: #236fa1; font-size: 16px; line-height: 22.4px;">{{__('mail.provider.purchase_cancel')}}</span></strong></em></p>
                        <ul style="text-align: left;">
                            <LI>{{__('mail.provider.msg_cancel_1')}}</li>
                            <LI>{{__('mail.provider.msg_cancel_2')}}</li>
                            <LI>{{__('mail.provider.msg_cancel_3')}}</li>
                        </ul>

                        <p style="text-align: left;"><em><strong><span style="color: #236fa1; font-size: 16px; line-height: 22.4px;">{{__('mail.provider.head_voucher')}}</span></strong></em></p>
                        <ul style="text-align: left;">
                            <LI>{{__('mail.provider.msg_voucher_1')}}</li>
                        </ul>

                        <p style="text-align: left;"><em><strong><span style="color: #236fa1; font-size: 16px; line-height: 22.4px;">{{__('mail.provider.head_pags')}}</span></strong></em></p>
                        <ul style="text-align: left;">
                            <LI>{{__('mail.provider.msg_pags_1')}}</li>
                            <LI>{{__('mail.provider.msg_pags_2')}}</li>
                            <LI>{!!__('mail.provider.msg_pags_3')!!}</li>
                        </ul>

                        <p style="text-align: left;"><em><strong><span style="color: #236fa1; font-size: 16px; line-height: 22.4px;">{{__('mail.provider.more_info')}}</span></strong></em></p>
                        <ul style="text-align: left;">
                            <LI>{!!__('mail.provider.msg_info_1')!!}</li>
                        </ul>

                      </div>

                    </td>
                  </tr>
                </tbody>
              </table>

              <!--[if (!mso)&(!IE)]><!-->
            </div>
            <!--<![endif]-->
          </div>
        </div>
        <!--[if (mso)|(IE)]></td><![endif]-->
        <!--[if (mso)|(IE)]></tr></table></td></tr></table><![endif]-->
      </div>
    </div>
  </div>

@endsection

