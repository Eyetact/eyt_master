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
            <h4 class="page-title mb-0">{{ $mailbox->id == null ? 'Create' : 'Edit' }} Mailbox Settings</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ route('smtp.index') }}">Mailbox Settings</a></li>
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
    @push('styles')
    <!-- INTERNAL Sumoselect css-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sumoselect/sumoselect.css') }}    ">
@endpush
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $mailbox->id == null ? 'Create' : 'Edit' }} Mailbox Settings</h3>
        </div>
        <div class="card-body pb-2">
        <form
action="{{ $mailbox->id == null ? route('main_mailbox.store') : route('main_mailbox.update', ['main_mailbox' => $mailbox->id]) }}"
method="POST" id="mailboxForm" >
        @csrf
            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="mailer_id" class="input-label">Mailbox ID</label>
                        <input type="text" class="google-input" name="mailer_id"
                               id="mailer_id" value="{{ old('mailer_id', $mailbox->mailer_id) }}"/>
                    </div>
                    @error('mailer_id')
                    <label id="mailer_id-error" class="error"
                           for="mailer_id">{{ $message }}</label>
                    @enderror
                </div>
                <div class="col-sm-6 col-md-6">
                    <div class="input-box">
                        <label for="mailbox_name" class="input-label">Mailbox Name</label>
                        <input type="text" class="google-input" name="mailbox_name"
                               id="mailbox_name" value="{{ old('mailbox_name', $mailbox->mailbox_name) }}"/>
                    </div>
                    @error('mailbox_name')
                    <label id="mailbox_name-error" class="error"
                           for="mailbox_name">{{ $message }}</label>
                    @enderror
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
                        <label for="imap" class="input-label">Mailer ID (IMAP)</label>
                        <div class="col-md-12">
                            <select class="SlectBox form-control" name="imap" id="imap"
                                    data-placeholder="Select Mailer ID">
                                <option value="">Select</option>
                                @foreach ($smtps as $smtp)
                                    <option value="{{ $smtp->id }}"
                                        {{ isset($mailbox->imap) && $mailbox->imap == $smtp->id ? 'selected' : '' }}>
                                        {{ $smtp->mailer_id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('imap')
                            <label id="imap-error" class="error" for="imap">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
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
                        <label for="imap" class="input-label">Mailer ID (SMTP)</label>
                        <div class="col-md-12">
                            <select class="SlectBox form-control" name="smtp" id="smtp"
                                    data-placeholder="Select Mailer ID">
                                <option value="">Select</option>
                                @foreach ($smtps as $smtp)
                                    <option value="{{ $smtp->id }}"
                                        {{ isset($mailbox->smtp) && $mailbox->smtp == $smtp->id ? 'selected' : '' }}>
                                        {{ $smtp->mailer_id }}
                                    </option>
                                @endforeach
                            </select>
                            @error('smtp')
                            <label id="smtp-error" class="error" for="smtp">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary" type="submit">{{ $mailbox->id == null ? 'Save' : 'Update' }}</button>
                <a href="{{ route('main_mailbox.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
    </div>
@endsection
@push('script')
    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
    <script type="text/javascript">
        // $(document).ready(function() {
        $("#mailboxForm").validate({
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
                mailbox_name:{
                    required:true,
                },
                imap:{
                    required:true,
                },
                smtp:{
                    required:true,
                },
                // option_id:{
                //     required:true,
                // },
                // group_id:{
                //     required:true,
                // },
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
