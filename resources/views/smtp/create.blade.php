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

    <!-- INTERNAL Sumoselect css-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sumoselect/sumoselect.css') }}    ">
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">{{ $smtp->id == null ? 'Create' : 'Edit' }} Swift Mailer</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('smtp.index') }}">SMTP Settings</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
{{--                <a href="{{ route('smtp.create') }}" class="btn btn-info" title="" data-original-title="Add new"><i class="fe fe-plus mr-1"></i> Add Swift Mailer </a>--}}
            </div>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
<form action="{{ $smtp->id == null ? route('smtp.store') : route('smtp.update', ['smtp' => $smtp->id]) }}" method="POST"
      id="smtpForm" novalidate="" >
@csrf
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $smtp->id == null ? 'Create' : 'Edit' }} Swift Mailer</h3>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="mailer_id" class="input-label">Mailer ID</label>
                        <input type="text" class="google-input" name="mailer_id"
                               id="mailer_id" value="{{ old('mailer_id', $smtp->mailer_id) }}"/>
                    </div>
                    @error('mailer_id')
                    <label id="mailer_id-error" class="error"
                           for="mailer_id">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="transport_type" class="input-label">Mailer ID (IMAP)</label>
                        <div class="col-md-12">
                            <select class="SlectBox form-control" name="transport_type" id="transport_type"
                                    data-placeholder="Select Transport Type">
                                <option value="">Select</option>
                                <option value="gmail" {{ $smtp->transport_type == 'gmail' ? 'selected' : '' }}>Gmail</option>
                                <option value="yahoo" {{ $smtp->transport_type == 'yahoo' ? 'selected' : '' }}>Yahoo</option>
                                <option value="smtp" {{ $smtp->transport_type == 'smtp' ? 'selected' : '' }}>Custom</option>
                            </select>
                            @error('transport_type')
                            <label id="transport_type-error" class="error" for="transport_type">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="email" class="input-label">Email</label>
                        <input type="text" class="google-input" name="email"
                               id="email" value="{{ old('email', $smtp->email) }}"/>
                    </div>
                    @error('email')
                    <label id="email-error" class="error"
                           for="email">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="password" class="input-label">Password</label>
                        <input type="text" class="google-input" name="password"
                               id="password" value="{{ old('password', $smtp->password) }}"/>
                    </div>
                    @error('password')
                    <label id="password-error" class="error"
                           for="password">{{ $message }}</label>
                    @enderror
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <div class="form-heading">
                <h5>Outgoing Mail (SMTP) Server</h5>
                <span>Select the SwiftMailer config to use for sending emails through your account.</span>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="mail_server" class="input-label">Server (Host)</label>
                        <input type="text" class="google-input" name="mail_server"
                               id="mail_server" value="{{ old('mail_server', $smtp->mail_server) }}"/>
                    </div>
                    @error('mail_server')
                    <label id="mail_server-error" class="error"
                           for="mail_server">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="port" class="input-label">Port</label>
                        <input type="text" class="google-input" name="port"
                               id="port" value="{{ old('port', $smtp->port) }}"/>
                    </div>
                    @error('port')
                    <label id="port-error" class="error"
                           for="port">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12 form-label">Encryption Mode</label>
                        <div class="col-md-12">
                            <select id="encryption_mode" class="SlectBox form-control" name="encryption_mode" data-placeholder="Select Encryption Mode">
                                <option value="">Select</option>
                                <option value="tls" {{ $smtp->encryption_mode == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $smtp->encryption_mode == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                            @error('encryption_mode')
                            <label id="encryption_mode-error" class="error" for="encryption_mode">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <div class="form-heading">
                <h5>Incoming Mail (IMAP) Server</h5>
                <span>Configure your imap settings in order to fetch new mail from your inbox.</span>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="imap_mail_server" class="input-label">Server (Host)</label>
                        <input type="text" class="google-input" name="imap_mail_server"
                               id="imap_mail_server" value="{{ old('imap_mail_server', $smtp->imap_mail_server) }}"/>
                    </div>
                    @error('imap_mail_server')
                    <label id="imap_mail_server-error" class="error"
                           for="imap_mail_server">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="imap_port" class="input-label">imap_Port</label>
                        <input type="text" class="google-input" name="imap_port"
                               id="imap_port" value="{{ old('imap_port', $smtp->imap_port) }}"/>
                    </div>
                    @error('imap_port')
                    <label id="imap_port-error" class="error"
                           for="imap_port">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="form-group row">
                        <label class="col-md-12 form-label">Encryption Mode</label>
                        <div class="col-md-12">
                            <select id="imap_encryption_mode" class="SlectBox form-control" name="imap_encryption_mode" data-placeholder="Select Encryption Mode">
                                <option value="">Select</option>
                                <option value="tls" {{ $smtp->imap_encryption_mode == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ $smtp->imap_encryption_mode == 'ssl' ? 'selected' : '' }}>SSL</option>
                            </select>
                            @error('imap_encryption_mode')
                            <label id="imap_encryption_mode-error" class="error" for="imap_encryption_mode">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-4 mb-4">
            <div class="row ">
                <div class="col-sm-6 col-md-6 hide">
                    <div class="input-box">
                        <label for="sender_address" class="input-label">Sender Address</label>
                        <input type="text" class="google-input" name="sender_address"
                            id="sender_address" value="{{ old('sender_address', $smtp->sender_address) }}"/>
                    </div>
                    @error('sender_address')
                    <label id="sender_address-error" class="error"
                        for="sender_address">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6 hide">
                    <div class="input-box">
                        <label for="delivery_address" class="input-label">Delivery Address</label>
                        <input type="text" class="google-input" name="delivery_address"
                            id="delivery_address" value="{{ old('delivery_address', $smtp->delivery_address) }}"/>
                    </div>
                    @error('delivery_address')
                    <label id="delivery_address-error" class="error"
                        for="delivery_address">{{ $message }}</label>
                    @enderror
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary" type="submit">{{ $smtp->id == null ? 'Save' : 'Update' }}</button>
            <a href="{{ route('smtp.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection
@push('script')
    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
<script type="text/javascript">
    // $(document).ready(function() {
    $("#smtpForm").validate({
        onkeyup: function(el, e) {
            $(el).valid();
        },
        // errorClass: "invalid-feedback is-invalid",
        // validClass: 'valid-feedback is-valid',
        ignore: ":hidden",
        rules: {
            mailer_id: {
                required: true,
            },
            transport_type:{
                required:true,
            },
            email:{
                required:true,
                email: true,
            },
            password:{
                required:true,
            },
            mail_server:{
                required:true,
            },
            port:{
                required:true,
                number:true,
            },
            encryption_mode:{
                required:true,
            },
            imap_mail_server:{
                required:true,
            },
            imap_port:{
                required:true,
                number:true,
            },
            imap_encryption_mode:{
                required:true,
            },
            sender_address:{
                required:true,
            },
            delivery_address:{
                required:true,
            }
        },
        messages: {
        },
        errorPlacement: function (error, element) {
            error.insertAfter($(element).parent());
        },
    });
    $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").on( "keyup", function() {
        var $input = $(this);
        if($input.val() != ''){
            $input.parents(".input-box").addClass("focus");
        }else{
            $input.parents(".input-box").removeClass("focus");
        }
    } );
    $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").each(function(index, element) {
        var value = $(element).val();
        if(value != ''){
            $(this).parents('.input-box').addClass('focus');
        }else{
            $(this).parents('.input-box').removeClass('focus');
        }
    });
    // });
</script>
@endpush
