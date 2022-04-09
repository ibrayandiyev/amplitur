<table id="saleCoefficientsTable" class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>{{ __('resources.saleCoefficients.model.name') }}</th>
            <th class="text-center">{{ __('resources.saleCoefficients.model.value') }}</th>
            <th class="text-center">{{ __('messages.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($saleCoefficients as $key => $saleCoefficient)
            <tr>
                <td class="text-uppercase">
                    <a href="{{ route('backend.saleCoefficients.edit', $saleCoefficient->id) }}">{{ $saleCoefficient->name }}</a>
                    @if ($saleCoefficient->is_default)
                        <span class="label label-primary m-l-20">{{ __('messages.default') }}</span>
                    @endif
                </td>
                <td class="text-center">{{ $saleCoefficient->value }}</td>
                <td class="text-center skip">
                    <a href="{{ route('backend.saleCoefficients.destroy', $saleCoefficient->id) }}" token="{{ csrf_token() }}" class="btn btn-danger btn-sm delete" data-toggle="tooltip" data-placement="top" title="{{ __('messages.delete') }}">
                        <i class="fa fa-trash"></i>
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#saleCoefficientsTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
    </script>
@endpush
