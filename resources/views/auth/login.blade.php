@extends('layouts.master4')

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
                                            action="{{ route('login') }}">
                                            @csrf

                                            <div class="input-group mb-4">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-user"></i>
                                                    </div>
                                                </div>
                                                <label for="email"></label>
                                                <input id="email" type="text" name="email" class="form-control"
                                                    value="{{ old('email') }}" autofocus>
                                                <span id="password-error" class="error text-danger error-message">
                                                    @error('email')
                                                        {{ $message }}
                                                    @enderror
                                                </span>

                                            </div>

                                            <div class="input-group mb-4">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-lock"></i>
                                                    </div>
                                                </div>
                                                <label for="password"></label>
                                                <input id="password" type="password" name="password" class="form-control"
                                                    value="" autofocus>
                                                <span id="password-error" class="error text-danger error-message">
                                                    @error('password')
                                                        {{ $message }}
                                                    @enderror
                                                </span>


                                            </div>

                                            <div>
                                                <button type="submit" class="btn btn-primary btn-block px-4">Login</button>
                                            </div>
                                        </form>


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
