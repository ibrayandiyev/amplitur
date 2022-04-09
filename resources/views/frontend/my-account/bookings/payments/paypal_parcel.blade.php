@if(!$bookingBill->isPaid())
<form action="{{ route('frontend.my-account.bookings.reservation.do-payment', ['booking' => $booking, 'bookingBill' => $bookingBill]) }}" method="post" accept-charset="utf-8">
    <button type='submit'>{{__('frontend.reservas.pagar')}}</button>
</form>
@endif
