@for ($i = 1; $i <= $passengers; $i++)
    <div class="lista-passageiros__item passageiro mb">
        <header class="passageiro__header">
            <h3 class="passageiro__titulo">{{ __('frontend.reservas.passageiro') }} {{ $i }}</h3>
        </header>
        <ul class="servicos-adicionais__lista">
            @foreach($additionalGroups as $groupKey => $additionals)
                @php
                $group              = $additionals[0]->group;
                $providerName       = (isset($group->company->company_name)?ucwords($group->company->company_name):null);
                @endphp
                <li>
                    <h4 class="servicos-adicionais__subtitulo mt">{{ mb_strtoupper($group->name) }} @if($providerName != null) - <i>{{ __('frontend.pacotes.prov_name') }} {{$providerName}}</i> @endif</h4>
                </li>
                @if ($image = $group->offer->getAdditionalImage())
                    <li class="servicos-adicionais__foto">
                        <figure>
                            <a class="servicos-adicionais__foto-link" href="{{ $image->getUrl() }}" data-fancybox>
                                <img class="servicos-adicionais__foto-img" src="{{ $image->getUrl() }}" alt="">
                                <figcaption class="servicos-adicionais__foto-legenda">

                                </figcaption>
                            </a>
                        </figure>
                    </li>
                @endif
                @if ($group->isSingleSelection())
                    <li class="servicos-adicionais__item">
                        <div class="servico-adicional form__checkbox form__checkbox--radio">
                            <input type="radio" class="rd-servico-adicional" id="rd-servico-adicional-{{ $i }}-{{ $groupKey }}" checked="" data-valor="0" data-cotacao="1.00" data-moeda="BRL" data-grupo="{{ $offer->type }}" data-numpass="1" name="adicionais[{{ $i }}][{{ $groupKey }}]" value="0">
                            <label for="rd-servico-adicional-{{ $i }}-{{ $groupKey }}">
                                {{ __('frontend.forms.nenhum') }}
                            </label>
                        </div>
                    </li>
                    @foreach ($additionals as $additional)
                        @php ($key = uniqid())
                        <li class="servicos-adicionais__item">
                            <div class="servico-adicional form__checkbox form__checkbox--radio">
                                <input type="radio" data-grupo="{{ $offer->type }}" @if ($additional->isOutOfStock()) disabled @endif class="rd-servico-adicional" data-valor="{{ $additional->price }}" data-moeda="BRL" data-cotacao="1.00" data-numpass="1" name="adicionais[{{ $i }}][{{ $groupKey }}]" value="{{ $additional->id }}" id="rd-servico-adicional-{{ $i }}-{{ $groupKey }}-{{ $key }}">
                                <label for="rd-servico-adicional-{{ $i }}-{{ $groupKey }}-{{ $key }}">
                                    <span class="servicos-adicionais__nome">{{ mb_strtoupper($additional->name) }}</span> –
                                    <strong class="servicos-adicionais__valor">{{ money($additional->getPrice(), currency(), $additional->currency) }}</strong>

                                    @if ($additional->isOutOfStock())
                                        <strong class="servicos-adicionais__selo servicos-adicionais__selo--esgotado">{{ __('frontend.pacotes.esgotado') }} </strong>
                                    @elseif ($additional->isRunningOut())
                                        <strong class="servicos-adicionais__selo servicos-adicionais__selo--ultimas-unidades">{{ $additional->getStock() }} {{ __('frontend.reservas.ultima') }} {{ __('frontend.reservas.unidade') }}</strong>
                                    @endif
                                </label>
                            </div>
                        </li>
                    @endforeach
                @else
                    @foreach ($additionals as $additional)
                        @php ($key = uniqid())
                        <li class="servicos-adicionais__item">
                            <div class="servico-adicional form__checkbox form__checkbox--checkbox">
                                <input type="checkbox" data-grupo="{{ $offer->type }}" @if ($additional->isOutOfStock()) disabled @endif class="rd-servico-adicional" data-valor="{{ $additional->price }}" data-moeda="{{ currency()->code }}" data-cotacao="1.00" data-numpass="1" name="adicionais[{{ $i }}][{{ $groupKey }}][]" value="{{ $additional->id }}" id="rd-servico-adicional-{{ $i }}-{{ $groupKey }}-{{ $key }}">
                                <label for="rd-servico-adicional-{{ $i }}-{{ $groupKey }}-{{ $key }}">
                                    <span class="servicos-adicionais__nome">{{ mb_strtoupper($additional->name) }}</span> –
                                    <strong class="servicos-adicionais__valor">{{ money($additional->getPrice(), currency(), $additional->currency) }}</strong>

                                    @if ($additional->isOutOfStock())
                                        <strong class="servicos-adicionais__selo servicos-adicionais__selo--esgotado">{{ __('frontend.pacotes.esgotado') }} </strong>
                                    @elseif ($additional->isRunningOut())
                                        <strong class="servicos-adicionais__selo servicos-adicionais__selo--ultimas-unidades">{{ $additional->getStock() }} {{ __('frontend.reservas.ultima') }} {{ __('frontend.reservas.unidade') }}</strong>
                                    @endif
                                </label>
                            </div>
                        </li>
                    @endforeach
                @endif

            @endforeach
        </ul>
    </div>
@endfor




