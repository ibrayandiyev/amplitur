
<table id="reportsTable" class="table full-color-table full-inverse-table hover-table">
        <thead>
            <tr>
                <th class="text-center">{{__('report.id')}}</th>
                <th class="text-center">{{__('report.date')}}</th>
                <th class="text-center">{{__('report.name')}}</th>
                <th class="text-center">{{__('report.email')}}</th>
            </tr>

        </thead>
        <tbody>
        @foreach($newsletters->sortByDesc("id") as $newsletter)
            <tr>
                <td class="text-center">{{ $newsletter->id }}</td>
                <td class="text-center">{{ $newsletter->created_at->format("Y-m-d H:i:s") }}</td>
                <td class="text-center">{{ $newsletter->name }}</td>
                <td class="text-center">{{ $newsletter->email }}</td>
            </tr>
        @endforeach
        </tbody>
</table>

@push('scripts')
    <script src="/backend/vendors/datatables/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $('#reportsTable').DataTable({
                searching: false,
                ordering: false,
            });
        });
    </script>
@endpush

