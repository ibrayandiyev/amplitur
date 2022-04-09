@include('backend.offers.filter')
@csrf
<table id="offersTable" class="table table-bordered table-striped table-hover datatable">
    <thead>
        <tr>
            <th>{{ __('resources.packages.name') }}</th>
            <th>{{ __('resources.providers.name') }}</th>
            <th>{{ __('resources.companies.name') }}</th>
            <th class="text-center">{{ __('resources.offers.model.type') }}</th>
            <th class="text-center">{{ __('resources.offers.model.status') }}</th>
            <th class="text-center">{{ __('resources.offers.model.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($offers as $offer)
            <tr>
                <td width="48%"class=""><a href="{{ route('backend.providers.companies.offers.edit', [$offer->provider, $offer->company, $offer]) }}">{{ $offer->package->extendedName }}</a></td>
                <td width="15%"class="">{{ $offer->provider->name }}</td>
                <td width="15%"class="">{{ $offer->company->company_name }}</td>
                <td width="10%"class="text-center">{!! $offer->typeLabel !!}</td>
                <td width="5%"class="text-center">{!! $offer->statusLabel !!}</td>
                <td width="8%" class="text-center skip">
                    @if (user()->canDeleteOffer($offer))
                        <a href="{{ route('backend.providers.companies.offers.destroy', [$offer->provider, $offer->company, $offer]) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                            <i class="fa fa-trash"></i>
                        </a>
                    @endif

                    @if (user()->canReplicateOffer($offer))
                        <a href="{{ route('backend.offers.replicate', [$offer]) }}"  class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="{{ __('resources.offers.replicate') }}">
                            <i class="fa fa-copy"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#offersTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
