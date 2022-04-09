-@forelse ($packages as $package)
    @if (count($package->additionals) > 0 && count($package->offers))
        <div class="col-md-12 grouped-entities">
            <h2 class="group-entity-head-no-sub">{{ $package->extendedName }}</h2>
            <table id="tableOffers-{{$package->id}}" class="table table-bordered table-striped table-hover offers-table">
                <thead>
                    <tr>
                        <th width="20%">{{ __('resources.providers.name') }}</th>
                        <th width="20%">{{ __('resources.companies.name') }}</th>
                        <th width="12%" class="text-center">{{ __('resources.offers.model.type') }}</th>
                        <th width="12%" class="text-center">{{ __('resources.offers.model.status') }}</th>
                        <th width="25%">{{ __ ('resources.additionals.name-plural') }}</th>
                        <th width="5%" class="text-center">{{ __('resources.offers.model.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($package->offers as $offer)
                        @if (count($offer->additionals) > 0)
                            <tr>
                                <td class=""><a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $offer->provider->name }}</a></td>
                                <td class="">{{ $offer->company->company_name }}</td>
                                <td class="text-center">{!! $offer->typeLabel !!}</td>
                                <td class="text-center">{!! $offer->statusLabel !!}</td>
                                <td>
                                    @foreach($offer->additionals as $additional)
                                        <span class="label label-primary">{{ $additional->name }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center skip">
                                    <a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}"  class="btn btn-secondary" data-toggle="tooltip" data-placement="top" title="Gerenciar vínculos">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                    <a href="{{ route('backend.providers.companies.offers.destroy', [$offer->provider, $offer->company, $offer]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <div class="card-body">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                <span>Nenhuma oferta com adicionais vinculados</span>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endif
@empty
    <div class="card-body">
        <div class="col-md-12">
            <div class="alert alert-info">
            <span>Nenhuma oferta com adicionais vinculados</span>
            </div>
        </div>
    </div>
@endforelse



@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('.offers-table').DataTable({
                searching: false,
                bPaginate: false,
                bInfo: false,
            });
        });
    </script>
@endpush
