@extends('layouts.app')

@section('title')
    Permissions
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/datatable/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/extensions/rowReorder.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/tables/extensions/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/css/forms/selects/select2.min.css') }}">
    <style>
        .permissionLabel{
            float: left;
            width: 100%;
        }
        .add-module, .add-module:focus{
            border: none;
            outline: none;
        }
        .permission {
            display: block;
        }
        .permission .permissionInput{
            width: 80%;
            float: left;
            margin-right: 20px;
        }
        .permission #add, .btn-remove{
            float: right;
            width: 95px;
        }
        .each-input{
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            margin-bottom: 15px;
        }
        .select2-search__field {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')

<div class="row company_module">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-head">
                <div class="card-header">
                    <h4 class="inline">Edit Permission</h4>
                </div>
            </div>
            <div class="card-body">
                <form
                    action="{{ route('permission.update') }}"
                    method="POST" id="permission_form" novalidate="" class="needs-validation">
                    <div class="card-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="name">Permission</label>
                                <div class="permission">
                                @foreach($permission as $key => $value)
                                    @php $selectedModule = $value->module; @endphp
                                    <div class="each-input">
                                        <input class="nameInput permissionInput form-control @error('name') is-invalid @enderror" name="name[{{$key}}]" type="text" data-id="{{$value->id}}" placeholder="Enter permission name" value="{{ $value->name }}">
                                        <input class="" name="id[{{$key}}]" type="hidden" data-id="{{$value->id}}" placeholder="Enter permission name" value="{{ $value->id }}">
                                        @if($key === 0)
                                            <button type="button" name="add" id="add" class="btn btn-success">Add More</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-remove remove-permission" data-id="{{$value->id}}" >Remove</button>
                                        @endif
                                    </div>
                                @endforeach
                                <div class="append-list"></div>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter name.</div>
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="module">Module</label>
                                <select class="form-control custom-select @error('module') is-invalid @enderror" name="module" id="moduleId">
                                    @if(count($moduleList))
                                        @foreach ($moduleList as $module)
                                            <option value="{{ $module->id }}" {{ isset($value) && $module->id==$value->module ? 'selected' : ''}}> {{ $module->name }} </option>
                                        @endforeach
                                    @else
                                        <option disabled value="No module found selected">No module found selected</option>
                                    @endif
                                </select>
                                @error('module')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="valid-feedback">Looks good!</div>
                                <div class="invalid-feedback">Please enter module.</div>
                            </div>
                            <input type="hidden" name="guard_name" value="web">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <input class="btn btn-primary update-permission" type="submit" value="Update">
                        <a href="{{ route('permission.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        let Testcount = $(".permission input.nameInput:last").attr("name");
        let count = parseInt(Testcount.slice(5,6)) + 1;
        console.log(Testcount);
        // alert(count);
        $('#add').on('click', function(){

            let rules = '';
            let appendHtml = '<div class="each-input"> <input class="permissionInput form-control" name="name['+count+']" type="text" placeholder="Enter permission name"> <input class="permissionInput form-control" name="id['+count+']" type="hidden" placeholder="Enter permission name"> <button type="button" class="btn btn-danger btn-remove">Remove</button> </div>';
            $('.append-list').append(appendHtml);
            rules = {
                required: true,
                maxlength: 250,
                messages: {
                    required: 'The Permission field is required'
                }
            };

            $('.append-list').find("[name='name["+count+"]']").rules('add', rules);
            count++;
        });

        $('.btn-remove').on('click', function(){
            $(this).parent('.each-input').remove();
        });

        $('.remove-permission').on('click', function(){
            let id = $(this).data("id");
            $.ajax({
                type:'POST',
                url:"{{ route('permission.delete') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'id': id},
                success: function(data) {
                    toastr.success(data.msg);
                },
                error: function(data){
                    console.log('error while deleting')
                }
            });
        });

        $('.update-permission').on('click', function(){
            let permissionData = [];
            $('.each-input').each(function(i, obj) {
                let permissionName = $(obj).find('input').val();
                let permissionId  = $(obj).find('input').data("id");
                permissionData.push([permissionId, permissionName]);
            });
        });
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
