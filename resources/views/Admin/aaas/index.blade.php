@extends('layouts.master')

@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ __('Aaas') }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">{{ __('Aaas') }}</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
        @can('create.aaa')
            <div class="btn btn-list">
                <a id="add_new" class="btn btn-info" data-toggle="tooltip" title="" data-original-title="Add new"><i
                        class="fe fe-plus mr-1"></i> Add new </a>
            </div>

            @endcan
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
     <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                <div class="card-title">{{ __('Aaas') }}</div>

                </div>
                <div class="card-body">
                    <div class="table-responsive p-1">
                        <table class="table align-items-center mb-0" id="data_table" width="100%">
                            <thead>
                                <tr>
                                    <th width="30px"></th>
                                    
                                    <th>{{ __('Created At') }}</th>
                                    <th>{{ __('Updated At') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
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
@section('css')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />
        <!-- INTERNAL File Uploads css -->
    <link href="{{ asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css-->
    <link href="{{ asset('assets/plugins/fileupload/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
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
        .range {
  -webkit-appearance: none;
  vertical-align: middle;
  outline: none;
  border: none;
  padding: 0;
  background: none;
    width:100%;
    box-shadow:0
}

.range::-webkit-slider-runnable-track {
  background-color: #d7dbdd;
  height: 20px;
  border-radius: 3px;
  border: 1px solid transparent;
  margin-top:-5px
}

.range[disabled]::-webkit-slider-runnable-track {
  border: 1px solid #d7dbdd;
  background-color: transparent;
  opacity: 0.4;
}

.range::-moz-range-track {
  background-color: #d7dbdd;
  height: 6px;
  border-radius: 3px;
  border: none;
}

.range::-ms-track {
  color: transparent;
  border: none;
  background: none;
  height: 6px;
}

.range::-ms-fill-lower {
  background-color: #d7dbdd;
  border-radius: 3px;
}

.range::-ms-fill-upper {
  background-color: #d7dbdd;
  border-radius: 3px;
}

.range::-ms-tooltip { display: none; /* display and visibility only */ }

.range::-moz-range-thumb {
  border-radius: 20px;
  height: 18px;
  width: 18px;
  border: none;
  background: none;
  background-color: #705ec8;
  margin-bottom:-10px;
}

.range:active::-moz-range-thumb {
  outline: none;
}

.range::-webkit-slider-thumb {
  -webkit-appearance: none !important;
  border-radius: 100%;
  background-color: #705ec8;
  height: 18px;
  width: 18px;
  margin-top: -1px;
}

.range[disabled]::-webkit-slider-thumb {
  background-color: transparent;
  border: 1px solid #d7dbdd;
}

.range:active::-webkit-slider-thumb {
  outline: none;
}

.range::-ms-thumb {
  border-radius: 100%;
  background-color: #705ec8;
  height: 18px;
  width: 18px;
  border: none;
}

.range:active::-ms-thumb {
  border: none;
}
output {
  border: 1px solid #d7dbdd;
  color: #333;
  font-family: 'Lato', sans-serif;
  font-size: 12px;
  padding: .4em .6em;
  border-radius: 3px;
    margin-top:15px
}
    </style>
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
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
    <script src="{{ asset('assets/plugins/fileupload/js/dropify.js') }}"></script>



    <!-- INTERNAL Sweet alert js -->
    <script src="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>
    <script>

        $(document).on('click', '#add_new', function() {
            // window.addEventListener('load', function() {

            // }, false);
            $.ajax({
                url: "{{ route('aaas.create') }}",
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add ");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
        });

        var table = $('#data_table').DataTable({
            processing: true,
            serverSide: true,
            dom: 'lBftrip',
            buttons: ['copy', 'excel', 'pdf', 'colvis'],
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ ',
            },
            ajax: "{{ route('aaas.index') }}",
            columnDefs: [{
                orderable: false,
                className: 'select-checkbox',
                targets: 0
            }],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columns: [
                 {
                    'data': null,
                    'defaultContent': '',
                    'checkboxes': {


                        'selectRow': true
                    }
                },
                
                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
        });

        table.buttons().container()
            .appendTo('#data_table_wrapper .col-md-6:eq(0)');


                $(document).on('click', '.model-delete', function() {
        	var id = $(this).attr("data-id");
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this attribute!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    showCancelButton: true,


                }, function (willDelete) {
                    if (willDelete) {

                        $.ajax({
                            type: "DELETE",
                            url: '{{url("/")}}/aaas/' + id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
	                            swal({
	                				title: response.msg
	                			}, function (result) {
	                				location.reload();
	                			});
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
                    $(".modal-title").html("edit ");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                    $('.select-sub').trigger('change');
                }
            });
        });




        

        $(document).on('click', '.btn-delete', function() {
            $(this).parent().parent().parent().remove()
            generateNo()
        })

        $(document).on('change', '.select-sub', function() {

            var id = $(this).find(":selected").data('id')

            var path = "{{ url('/') }}/create-less/" + $(this).find(":selected").data('value') 

            if(id > 0){
                path = "{{ url('/') }}/edit-less/" + $(this).find(":selected").data('value') + '/'+ id 
            }
            // alert(path)
                     console.log(path);
            $.ajax({
                url: path,
                success: function(response) {
                     console.log(response);
                    $(".view-sub-form").html(response);
                    $('.dropify').dropify();
                }
            });
        });

    </script>
    @include('admin.aaas.include.multi')
@endsection



