@extends('layouts.master')
@section('css')

@endsection
@section('page-header')
	<!--Page header-->
	<div class="page-header">
		<div class="page-leftheader">
			<h4 class="page-title mb-0">Modules</h4>
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>Settings</a></li>
				<li class="breadcrumb-item active" aria-current="page"><a href="#">Add Modules</a></li>
			</ol>
		</div>
	</div>
    <!--End Page header-->
@endsection
@section('content')

			<form action="{{ $module->id == null ? route('module.store') : route('module.update', ['module' => $module->id]) }}" id="moduleCreate" method="POST" autocomplete="off">
				@csrf
				<div class="row">
					<div class="col-lg-12 col-md-12">
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">Basics</h3>
							</div>
							<div class="card-body pb-2">
			                    <div class="row">
			                        <div class="col-sm-12 form-group">
										<label class="form-label" for="name">Name <span class="text-red">*</span></label>
										<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter name" value="{{ old('name', $module->name) }}">
			                            <label id="name-error" class="error text-red hide" for="name"></label>
			                            @error('name')
			                                <span class="error name-error">{{ $message }}</span>
			                            @enderror
			                        </div>
			                        <div class="form-group col-sm-12">
			                            <label class="form-label" for="description">Description</label>
			                            <textarea class="form-control" name="description" autocomplete="off" id="description"
			                                placeholder="Enter Description" rows="3">{{ old('description', $module->description) }}</textarea>
			                        </div>
			                    </div>
							</div>
							<div class="card-footer text-right">
								<input title="Save module" class="btn btn-primary" type="submit"
			                        value="{{ $module->id == null ? 'Create' : 'Update' }}">
			                    <input title="Reset form" class="btn btn-warning" type="reset" value="Reset">
			                    <a title="Cancel form" href="{{ route('module.index') }}" class="btn btn-danger">Cancel</a>
							</div>
						</div>
					</div>
				</div>

			</form>
			<!-- End Row -->

		</div>
	</div><!-- end app-content-->
</div>
@endsection
@section('js')
	<!--INTERNAL Select2 js -->
	<!-- <script src="{{ asset('assets/js/jquery.validate.js') }}"></script> -->
	<script>
	    $('#moduleCreate').validate({
	        rules: {
	            name: {
	                required: true,
	            }
	        },
	        messages: {
	            name: {
	                required: "Please enter name.",
	            }
	        }
	    });
    </script>
@endsection
