@include('backend.packages.filter')
@php 

    $listPackages   = $packages;
    if(isset($_params['package_id']) && $_params['package_id']>0){
        $listPackages = $packages->where("id", $_params['package_id']);
    }
@endphp 
<table id="packagesTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th class="text-center">{{ __('resources.packages.model.status') }}</th>
            <th>{{ __('resources.events.name') }}</th>
            <th>{{ __('resources.packages.model.location') }}</th>
            <th>{{ __('resources.address.address') }}</th>
            <th class="text-center">{{ __('resources.packages.model.updated_at') }}</th>
            <th class="text-center">{{ __('messages.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($listPackages as $package)
            <tr>
                <td>
                    {{ $package->getDateAttribute() }}
                </td>
                <td class=""><a href="{{ route('backend.packages.edit', $package) }}">{{ $package->name }}</a></td>
                <td class="">{{ $package->location }}</td>
                <td class="">
                    {{ city($package->address->city) }} - {{ state($package->address->country, $package->address->state) }} - {{ country($package->address->country) }}
                </td>
                <td class=" text-center">{!! $package->statusLabel !!}</td>
                <td class="text-center skip">

                    <a href="{{ route('backend.offers.index', "analytic=1&package=") }}{{ ($package->id)}} "   class="btn btn-sm btn-secondary" data-toggle="tooltip" data-placement="top" title="{{ __('resources.offers.name-plural') }}">
                        <i class="fa fa-folder-o"></i>
                    </a>
                    @if (user()->canDeletePackage())
                        <a href="{{ route('backend.packages.destroy', $package) }}"  token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                            <i class="fa fa-trash"></i>
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
            $('#packagesTable').DataTable({
                searching: false,
            });
        });
    </script>
@endpush
