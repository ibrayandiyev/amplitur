@forelse ($packages as $package)
    @if (count($package->additionals) > 0)
        <div class="col-md-12 grouped-entities">
            <h2 class="group-entity-head">{{ $package->extendedName }}</h2>

            @foreach ($package->additionals->groupBy('additional_group_id')->all() as $additionals)
                <h3 class="group-entity-subhead">{{ $additionals->first()->group->name ?? '--' }}</h3>
                <table id="tableAdditionals-{{$package->id}}" class="table table-bordered table-striped table-hover table-linked-row additionals-table">
                    <thead>
                        <tr>
                            <th width="20%">{{ __('resources.providers.name') }}</th>
                            <th width="20%">{{ __('resources.additionals.model.name') }}</th>
                            <th width="15%">{{ __('resources.additionals.model.type') }}</th>
                            <th width="15%">{{ __('resources.additionals.model.sale_price') }}</th>
                            <th width="5%">{{ __('resources.additionals.model.stock') }}</th>
                            <th width="5%" class="text-center">{{ __('messages.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($additionals as $additional)
                            <tr data-href="{{ route('backend.additionals.edit', $additional) }}" data-put>
                                <td class="">
                                    <input type="hidden" name="id" value="{{ $additional->id }}" />
                                    {{ $additional->provider->name }}
                                </td>
                                <td class="">{{ $additional->name }}</td>
                                <td class="">{!! $additional->typeLabel !!}</td>
                                <td class=" skip">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                {{ $additional->currency }}
                                            </span>
                                        </div>
                                        <input type="text" class="form-control input-money" name="price" value="{{ $additional->extendedPrice }}" />
                                    </div>
                                </td>
                                <td class=" skip">
                                    <input type="number" min="0" class="form-control" name="stock" value="{{ $additional->getStock() }}" />
                                </td>
                                <td class="text-center skip">
                                    <a href class="btn btn-primary save-put" data-toggle="tooltip" data-placement="top" title="{{ __('messages.save') }}">
                                        <i class="fa fa-save"></i>
                                    </a>
                                    <a href="{{ route('backend.additionals.destroy', $additional) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    @endif
@empty
    <div class="card-body">
        <div class="col-md-12">
            <div class="alert alert-info">
            <span>Nenhum adicional disponível ou cadastrado. Clique em <strong><a class="btn btn-sm btn-primary" href="{{ route('backend.additionals.create') }}"><i class="fa fa-plus"></i> {{ __('resources.additionals.create') }}</a></strong> para cadastrar</span>
            </div>
        </div>
    </div>
@endforelse



@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('.additionals-table').DataTable({
                searching: false,
                bPaginate: false,
                order: [[1, 'asc']],
                bInfo: false,
            });
        });
    </script>
@endpush

@push('metas')
<meta name="csrf_token" content="{{ csrf_token() }}">
@endpush
