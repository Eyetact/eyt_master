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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Hi! Welcome Back</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/' . ($page = '#')) }}"><i
                            class="fe fe-home mr-2 fs-14"></i>Home</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="{{ url('/' . ($page = '#')) }}">Profile</a>
                </li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <style>
        .profile-upload {
            transition: .5s ease;
            opacity: 0;
            position: absolute;
            top: 50%;
            left: 65px;
            transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            text-align: center;
        }

        .profile-upload i {
            font-size: 34px;
            color: #705ec8;
        }

        .img-container {
            position: relative;
            width: 17%;
        }

        .img-container:hover .profile-img {
            opacity: 0.3;
        }

        .img-container .profile-img {
            width: auto;
            height: 100%;
        }

        .img-container:hover .profile-upload {
            opacity: 1;
        }

        .profile-img {
            opacity: 1;
            display: block;
            transition: .5s ease;
            backface-visibility: hidden;
            max-height: 131px;
        }

        .dt-buttons.btn-group {
            float: left;
        }

        .table {
            width: 100% !important;
        }

        .main-proifle.admin {
            background: #dee2e6;
        }

        h4.pro-user-username.mb-3.font-weight-bold {
            width: AUTO;
            display: inline-block;
        }

        .iti.iti--allow-dropdown.iti--show-flags {
            width: 100%;
        }
    </style>
    <!--/app header-->
    <div class="main-proifle {{ $user?->roles()?->first()?->name }}">
        <div class="row">
            <div class="col-lg-8">
                <div class="box-widget widget-user">
                    <div class="widget-user-image1 d-sm-flex">
                        <div class="img-container">
                            <img alt="User Avatar" class="rounded-circle profile-img border p-0"
                                src="{{ $user->profile_url }}">
                            <div class="profile-upload">
                                <a href="javascript:void(0)" class="" id="ProfileUploadBtn"><i
                                        class="fa fa-camera"></i></a>
                            </div>
                            <form id="profileImageForm" action="{{ route('profile.upload-image', $user->id) }}"
                                enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="file" id="ProfileUpload" name="image_upload" style="display:none;"
                                    accept="image/*" />
                            </form>
                        </div>
                        <div class="mt-1 ml-lg-5">
                            <h4 class="pro-user-username mb-3 font-weight-bold">{{ $user->name }} <i
                                    class="fa fa-check-circle text-success"></i></h4> <span
                                class="badge badge-default mt-2">{{ $user?->roles()?->first()?->name }}</span>
                            <ul class="mb-0 pro-details">
                                <li><span class="profile-icon"><i class="fe fe-mail"></i></span><span
                                        class="h6 mt-3">{{ $user->email }}</span></li>
                                <li><span class="profile-icon"><i class="fe fe-phone-call"></i></span><span
                                        class="h6 mt-3">{{ $user->phone }}</span></li>
                                <li><span class="profile-icon"><i class="fe fe-globe"></i></span><span
                                        class="h6 mt-3">{{ $user->website }}</span></li>
                                <li><span class="profile-icon"><i class="fa fa-location-arrow"></i></span><span
                                        class="h6 mt-3">{{ $user->address }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="profile-cover">
            <div class="wideget-user-tab">
                <div class="tab-menu-heading p-0">
                    <div class="tabs-menu1 px-3">
                        <ul class="nav">
                            <li><a href="#myProfile" class="active fs-14" data-toggle="tab"> Profile</a></li>
                            <li><a href="#sub" class=" fs-14" data-toggle="tab"> Subscriptions</a></li>



                            @if ($user->hasRole('super'))
                                <li><a href="#admins" class=" fs-14" data-toggle="tab"> Admins</a></li>


                            @endif

                            @if ($user->hasRole('admin'))
                                <li><a href="#admins" class=" fs-14" data-toggle="tab"> Vendors</a></li>
                            @endif

                            @if ($user->hasRole('vendor'))
                                <li><a href="#vendor" class=" fs-14" data-toggle="tab"> admin</a></li>
                                <li><a href="#order" class=" fs-14" data-toggle="tab"> Orders</a></li>

                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div><!-- /.profile-cover -->
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="border-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="myProfile">
                        <div class="card">
                            <form action="{{ route('profile.update', $user->id) }}" method="POST" id="editProfile">
                                @csrf
                                <div class="card-header">
                                    <div class="card-title">Edit Profile</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">
                                                <label class="input-label">Name</label>
                                                <input type="text" class="google-input" name="name" id="name"
                                                    value="{{ $user->name }}" />
                                            </div>
                                            @error('name')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">
                                                <label class="input-label">Username</label>
                                                <input type="text" name="username" class="google-input"
                                                    id="Username" value="{{ $user->username }}">
                                            </div>
                                            @error('username')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">
                                                <label class="input-label">Email address</label>
                                                <input type="email" name="email" value="{{ $user->email }}"
                                                    class="google-input">
                                            </div>
                                        </div>
                                        <input type="hidden" name="last_used[0][iso2]" id="last_used" value="" />
                                        <input type="hidden" name="last_used[0][dialCode]" id="last_used2"
                                            value="" />
                                        <input type="hidden" name="last_used[0][name]" id="last_used3"
                                            value="" />
                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">
                                                <label class="input-label">Phone Number</label>
                                                <input type="phone" name="phone" id="phone" class="google-input"
                                                    value="{{ $user->phone }}">
                                            </div>
                                            @error('phone')
                                                <label id="phone-error" class="error"
                                                    for="phone">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-box">
                                                <textarea class="google-input" id="address" name="address" rows="1" placeholder="Address">{{ $user->address }}</textarea>
                                            </div>
                                            @error('address')
                                                <label id="address-error" class="error"
                                                    for="address">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">
                                                <label class="input-label">Website</label>
                                                <input type="text" id="website" name="website" class="google-input"
                                                    value="{{ $user->website }}">
                                            </div>
                                            @error('website')
                                                <label id="website-error" class="error"
                                                    for="website">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        @if (
                                            (auth()->user()->hasRole('super') ||
                                                auth()->user()->id == $user->user_id) &&
                                                $user->hasanyrole('super|admin|vendor'))
                                            <div class="col-sm-6 col-md-6">
                                                <div class="input-box">
                                                    <select class=" google-input" name="group_id" tabindex="null">
                                                        <option selected disabled>Select Customer Group</option>
                                                        @foreach ($groups as $group)
                                                            <option @if ($user->group_id == $group->id) selected @endif
                                                                value="{{ $group->id }}">{{ $group->id }} -
                                                                {{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('group_id')
                                                    <label id="user_id-error" class="error"
                                                        for="group_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        @endif



                                        @if ((auth()->user()->hasRole('super') || auth()->user()->id == $user->user_id) )
                                            <div class="col-sm-6 col-md-6">
                                                <div class="input-box">
                                                    <select class=" google-input" name="access_table" tabindex="null">
                                                        <option disabled>Select Access Scoup</option>
                                                        <option @if($user->access_table == 'Global') selected @endif value="Global">Global</option>
                                                        <option @if($user->access_table == 'Group') selected @endif value="Group">Group</option>
                                                        <option @if($user->access_table == 'Individual') selected @endif value="Individual">Individual</option>

                                                    </select>
                                                </div>
                                                @error('access_table')
                                                    <label id="user_id-error" class="error"
                                                        for="access_table">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        @endif


                                        {{-- @role('user') --}}
                                        @if (
                                            (auth()->user()->hasRole('super') ||
                                                auth()->user()->id == $user->user_id))
                                                @if($user->role('user'))
                                            <div class="col-sm-6 col-md-6">
                                                <div class="input-box">
                                                    <select class=" google-input" name="ugroup_id" tabindex="null">
                                                        <option selected disabled>Select User Group</option>
                                                        @foreach ($ugroups as $group)
                                                            <option @if ($user->ugroup_id == $group->id) selected @endif
                                                                value="{{ $group->id }}">{{ $group->id }} -
                                                                {{ $group->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @error('ugroup_id')
                                                    <label id="user_id-error" class="error"
                                                        for="ugroup_id">{{ $message }}</label>
                                                @enderror
                                            </div>
                                            @endif
                                            {{-- @endrole --}}
                                        @endif


                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                        <div class="card">
                            <form action="{{ route('profile.change-password', $user->id) }}" method="POST"
                                id="changePasswordForm">
                                @csrf
                                <div class="card-header">
                                    <div class="card-title">Change Password</div>
                                </div>
                                <div class="card-body">
                                    {{-- <div class="card-title font-weight-bold">Basic info:</div> --}}
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4">
                                            <div class="input-box">
                                                <label class="input-label">Old Password</label>
                                                <input type="password" name="old_password" class="google-input"
                                                    id="old_password">
                                            </div>
                                            @error('old_password')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="input-box">
                                                <label class="input-label">New Password</label>
                                                <input type="password" name="password" class="google-input"
                                                    id="password">
                                            </div>
                                            @error('password')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4 col-md-4">
                                            <div class="input-box">
                                                <label class="input-label">Confirm Password</label>
                                                <input type="password" name="password_confirmation" class="google-input"
                                                    id="PasswordConfirmation">
                                            </div>
                                            @error('password_confirmation')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tab-pane" id="sub">

                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Subscriptions Data</div>
                            </div>
                            <div class="card-body">

                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap" id="attribute_table">
                                        <thead>
                                            <tr>
                                                <th width="100px">No.</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>User</th>
                                                <th>Plan</th>
                                                <th>Status</th>
                                                <th data-priority="1"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

                    @if ($user->hasRole('super') || $user->hasRole('admin'))
                        <div class="tab-pane" id="admins">

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Data</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap" id="admins_table">
                                            <thead>
                                                <tr>
                                                    <th width="100px">No.</th>
                                                    <th>Name</th>
                                                    <th>username</th>
                                                    <th>email</th>
                                                    <th>avatar</th>
                                                    <th>phone</th>
                                                    <th>address</th>
                                                    <th>website</th>
                                                    <th data-priority="1"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif

                    @if ($user->hasRole('vendor'))
                        <div class="tab-pane" id="vendor">

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">vendor</div>
                                </div>
                                <div class="card-body">
                                    This Vendor was Registered By <a
                                        href="{{ route('profile.index', $user->admin->id) }}">{{ $user->admin->name }}</a>
                                </div>
                            </div>
                        </div>




                        <div class="tab-pane" id="order">

                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Data</div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered text-nowrap" id="orders_table">
                                            <thead>
                                                <tr>
                                                    <th width="100px">No.</th>
                                                    <th>Mixture_id</th>
                                                    <th>Mixture Name</th>
                                                    <th>{{ __('Created At') }}</th>
                                                    <th>{{ __('Updated At') }}</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>



                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/js/popover.js') }}"></script>

    <!-- INTERNAL Sweet alert js -->
    <script src="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>


    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
    <script>
        const input = document.querySelector("#phone");
        iti = window.intlTelInput(input, {
            initialCountry: "auto",
            geoIpLookup: callback => {
                fetch("https://ipapi.co/json")
                    .then(res => res.json())
                    .then(data => callback(data.country_code))
                    .catch(() => callback("us"));
            },
            utilsScript: "/intl-tel-input/js/utils.js?1695806485509" // just for formatting/placeholders etc
        });

        input.addEventListener("countrychange", function() {
            // do something with iti.getSelectedCountryData()
            const countryData = iti.getSelectedCountryData();
            input1 = document.querySelector("#last_used");
            input2 = document.querySelector("#last_used2");
            input3 = document.querySelector("#last_used3");
            input1.value = countryData.iso2;
            input2.value = countryData.dialCode;
            input3.value = countryData.name;
            console.log(countryData);
        });
    </script>

    {{-- @push('script') --}}
    <script>
        var table = $('#admins_table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            // dom: 'lBftrip',
            // buttons: ['copy', 'excel', 'pdf', 'colvis'],
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ ',
            },
            ajax: "{{ route('users.myadmins', $user->id) }}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'username',
                    name: 'username'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'avatar',
                    name: 'avatar',
                },
                {
                    data: 'phone',
                    name: 'phone'
                },
                {
                    data: 'address',
                    name: 'address'
                },
                {
                    data: 'website',
                    name: 'website'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],
            order: [
                [1, 'asc']
            ]
        });

        // console.log(table.buttons().container());

        table.buttons().container()
            .appendTo('#admins_table_wrapper .col-md-6:eq(0)');


            var table = $('#orders_table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            // dom: 'lBftrip',
            // buttons: ['copy', 'excel', 'pdf', 'colvis'],
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ ',
            },
            ajax: "{{ route('users.myorders') }}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'mixture_id',
                    name: 'mixture_id'
                },
                {
                    data: 'mixture',
                    name: 'mixture'
                },

                {
                    data: 'created_at',
                    name: 'created_at'
                },
                {
                    data: 'updated_at',
                    name: 'updated_at'
                },


            ]
        });

        // console.log(table.buttons().container());

        // table.buttons().container()
        //     .appendTo('#orders_table_wrapper .col-md-6:eq(0)');


        $(document).ready(function() {

            var last_used = JSON.parse({!! $last_used !!})
            console.log(last_used)
            setTimeout(() => {
                last_used.forEach(element => {
                    var li =
                        '<li class="iti__country iti__standard" tabindex="-1" id="iti-0__item-jm" role="option" data-dial-code="' +
                        element.dialCode + '" data-country-code="' + element.iso2 +
                        '" aria-selected="false"><div class="iti__flag-box"><div class="iti__flag iti__' +
                        element.iso2 + '"></div></div><span class="iti__country-name">' + element
                        .name + '</span><span class="iti__dial-code">+' + element.dialCode +
                        '</span></li>';
                    console.log(element.iso2)
                    $('.iti__country-list li:eq(0)').before(li);
                });
            }, 1000);
            // $('.iti__country-list li:eq(0)').before('<li>last used</li><li class="iti__divider" role="separator" aria-disabled="true"></li>');

            $("#ProfileUploadBtn").click(function() {
                $("#ProfileUpload").trigger('click');
            });
            $('#ProfileUpload').change(function() {
                $('#profileImageForm').submit()
            })
            $("#editProfile").validate({
                onkeyup: function(el, e) {
                    $(el).valid();
                },
                // errorClass: "invalid-feedback is-invalid",
                // validClass: 'valid-feedback is-valid',
                ignore: ":hidden",
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                    },
                    username: {
                        required: true,
                        maxlength: 255,
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 255,
                    },
                    address: {
                        required: true,
                        maxlength: 500,
                    },
                    phone: {
                        required: true,
                        maxlength: 255,
                    },
                    website: {
                        required: true,
                        url: true,
                        maxlength: 255,
                    }
                },
                messages: {},
                errorPlacement: function(error, element) {
                    error.insertAfter($(element).parent());
                },
            });

            $("#changePasswordForm").validate({
                ignore: ":hidden",
                rules: {
                    old_password: {
                        required: true,
                        // strong_password: true,
                    },
                    password: {
                        required: true,
                        strong_password: true,
                    },
                    password_confirmation: {
                        required: true,
                        strong_password: true,
                        equalTo: "#password"
                    }
                },
                messages: {
                    password_confirmation: {
                        equalTo: "To create a valid password, both the password and confirm password field values must be matched."
                    }
                },
            });

            $.validator.addMethod("strong_password", function (value, element) {
                let password = value;
                if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
                    return false;
                }
                return true;
            }, function (value, element) {
                let password = $(element).val();
                if (!(/^(.{8,20}$)/.test(password))) {
                    return 'Password must be between 8 to 20 characters long.';
                }
                else if (!(/^(?=.*[A-Z])/.test(password))) {
                    return 'Password must contain at least one uppercase.';
                }
                else if (!(/^(?=.*[a-z])/.test(password))) {
                    return 'Password must contain at least one lowercase.';
                }
                else if (!(/^(?=.*[0-9])/.test(password))) {
                    return 'Password must contain at least one digit.';
                }
                else if (!(/^(?=.*[@#$%&])/.test(password))) {
                    return "Password must contain special characters from @#$%&.";
                }
                return false;
            });
        });

        var table = $('#attribute_table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            // dom: 'lBftrip',
            // buttons: ['copy', 'excel', 'pdf', 'colvis'],
            responsive: true,
            language: {
                searchPlaceholder: 'Search...',
                sSearch: '',
                lengthMenu: '_MENU_ ',
            },
            ajax: "{{ route('profile.index', $user->id) }}",

            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'start_date',
                    name: 'start_date'
                },
                {
                    data: 'end_date',
                    name: 'end_date'
                },
                {
                    data: 'user_id',
                    name: 'user'
                },
                {
                    data: 'plan_id',
                    name: 'plan'
                },
                {
                    data: 'status',
                    name: 'status'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },

            ],
            order: [
                [1, 'asc']
            ]
        });

        // console.log(table.buttons().container());

        table.buttons().container()
            .appendTo('#attribute_table_wrapper .col-md-6:eq(0)');


        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        $(document).on('click', '.subscription-delete', function() {
            var id = $(this).attr("data-id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this attribute!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,


            }, function(willDelete) {
                if (willDelete) {

                    $.ajax({
                        type: "POST",
                        url: '{{ url('/') }}/subscription/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swal({
                                title: response.msg
                            }, function(result) {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    </script>
    {{-- @endpush
     --}}
@endsection
