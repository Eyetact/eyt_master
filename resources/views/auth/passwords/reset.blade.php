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
                                            <h1 class="mb-2">Reset Password</h1>
                                            <hr>
                                            <p class="text-muted">Reset your password</p>
                                        </div>

                                        <form id="reset_password" method="POST" class="form-horizontal form-simple"
                                              action="{{ route('password.update') }}" id="login">
                                            @csrf
                                            <input type="hidden" name="token" value="{{ $token }}">
                                            <div class="input-group mb-4">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fe fe-user"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control @error('password') is-invalid @enderror" placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="email" autofocus>
                                                @error('email')
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
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="{{ __('Password') }}">
                                                @error('password')
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
                                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" id="password_confirmation" placeholder="{{ __('Confirm Password') }}" value="{{ old('password_confirmation') }}" autocomplete="password_confirmation" autofocus>
                                                @error('password_confirmation')
                                                <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                @enderror
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <button type="submit" class="btn  btn-primary btn-block px-4">Reset Password</button>
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
<script>
{{--    @if($errors->any())--}}
{{--    toastr.error("{{ $errors->first() }}");--}}
{{--    {{ implode('', $errors->all('<div>:message</div>')) }}--}}
{{--    @endif--}}
    $(document).ready(function() {
        $("#reset_password").validate({
            ignore: ":hidden",
            rules: {
                email: {
                    required: true,
                    email: true,
                    maxlength: 250,
                },
                password: 'required',
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                }
            },
            messages: {
                email: {
                    required: "The Email field is required",
                    email: "Email must be a valid email",
                },
                password: {
                    required: "The Password field is required"
                },
                password_confirmation: {
                    required: "The Confirm Password field is required",
                    equalTo:"Confirm Password should be similar to Password"
                }
            },
            errorPlacement: function (error, element) {
                error.insertAfter($(element).parent());
            },
        });
    });
</script>
@endsection
