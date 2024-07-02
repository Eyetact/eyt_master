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
    <link href="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/plugins/wysiwyag/richtext.css"
        rel="stylesheet" />
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

        table {
            max-width: 99% !important;
            width: 99% !important;
        }

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

        .custom-limit {
            height: 30px !important;
            width: 70px !important;
            /* float: right !important; */
        }

        .custom-checkbox.permission.input-box:after {
            content: no-close-quote;
            top: 0px;
            bottom: 0px;
            left: 100%;
            right: -27px;
            background: #f1f1f1;
            position: absolute;
            width: auto;
            height: auto;
            z-index: 0;
        }

        .custom-checkbox.permission.input-box {
            position: relative;
        }

        label.custom-switch.form-label {
            margin-left: -106px;
            position: absolute;
            top: 12px;
        }

        .hide-input {
            opacity: 0;
        }
    </style>
@endsection
@push('styles')
    <!-- INTERNAL Sumoselect css-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sumoselect/sumoselect.css') }}    ">

    <!-- INTERNAL File Uploads css -->
    <link href="{{ asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css-->
    <link href="{{ asset('assets/plugins/fileupload/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Plans</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Plans</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            @can('create.plan')
                @if (auth()->user()->checkAllowdByModelID(4))
                    <div class="btn btn-list">
                        <a id="add_new" class="btn btn-info" data-toggle="tooltip" title=""
                            data-original-title="Add new"><i class="fe fe-plus mr-1"></i> Add new </a>

                    </div>
                @endif
            @endcan
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-12">


            <div class="card">
                <div class="card-header">
                    <div class="card-title">Plans Data</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap" id="attribute_table">
                            <thead>
                                <tr>


                                    <th width="30px">No.</th>
                                    <th>Name</th>
                                    <th>details</th>
                                    <th>image</th>
                                    <th>period</th>
                                    <th>price</th>
                                    <th>module limit</th>
                                    <th>data limit</th>
                                    <th data-priority="1"></th>
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
    <!-- /Row -->

    </div>
    </div><!-- end app-content-->
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


    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- INTERNAL File uploads js -->
    <script src="{{ asset('assets/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/filupload.js') }}"></script>

    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!--INTERNAL Form Advanced Element -->
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>


    <script type="text/javascript">
        $(document).on('click', '#add_new', function() {
            // window.addEventListener('load', function() {

            // }, false);
            $.ajax({
                url: "{{ route('plans.create') }}",
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add Plan");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                    checkInput();

                    $('.check-all').on('change', function() {

                        var checkbox = $(this);

                        var numberInput = checkbox.closest('.role-group').find('.number-input');
                        var un = checkbox.closest('.role-group').find('.custom-limit-checkbox');


                        if (checkbox.is(':checked')) {
                            if (numberInput.attr('max') == 0) {
                                numberInput.val(0)

                            } else {
                                numberInput.val(1)
                            }


                        } else {
                            numberInput.val(0)
                            un.val(0).prop('checked', false).trigger('change')
                            numberInput.removeClass('hide-input')




                        }


                    });
                    $('.number-input').on('change', function() {

                        var input = $(this);
                        var checkAll = input.closest('.role-group').find('.check-all');
                        var un = input.closest('.role-group').find('.custom-limit-checkbox');
                        checkAll.val(1).prop('checked', true)
                        if (input.val() < 0) {
                            un.val(1).prop('checked', true).trigger('change')


                        }




                    });

                    $('.custom-limit-checkbox').on('change', function() {

                        var checkbox = $(this);

                        var numberInput = checkbox.closest('.role-group').find('.number-input');
                        var checkAll = checkbox.closest('.role-group').find('.check-all');
                        numberInput.toggleClass('hide-input')

                        if (checkbox.is(':checked')) {
                            numberInput.val(-1).prop('readonly', true);
                            checkAll.val(1).prop('checked', true)


                        } else {
                            numberInput.val(0).prop('readonly', false);
                            checkAll.val(0).prop('checked', false)


                        }
                    });






                }
            });
        });

        $(document).on('click', '#edit_item', function() {
            // window.addEventListener('load', function() {

            // }, false);
            var path = $(this).data('path')
            $.ajax({
                url: path,
                success: function(response) {
                    console.log(path);
                    console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("edit Plan");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                    checkInput();
                    $('.check-all').on('change', function() {

                        var checkbox = $(this);

                        var numberInput = checkbox.closest('.role-group').find('.number-input');
                        var un = checkbox.closest('.role-group').find('.custom-limit-checkbox');


                        if (checkbox.is(':checked')) {
                            if (numberInput.attr('max') == 0) {
                                numberInput.val(0)

                            } else {
                                numberInput.val(1)
                            }


                        } else {
                            numberInput.val(0)
                            un.val(0).prop('checked', false).trigger('change')
                            numberInput.removeClass('hide-input')




                        }


                    });
                    $('.number-input').on('change', function() {

                        var input = $(this);
                        var checkAll = input.closest('.role-group').find('.check-all');
                        var un = input.closest('.role-group').find('.custom-limit-checkbox');
                        checkAll.val(1).prop('checked', true)
                        if (input.val() < 0) {
                            un.val(1).prop('checked', true).trigger('change')


                        }




                    });

                    $('.custom-limit-checkbox').on('change', function() {

                        var checkbox = $(this);

                        var numberInput = checkbox.closest('.role-group').find('.number-input');
                        var checkAll = checkbox.closest('.role-group').find('.check-all');
                        numberInput.toggleClass('hide-input')

                        if (checkbox.is(':checked')) {
                            numberInput.val(-1).prop('readonly', true);
                            checkAll.val(1).prop('checked', true)


                        } else {
                            numberInput.val(0).prop('readonly', false);
                            checkAll.val(0).prop('checked', false)


                        }
                    });
                }
            });
        });
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
            ajax: "{{ route('plans.index') }}",

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
                    data: 'details',
                    name: 'details'
                },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'period',
                    name: 'period'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'model_limit',
                    name: 'model_limit'
                },
                {
                    data: 'data_limit',
                    name: 'data_limit'
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
            .appendTo('#attribute_table_wrapper .col-md-6:eq(0)');


        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        $(document).on('click', '.plan-delete', function() {
            var id = $(this).attr("data-id");
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
                        url: '{{ url('/') }}/plan/delete/' + id,
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
        });


        $(function() {
            checkInput();
        });

        function checkInput() {
            $('body').on('click', '.permission .check-all', function() {
                // alert("aaa");
                var check = this.checked;
                $(this).parents('.role-group').find('.check-one').prop("checked", check);
                // $('.permission .check-all').each(function() {
                //     var parentItem = $(this).parents('.role-group');
                //     var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                //         '.check-one').length;
                //     $(parentItem).find('.check-all').prop("checked", check)
                // });
            });
            $('.permission .check-one').click(function() {
                var parentItem = $(this).parents('.nav-treeview').parents('.nav-item');
                var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                    '.check-one').length;
                $(parentItem).find('.check-all').prop("checked", check)
                // $('.permission .check-all').each(function() {
                //     var parentItem = $(this).parents('.role-group');
                //     var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                //         '.check-one').length;
                //     $(parentItem).find('.check-all').prop("checked", check)
                // });
            });
            $('.permission .check-all').each(function() {
                var parentItem = $(this).parents('.role-group');
                var check = $(parentItem).find('.check-one:checked').length == $(parentItem).find(
                    '.check-one').length;
                $(parentItem).find('.check-all').prop("checked", check)
            });
        }
    </script>
    <script src="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/plugins/wysiwyag/jquery.richtext.js">
    </script>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <script type="text/javascript">
        $(function(e) {
            $('.content').richText();
            $('.content2').richText();
        });
    </script>
@endsection
