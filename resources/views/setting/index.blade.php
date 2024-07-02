@extends('layouts.master')
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Hi! Welcome Back</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/' . ($page = '#')) }}"><i
                            class="fe fe-home mr-2 fs-14"></i>Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('/' . ($page = '#')) }}">Settings</a>
                </li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
@endsection
@push('styles')
    <!-- INTERNAL File Uploads css -->
    <link href="{{ asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css-->
    <link href="{{ asset('assets/plugins/fileupload/css/fileupload.css') }}" rel="stylesheet" type="text/css" />

    <!-- INTERNAL Select2 css -->
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />

    <!-- INTERNAL Sumoselect css-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sumoselect/sumoselect.css') }}    ">
@endpush
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form action="{{ route('setting.store') }}" id="SettingForm" method="POST" enctype="multipart/form-data">
                @csrf
                <!--BASIC SETTINGS-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">BASIC SETTINGS</h3>
                    </div>
                    <div class=" card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-12">
                                <label class="form-label">Logo</label>
                                <input type="file" class="dropify" name="logo"
                                    data-default-file="{{ $data->logo ? asset('uploads/logo/' . $data->logo) : '' }}"
                                    data-height="180" />
                            </div>

                            <div class="col-lg-3 col-sm-12">
                                <label class="form-label">Dark Logo</label>
                                <input type="file" class="dropify" name="dark_logo"
                                    data-default-file="{{ $data->dark_logo ? asset('uploads/logo/' . $data->dark_logo) : '' }}"
                                    data-height="180" />
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <label class="form-label">Footer Logo</label>
                                <input type="file" class="dropify" name="footer_logo"
                                    data-default-file="{{ $data->footer_logo ? asset('uploads/footer_logo/' . $data->footer_logo) : '' }}"
                                    data-height="180" />
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <label class="form-label">Web Icon</label>
                                <input type="file" class="dropify" name="web_icon"
                                    data-default-file="{{ $data->web_icon ? asset('uploads/web_icon/' . $data->web_icon) : '' }}"
                                    data-height="180" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Copyright Text</label>
                                    <input type="text" class="google-input" name="copyright_text" id="copyright_text"
                                        value="{{ $data->copyright_text ?? '' }}" />
                                </div>
                                @error('copyright_text')
                                    <label id="copyright_text-error" class="error"
                                        for="copyright_text">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Footer Text</label>
                                    <input type="text" name="footer_text" class="google-input" id="footer_text"
                                        value="{{ $data->footer_text ?? '' }}">
                                </div>
                                @error('footer_text')
                                    <label id="footer_text-error" class="error" for="footer_text">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Meta Keywords</label>
                                    <input class="google-input" type="text" id="meta_keywords" name="meta_keywords"  value="{{ $data->meta_keywords ?? '' }}" />
                                </div>
                                @error('meta_keywords')
                                    <label id="meta_keywords-error" class="error"
                                        for="meta_keywords">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Meta Description</label>

                                    <input type="text" class="google-input" id="meta_description" name="meta_description"
                                         value="{{ $data->meta_description ?? '' }}" />
                                </div>
                                @error('meta_description')
                                    <label id="meta_description-error" class="error"
                                        for="meta_description">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Logo Text</label>
                                    <input type="text" name="logo_text" id="logo_text" class="google-input"
                                        value="{{ $data->logo_text ?? '' }}">
                                </div>
                                @error('logo_text')
                                    <label id="logo_text-error" class="error" for="logo_text">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                    </div>
                </div>

                <!--OUTGOING MAIL (SMTP) SERVER SETTINGS-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">OUTGOING MAIL (SMTP) SERVER SETTINGS</h3>
                    </div>
                    <div class=" card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Email</label>
                                    <input type="text" class="google-input" name="email" id="email"
                                        value="{{ old('email', $data->email) }}" />
                                </div>
                                @error('email')
                                    <label id="email-error" class="error" for="email">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Password</label>
                                    <input type="password" name="password" class="google-input" id="password"
                                        value="{{ old('password', $data->password) }}">
                                </div>
                                @error('password')
                                    <label id="password-error" class="error" for="password">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Server (Host)</label>
                                    <input type="text" name="mail_server" id="mail_server" class="google-input"
                                        value="{{ old('mail_server', $data->server) }}">
                                </div>
                                @error('mail_server')
                                    <label id="mail_server-error" class="error"
                                        for="mail_server">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Port</label>
                                    <input type="tel" name="port" id="port" class="google-input"
                                        value="{{ old('port', $data->port) }}">
                                </div>
                                @error('port')
                                    <label id="port-error" class="error" for="port">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                        <select id="encryption_mode" class="google-input" name="encryption_mode">
                                            <option selected disabled>Select Encryption Mode</option>

                                            <option value="tls" {{ $data->encryption == 'tls' ? 'selected' : '' }}>TLS
                                            </option>
                                            <option value="ssl" {{ $data->encryption == 'ssl' ? 'selected' : '' }}>SSL
                                            </option>
                                        </select>
                                        @error('encryption_mode')
                                            <label id="encryption_mode-error" class="error"
                                                for="encryption_mode">{{ $message }}</label>
                                        @enderror

                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!--SOCIAL MEDIA SETTINGS-->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">SOCIAL MEDIA SETTINGS</h3>
                    </div>
                    <div class=" card-body">
                        <div class="repeater-default">
                            <div data-repeater-list="social">
                                @if (isset($socialMediaData) && $socialMediaData->count() > 0)
                                    @foreach ($socialMediaData as $socialMedia)
                                        <div data-repeater-item>
                                            <div class="form row">
                                                <input type="hidden" name="id" value="{{ $socialMedia->id }}">
                                                <div class="col-sm-6 col-md-6">
                                                    {{-- <input type="file" class="dropify" name="icon"
                                                        data-default-file="{{ filled($socialMedia->icon) ? asset('uploads/socialmedia/' . $socialMedia->icon) : '' }}"
                                                        data-height="180" /> --}}
                                                        <div class="input-box">
                                                        <select class="google-input" name="icon">
                                                            <option value="">-- select network</option>
                                                            <option @if( $socialMedia->icon == 'Facebook' ) selected @endif value="Facebook">Facebook</option>
                                                            <option @if( $socialMedia->icon == 'YouTube' ) selected @endif value="YouTube">YouTube</option>
                                                            <option @if( $socialMedia->icon == 'WhatsApp' ) selected @endif value="WhatsApp">WhatsApp</option>
                                                            <option @if( $socialMedia->icon == 'Instagram' ) selected @endif value="Instagram">Instagram</option>
                                                            <option @if( $socialMedia->icon == 'WeChat' ) selected @endif value="WeChat">WeChat</option>
                                                            <option @if( $socialMedia->icon == 'TikTok' ) selected @endif value="TikTok">TikTok</option>
                                                            <option @if( $socialMedia->icon == 'Telegram' ) selected @endif value="Telegram">Telegram</option>
                                                            <option @if( $socialMedia->icon == 'Snapchat' ) selected @endif value="Snapchat">Snapchat</option>
                                                            <option @if( $socialMedia->icon == 'X' ) selected @endif value="X">X (formerly Twitter)</option>
                                                        </select>
                                                    @error('icon')
                                                        <label id="title-error" class="error"
                                                            for="title">{{ $message }}</label>
                                                    @enderror
                                                    </div>
                                                </div>
                                                {{-- <div class="col-sm-4 col-md-4 mt-8">
                                                    <div class="input-box">
                                                        <label class="input-label">Title</label>
                                                        <input type="text" class="google-input" name="title"
                                                            id="title"
                                                            value="{{ old('title', $socialMedia->title) }}" />
                                                    </div>
                                                    @error('title')
                                                        <label id="title-error" class="error"
                                                            for="title">{{ $message }}</label>
                                                    @enderror
                                                </div> --}}
                                                <div class="col-sm-3 col-md-3 ">
                                                    <div class="input-box">
                                                        <label class="input-label">URL</label>
                                                        <input type="text" name="url" class="google-input"
                                                            id="url" value="{{ old('url', $socialMedia->url) }}">
                                                    </div>
                                                    @error('url')
                                                        <label id="url-error" class="error"
                                                            for="url">{{ $message }}</label>
                                                    @enderror
                                                </div>
                                                <div class="form-group col-sm-1">
                                                    <label class="custom-switch form-label mt-5">
                                                        <input type="checkbox" name="active" class="custom-switch-input" id="active" {{ $socialMedia->active == 1 ? 'checked' : '' }}>
                                                        <span class="custom-switch-indicator"></span>
                                                        <span class="custom-switch-description">Active</span>
                                                    </label>
                                                </div>
                                                <div class="col-sm-1 col-md-2 text-center"
                                                    style="margin-top: 5px!important;">
                                                    <button type="button" class="btn btn-danger"
                                                        data-repeater-delete=""><i class="feather icon-x"></i> Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div data-repeater-item>
                                        <div class="form row">
                                            <div class="col-sm-6 col-md-6 ">
                                                {{-- <input type="file" class="dropify" name="icon"
                                                    data-default-file="{{ isset($socialMedia) && filled($socialMedia->icon) ? asset('uploads/socialmedia/' . $socialMedia->icon) : '' }}"
                                                    data-height="180" /> --}}
                                                    <div class="input-box">
                                                        <select class="google-input" name="icon">
                                                            <option value="">-- select network</option>
                                                            <option value="Facebook">Facebook</option>
                                                            <option value="YouTube">YouTube</option>
                                                            <option value="WhatsApp">WhatsApp</option>
                                                            <option value="Instagram">Instagram</option>
                                                            <option value="WeChat">WeChat</option>
                                                            <option value="TikTok">TikTok</option>
                                                            <option value="Telegram">Telegram</option>
                                                            <option value="Snapchat">Snapchat</option>
                                                            <option value="X">X (formerly Twitter)</option>
                                                        </select>
                                            </div>
                                        </div>
                                            {{-- <div class="col-sm-4 col-md-4 mt-8">
                                                <div class="input-box">
                                                    <label class="input-label">Title</label>
                                                    <input type="text" class="google-input" name="title"
                                                        id="title"
                                                        value="{{ old('title', isset($socialMedia) && filled($socialMedia->title) ? $socialMedia->title : '') }}" />
                                                </div>
                                                @error('copyright_text')
                                                    <label id="title-error" class="error"
                                                        for="title">{{ $message }}</label>
                                                @enderror
                                            </div> --}}
                                            <div class="col-sm-3 col-md-3 ">
                                                <div class="input-box">
                                                    <label class="input-label">URL</label>
                                                    <input type="text" name="url" class="google-input"
                                                        id="url"
                                                        value="{{ old('url', isset($socialMedia) && filled($socialMedia->title) ? $socialMedia->url : '') }}">
                                                </div>
                                                @error('url')
                                                    <label id="url-error" class="error"
                                                        for="url">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            <div class="form-group col-sm-1">
                                                <label class="custom-switch form-label mt-5">
                                                    <input type="checkbox" name="active" class="custom-switch-input" id="active" checked>
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Active</span>
                                                </label>
                                            </div>
                                            <div class="col-sm-1 col-md-2 text-center"
                                                style="margin-top: 5px!important;">
                                                <button type="button" class="btn btn-danger" data-repeater-delete=""><i
                                                        class="feather icon-x"></i> Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group overflow-hidden">
                                <div class="col-12">
                                    <button type="button" data-repeater-create class="btn btn-primary btn-lg">
                                        <i class="icon-plus4"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!---->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">CONTACT US SETTINGS</h3>
                    </div>
                    <div class=" card-body">
                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Email</label>
                                    <input type="text" class="google-input" name="contact_email" id="contact_email"
                                        value="{{ old('contact_email', $contactSettingData->email) }}" />
                                </div>
                                @error('contact_email')
                                    <label id="contact_email-error" class="error"
                                        for="contact_email">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Contact Number</label>
                                    <input type="text" name="contact_phone" id="contact_phone" class="google-input"
                                        value="{{ old('contact_phone', $contactSettingData->phone) }}">
                                </div>
                                @error('contact_phone')
                                    <label id="contact_phone-error" class="error"
                                        for="contact_phone">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <textarea class="google-input" name="adress" id="adress" rows="1" placeholder="Address">{{ old('adress', $contactSettingData->address) }}</textarea>
                                </div>
                                @error('adress')
                                    <label id="adress-error" class="error" for="adress">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Latitude</label>
                                    <input type="tel" name="latitude" id="latitude" class="google-input"
                                        value="{{ old('latitude', $contactSettingData->latitude) }}">
                                </div>
                                @error('latitude')
                                    <label id="latitude-error" class="error" for="latitude">{{ $message }}</label>
                                @enderror
                            </div>
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Longitude</label>
                                    <input type="tel" name="longitude" id="longitude" class="google-input"
                                        value="{{ old('longitude', $contactSettingData->longitude) }}">
                                </div>
                                @error('longitude')
                                    <label id="longitude-error" class="error" for="longitude">{{ $message }}</label>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Save Settings</button>
                        <button class="btn btn-secondary" type="reset" data-bs-dismiss="modal"
                            aria-label="Close">Cancel</button>
                    </div>
                </div>



            </form>
            @can('assigne.domain')
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Assigned domain </h3>
                </div>
                <form action="{{ route('setting.store.url') }}" id="urlForm" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class=" card-body">

                        <div class="row">
                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">URL</label>
                                    <input type="text" class="google-input" name="main_url" id="main_url"
                                        value="{{ $data->url }}">
                                </div>
                            </div>

                            <div class="col-sm-6 col-md-6">
                                <div class="input-box">
                                    <label class="input-label">Path</label>
                                    <input type="text" class="google-input" name="main_path" id="main_path"
                                        value="{{ $data->path }}">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit">Save Settings</button>

                    </div>
                </form>

            </div>
            @endcan
        </div>
    </div>
    <!-- End Row-->
@endsection

@push('script')
    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- INTERNAL File uploads js -->
    <script src="{{ asset('assets/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/filupload.js') }}"></script>

    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!--INTERNAL Form Advanced Element -->
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>

    <!-- INTERNAL Select2 js -->
    <script src="{{ asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('assets/js/form-repeater.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#SettingForm").validate({
                onkeyup: function(el, e) {
                    $(el).valid();
                },
                // errorClass: "invalid-feedback is-invalid",
                // validClass: 'valid-feedback is-valid',
                ignore: ":hidden",
                rules: {
                    logo: {
                        required: true,
                    },
                    footer_logo: {
                        required: true,
                    },
                    web_icon: {
                        required: true,
                    },
                    copyright_text: {
                        required: true,
                    },
                    footer_text: {
                        required: true,
                    },
                    meta_keywords: {
                        required: true,
                    },
                    meta_description: {
                        required: true,
                    },
                    logo_text: {
                        required: true,
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                    },
                    password: {
                        required: true,
                    },
                    mail_server: {
                        required: true,
                    },
                    port: {
                        required: true,
                    },
                    encryption_mode: {
                        required: true,
                    },
                    icon: {
                        required: true,
                    },
                    title: {
                        required: true,
                    },
                    url: {
                        required: true,
                        url: true,
                    },
                    contact_email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                    },
                    contact_phone: {
                        required: true,
                    },
                    adress: {
                        required: true,
                    },
                    latitude: {
                        required: true,
                    },
                    longitude: {
                        required: true,
                    },
                },
                messages: {},
                errorPlacement: function(error, element) {
                    error.insertAfter($(element).parent());
                },
            });

            $("[name='logo']").rules('remove');
            $("[name='footer_logo']").rules('remove');
            $("[name='web_icon']").rules('remove');
        });
    </script>
@endpush
