@extends('frontend.template.document')

@section('content')

<table width="100%">
    <TR>
        <TD>
	        <img src="https://www.amp-travels.com/images/amp-travel-front-bgwhite2.png" alt="Amp Travels" align=left>
        </TD>
        <td>
        	<h3 align=right>
                <P> <B>AMP Travels Ltd. </B>
                <BR>138, Chapel Street
                <BR>M36DE - Salford - United Kingdom
                <BR>Company Number 13500131</P>
            </h3>
        </td>
    </TR>
</table>

<br><hr>

<div id="corpo-doc">


	<p><CENTER>
		<font size=6 color=black><strong>INVOICE</strong> </font>
        <font size=6 color=red>{{ $booking->id }} - {{ $bookingBill->installment }}</font><br>

		<font size=4 color=black><strong>BOOKING CODE:</strong></font>
        <font size=4 color=red>{{ $booking->id }}</font>
		|
		<font size=4 color=black><strong>DATE:</strong></font>
        <font size=4 color=red>{{ $bookingBill->createdAtLabel }}</font>
				|
		<font size=4 color=black><strong>DEAD LINE:</strong> </font>
        <font size=4 color=red>{{ $bookingBill->expiresAtLabel }}</font>

	</CENTER></p>
	<hr>

	<h4>
	<strong>CLIENT:</strong>
	{{ $bookingClient->name}}<BR>
	@switch($bookingClient->primary_document)
		@case("identity")
			<strong>IDENTITY DOCUMENT (ID):</strong> {{$bookingClient->identity}}
		@break;
		@default:
			<strong>PASSPORT:</strong> {{$bookingClient->passport}}
		@break;
	@endswitch
	<BR>
	<strong>PHONE NUMBER:</strong>  {{$bookingClient->phone}} <BR>
	<strong>ADDRESS:</strong>
	{{$bookingClient->address}}, {{$bookingClient->address_number}}
	@if($bookingClient->address_complement != null)
	, {{$bookingClient->address_complement}}
	@endif
	, {{$bookingClient->address_neighborhood}}, {{$bookingClient->address_city}} &ndash; {{$bookingClient->address_state}}
	</h4>

	<hr>
	<TABLE border=1 width=100%>
		<TR><TD width=80% align=center><B>Service</B></TD>
			<TD width=20% align=center><B>Quantity</B></TD>
		</TR>
		<TR>
			<TD>
				<strong>
					{{ $booking->package->getExtendedTitle() }}
					<BR>
					{{ $booking->getProductName() }}
				</strong>
			</TD>
			<TD align=center>
					{{$bookingPassengers->count()}}
			</TD>
		</TR>
		@foreach($bookingPassengers as $bookingPassenger)
			@if($bookingPassenger->bookingPassengerAdditionals()->get())
				<TR>

					<TD>
						{{ mb_strtoupper($bookingPassenger->name) }}
					</TD>
					<TD align=center>
						{{ $bookingPassenger->bookingPassengerAdditionals()->get()->count()}}
					</TD>
				</TR>
			@endif
		@endforeach
	</TABLE>

	<hr>

	<p align=right>
		<font size=5 color=black>GRAND TOTAL:</FONT>
		<font size=5 color=red>{{$bookingBill->currency->code}} {{ $bookingBill->total}}</FONT>
	</p>
	@if($invoiceInformation != null)
		@php
			$invoice_id =  $booking->id ." - ". $bookingBill->installment;
			$invoiceInformation->description = str_replace("{invoice_id}", $invoice_id, $invoiceInformation->description);
		@endphp
		<hr>
		{!! $invoiceInformation->description !!}
	@endif

	<hr>
	<small>
		BOOKING CODE: {{ $booking->id }} | {{date('d/m/Y H:i:s')}}
	</small>
	<hr />
	<B><U>Head Office</U></B><BR><B>Amp Travels Ltd.</B><BR>
	138, Chapel Street<br>
	Salford - United Kingdom - M3 6DE<BR>
	Company Number 13500131<BR>
	Tel.: + 44 (0) 1615 116 350<BR>
	E-mail - amplitur@amplitur.com<BR>

</div>
@endsection

@push('styles')
@endpush
