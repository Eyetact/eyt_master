@extends('layouts.master4')
@section('css')
@endsection
@section('content')
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
                                                <h1 class="mb-2">Login</h1>
                                                <hr>
                                                <p class="text-muted">Sign In to your account</p>
                                            </div>
                                            <form method="POST" class="form-horizontal form-simple"
                                            action="{{ route('login') }}" id="login">
                                            @csrf
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-user"></i>
                                                        </div>
                                                    </div>
                                                    <input type="text" class="form-control" placeholder="Email" name="email" id="user-name">
                                                    @error('login')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                                <div class="input-group mb-4">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fe fe-lock"></i>
                                                        </div>
                                                    </div>
                                                    <input type="password" class="form-control" placeholder="Password" name="password" id="user-password" >
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn  btn-primary btn-block px-4">Login</button>
                                                    </div>
                                                    <div class="col-12 text-center">
                                                        <a href="{{ route('password.request') }}" class="btn btn-link box-shadow-0 px-0">Forgot password?</a>
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
@endsection
@section('js')
<!-- <script>
    $(document).ready(function() {
        $("#login").validate({
            ignore: ":hidden",
            rules: {
                login: {
                    required: true,
                    //email: true,
                    maxlength: 250,
                },
                password: 'required'
            },
            messages: {
                login: {
                    required: "The Email field is required",
                    //email: "Email must be a valid email",
                },
                password: {
                    required: "The Password field is required"
                }
            },
        });
    });
</script> -->
@endsection
