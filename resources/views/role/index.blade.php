@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />
    <style>
        .dropdown-toggle:after {
            content: none !important;
        }

        li.dropdown-item button,
        li.dropdown-item a {
            border: none;
            background: transparent;
            color: #333;
            padding: 0px 10px;
        }

        li.dropdown-item {
            padding: 10px;
            text-align: left;
        }

        .dt-buttons.btn-group {
            float: left;
        }

        .parent {
            animation: unset !important;
        }
    </style>
    <style>
        .role-group .input-box {
            border: 1px solid #f1f1f1;
            align-items: center;
            margin: 0;
            border-bottom: 0;
            padding: 10px 30px 10px;
            line-height: 0;
        }

        .role-group {
            margin-bottom: 30px;
            border-bottom: 1px solid #f1f1f1;
        }

        table {
            max-width: 99% !important;
            width: 99% !important;
        }

        .role-group .input-box .col-md-6 {
            display: flex;
            align-items: center;
        }

        .role-group .input-box {
            min-height: 43px;
            padding-bottom: 0;
            display: flex;
            align-items: center !important;
            padding-top: 0;
        }

        label {
            margin: 0;
            padding-left: 5px;
        }

        .role-group .input-box:first-child {
            background: #f1f1f1;
            padding: 0 18px;
        }

        .role-group .input-box {
            padding-left: 15px !important;
        }

        .col-md-6.select-box {
            padding: 6px 3px;
        }

        .modal-lg {
            max-width: 1024px;
        }
    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Roles</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>roles</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">list</a></li>
            </ol>
        </div>
        <div class="page-rightheader">



            @if (auth()->user()->checkAllowdByModelID(2))
                <div class="btn btn-list">
                    <a href="javascript:void(0)" id="add_new" class="btn btn-info" data-toggle="tooltip" title=""
                        data-original-title="Add new"><i class="fe fe-plus mr-1"></i> Add new </a>
                </div>
            @endif

        </div>
    </div>
    <!--End Page header-->
@endsection

@section('content')
    <div class="row">
        <!-- Zero Configuration  Starts-->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-head">
                    <div class="card-header">
                        <h4 class="inline">Roles</h4>
                        <div class="heading-elements mt-0">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap" id="role_table">
                            <thead>
                                <tr>
                                    <th width="30px"></th>
                                    <th>Role</th>
                                    <th>Guard</th>
                                    <th width="300px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="role_form_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">

                    <h4 class="modal-title" id="myLargeModalLabel">Add Role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/js/popover.js') }}"></script>

    <!-- INTERNAL Sweet alert js -->
    <script src="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>


    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        var table = $('#role_table').DataTable({
            processing: true,
            serverSide: true,

            lengthChange: false,
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ ',
            },
            ajax: "{{ route('role.index') }}",
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columns: [{
                    'data': null,
                    'defaultContent': '',
                    'checkboxes': {


                        'selectRow': true
                    }
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'guard_name',
                    name: 'guard_name',
                    visible: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },


            ],


            order: [
                [1, 'asc']
            ]
        });

        // console.log(table.buttons().container());

        table.buttons().container()
            .appendTo('#role_table_wrapper .col-md-6:eq(0)');

        // Handle form submission event

        $(function() {
            checkInput();
        });

        function checkInput() {
            $('body').on('click', '.permission .check-all', function() {
                // alert("aaa");
                var check = this.checked;
                $(this).parents('.role-group').find('.check-one').prop("checked", check);
                $('.permission .check-all').each(function() {
                    var parentItem = $(this).parents('.role-group');
                    var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                        '.check-one').length;
                    $(parentItem).find('.check-all').prop("checked", check)
                });
            });
            $('.permission .check-one').click(function() {
                var parentItem = $(this).parents('.nav-treeview').parents('.nav-item');
                var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                    '.check-one').length;
                $(parentItem).find('.check-all').prop("checked", check)
                $('.permission .check-all').each(function() {
                    var parentItem = $(this).parents('.role-group');
                    var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                        '.check-one').length;
                    $(parentItem).find('.check-all').prop("checked", check)
                });
            });
            $('.permission .check-all').each(function() {
                var parentItem = $(this).parents('.role-group');
                var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                    '.check-one').length;
                $(parentItem).find('.check-all').prop("checked", check)
            });
        }

        $(document).on('change', '.schedule_no', function() {
            console.log($(this), $(this).parent().find('.schedule_time'));
            if ($(this).val() == 0) {
                $(this).parent().find('.schedule_time').attr('disabled', 'disabled');
            } else {
                $(this).parent().find('.schedule_time').removeAttr('disabled');
            }
        });


        $(document).on('click', '#add_new', function() {
            // window.addEventListener('load', function() {

            // }, false);
            $.ajax({
                url: "{{ route('role.create') }}",
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add Role");
                    checkValidation();
                    checkInput();
                    $("#role_form_modal").modal('show');
                }
            });
        });

        $(document).on('click', '.edit_form', function() {
            var id = $(this).data('id');
            // console.log($(this).data('path'),"{{ route('users.view', '+id+') }}");
            $.ajax({
                url: $(this).data('path'),
                success: function(response) {
                    // console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Update Role");
                    checkValidation();
                    checkInput();
                    $("#role_form_modal").modal('show');
                }
            });
        });
        $(document).on('click', '.delete-role', function() {
            let id = $(this).data("id");

            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this attribute!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,


            }, function(willDelete) {
                if (willDelete) {

                    $.ajax({
                        type: "POST",
                        url: '{{ url('/') }}/role/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swal({
                                title: response.msg
                            }, function(result) {
                                location.reload();
                            });
                        }
                    });
                }
            });
            // if (confirm("Are you sure to delete this role?")) {
            //     let id = $(this).data("id");

            //     $.ajax({
            //         type: 'DELETE',
            //         url: 'role/' + id,
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         },
            //         data: {
            //             'id': id,
            //             _token: "{{ csrf_token() }}",
            //         },
            //         success: function(data) {
            //             // toastr.success(data.msg);
            //             table.draw();
            //         },
            //         error: function(data) {
            //             table.draw();
            //             // toastr.error('Something went wrong, Please try again');
            //         }
            //     });
            // }
        });

        function checkValidation() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }
    </script>
@endsection
