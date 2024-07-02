@extends('layouts.master')
@section('css')
		<!-- Data table css -->
		<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}"  rel="stylesheet">
		<link href="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
		<!-- Slect2 css -->
		<link href="{{URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css')}}" rel="stylesheet" />
		<link href="{{URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet" />

@endsection
@section('page-header')
						<!--Page header-->
						<div class="page-header">
							<div class="page-leftheader">
								<h4 class="page-title mb-0">Modules</h4>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
									<li class="breadcrumb-item active" aria-current="page"><a href="#">Modules</a></li>
								</ol>
							</div>
							<div class="page-rightheader">
								<div class="btn btn-list">
									<a href="{{route('module.create')}}" class="btn btn-info" data-toggle="tooltip" title="" data-original-title="Add new"><i class="fe fe-plus mr-1"></i> Add new </a>
								</div>
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
										<div class="card-title">Modules Data</div>
									</div>
									<div class="card-body">
										<div class="table-responsive">
											<table class="table table-bordered text-nowrap" id="module_table">
												<thead>
													<tr>
														<th width="100px">No.</th>
							                            <th>Name</th>
							                            <th>Description</th>
							                            <th width="200px">Action</th>
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
@endsection
@section('js')
		<!-- INTERNAL Data tables -->
		<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/dataTables.responsive.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js')}}"></script>
		<script src="{{URL::asset('assets/js/datatables.js')}}"></script>
		<script src="{{URL::asset('assets/js/popover.js')}}"></script>

		<!-- INTERNAL Sweet alert js -->
		<script src="{{URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js')}}"></script>
		<script src="{{URL::asset('assets/plugins/sweet-alert/sweetalert.min.js')}}"></script>
		<script src="{{URL::asset('assets/js/sweet-alert.js')}}"></script>
	

		<!-- INTERNAL Select2 js -->
		<script src="{{URL::asset('assets/plugins/select2/select2.full.min.js')}}"></script>

		<script type="text/javascript">
			var table = $('#module_table').DataTable({
	            processing: true,
	            serverSide: true,
	            ajax: "{{ route('module.index') }}",
	            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }],
	            order: [
	                [1, 'asc']
	            ]
	        });

	    function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        $(document).on('click', '.toggle-btn', function(e) {
            var audit_typeId = $(this).attr("data-id");
            var currentState = $(this).attr('data-state');

         	e.preventDefault();

            var toggleButton = $(this);
            swal({
                title: 'Confirm ' + currentState,
                text: 'Are you sure you want to ' + currentState + ' this module set?',
                icon: 'warning',
                showCancelButton: true,
				confirmButtonText: 'Confirm',
            }, function (value) {
				  if (value) {
	                $.ajax({
	                    url: '{{url("/")}}/module/' + audit_typeId + '/updateStatus',
	                    type: 'POST',
	                    data: {
	                        _token: '{{ csrf_token() }}',
	                        state: currentState
	                    },
	                    success: function(response) {
	                        // Toggle the button state
	                        if (currentState === 'disabled') {
	                            toggleButton.data('state', 'enabled');
	                            toggleButton.removeClass('btn-danger').addClass(
	                                'btn-success');
	                            toggleButton.text('Enable');
	                        } else {
	                            toggleButton.data('state', 'disabled');
	                            toggleButton.removeClass('btn-success').addClass(
	                                'btn-danger');
	                            toggleButton.text('Disable');
	                        }
	                    },
	                    error: function(xhr, status, error) {
	                    }
	                });
	            }
			});
        });

        $(document).on('click', '.delete-module', function() {
        	var id = $(this).attr("data-id");
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this module!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }, function (willDelete) {
                    if (willDelete) {
                       
                        $.ajax({
                            type: "GET",
                            url: '{{url("/")}}/remove_module/' + id,
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
		</script>
@endsection