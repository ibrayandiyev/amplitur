<div class="box mb">
    <header class="box__header">
        <h3 class="box__titulo">{{ __('frontend.reservas.passageiros') }}</h3>
    </header>

    <div class="box__conteudo">
        <ul class="reserva-passageiros flex-grid flex-grid--gutters">
            @foreach ($booking->bookingPassengers as $bookingPassenger)
            <li class="reserva-passageiros__item grid-cell-xs--12 grid-cell-md--6">
                <h4 class="reserva-passageiros__titulo">{{ mb_strtoupper($bookingPassenger->name) }}</h4>
                <p>
                    @switch($bookingPassenger->primary_document)
                        @case(App\Enums\DocumentType::PASSPORT)
                            {{ __('frontend.forms.documento_passaporte') }}: {{ mb_strtoupper($bookingPassenger->passport) }} <br />
                        @break
                        @case(App\Enums\DocumentType::IDENTITY)
                            {{ __('frontend.forms.documento_id') }}: {{ mb_strtoupper($bookingPassenger->identity) }} - {{ mb_strtoupper($bookingPassenger->uf) }} <br />

                        @break
                        @case(App\Enums\DocumentType::DOCUMENT)
                            {{ __('frontend.forms.documento') }}: {{ mb_strtoupper($bookingPassenger->document) }} <br />

                        @break
                    @endswitch
                    {{ __('frontend.forms.telefone_secundario') }}: {{ mb_strtoupper($bookingPassenger->phone) }} <br/>
                    {{ __('frontend.forms.data_nascimento') }}: {{ mb_strtoupper($bookingPassenger->birthdate->format('d/m/Y')) }}
                </p>
            </li>
            @endforeach
        </ul>
    </div>
</div>