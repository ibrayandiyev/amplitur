@if(!$bookingBill->isPaid())
<a href="{{ route(getRouteByLanguage('frontend.my-account.bookings.credit-card-payment'), ['booking' => $booking, 'bookingBill' => $bookingBill]) }}">{{__('frontend.reservas.pagar')}}</a>
@endif
