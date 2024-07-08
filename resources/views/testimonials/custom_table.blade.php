<table class="table table-bordered text-nowrap" id="attribute_table">
    <thead>
        <tr>
            <th>No</th>
            <th>testimonials Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

@section('table_common_script')
    <script>
        $(document).ready(function() {
            console.log('asdadasd')
            var table = $('#attribute_table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                dom: 'lBftrip',
                buttons: ['copy', 'excel', 'pdf', 'colvis'],
                responsive: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: '_MENU_ ',
                },
                ajax: "{{ route('testimonials.index') }}",
                columns: [{
                        data: null,
                        orderable: false,
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                    },

                ],
                order: [
                    [1, 'asc']
                ]
            });
        });
    </script>
@endsection
