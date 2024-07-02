@extends('layouts.master4')
@section('css')
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="page">
        <div class="page-single">
            <div class="container">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center title-style mb-6">
                                            <h1 class="mb-2">Forgot Password</h1>
                                            <hr>
                                            <p class="text-muted">Forgot your password</p>
                                        </div>
                                        <form method="POST" action="{{ route('password.email') }}" class="form-horizontal form-simple" id="forgot_password">
                                            @csrf
                                            <div class="input-group mb-4">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-user"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" placeholder="Email" name="email" id="user-name">
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                                @error('login')
                                                <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn  btn-primary btn-block px-4">Forgot Password</button>
                                                </div>
                                                <div class="col-12 text-center">
                                                    <a href="{{ route('login') }}" class="btn btn-link box-shadow-0 px-0">Login</a>
                                                </div>
                                            </div>
                                        </form>
                                        {{-- <div class="text-center pt-4">
                                            <div class="font-weight-normal fs-16">You Don't have an account <a class="btn-link font-weight-normal" href="#">Register Here</a></div>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Content-->


@endsection
@section('js')
<script>
    @if (session('status'))
        toastr.success("{{session('status')}}");
    @endif
    $(document).ready(function() {
        console.log('aa');
        $("#forgot_password").validate({
            onkeyup: function(el, e) {
                $(el).valid();
            },
            // errorClass: "invalid-feedback is-invalid",
            // validClass: 'valid-feedback is-valid',
            ignore: ":hidden",
            rules: {
                email: {
                    required: true,
                    email: true,
                    maxlength: 250,
                }
            },
            messages: {
                email: {
                    required: "The Email field is required",
                    email: "Email must be a valid email",
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter($(element).parent());
            },
        });
    });
</script>
@endsection
