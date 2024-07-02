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
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Data Manager</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Data Manager</a></li>
            </ol>
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
                    <div class="card-title">Data Manager</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('export-template') }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 input-box">
                                <label class="form-label" for="module">Module<span class="text-red">*</span></label>
                                <div class="row">
                                    <div class="col-9">
                                <select name="module" class="google-input module" id="module" required="">
                                    <option value="" selected="">Select Module</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach

                                </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary" style="height: 50px;" > Download Template </button>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </form>


                    <form method="POST" action="{{ route('export-data') }}">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 input-box">
                                <label class="form-label" for="module">Module<span class="text-red">*</span></label>
                                <div class="row">
                                    <div class="col-9">
                                <select name="module" class="google-input module" id="module" required="">
                                    <option value="" selected="">Select Module</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach

                                </select>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary" style="height: 50px;" > Download data </button>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </form>

                    <form method="POST" action="{{ route('import-data') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 input-box">
                                <label class="form-label" for="module">Module<span class="text-red">*</span></label>
                                <div class="row">
                                    <div class="col-4">
                                <select name="module" class="google-input module" id="module" required="">
                                    <option value="" selected="">Select Module</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                                    @endforeach

                                </select>
                                    </div>
                                    <div class="col-5">
                                        <div class="input-group file-browser mb-5">
											<input type="text" class="form-control border-right-0 browse-file" placeholder="choose" readonly="">
											<label class="input-group-btn">
												<span class="btn btn-primary">
													Browse <input type="file" style="display: none;" multiple="" name="file">
												</span>
											</label>
										</div>
                                    </div>
                                    <div class="col-md-3">
                                        <button type="submit" class="btn btn-primary" style="height: 50px;" > Import Data </button>
                                    </div>
                                </div>
                            </div>



                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

    </div>
    </div><!-- end app-content-->
    </div>

    <div class="modal fade bd-example-modal-xl" id="role_form_modal" tabindex="-1" role="dialog"
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
