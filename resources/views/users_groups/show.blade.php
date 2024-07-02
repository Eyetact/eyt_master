
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
    </style>
    <!--/app header-->
    <div class="main-proifle">
        <h4 class="pro-user-username mb-3 font-weight-bold">{{ $group->name }} </h4>

    </div>

    <div class="profile-cover">
        <div class="wideget-user-tab">
            <div class="tab-menu-heading p-0">
                <div class="tabs-menu1 px-3">
                    <ul class="nav">
                        <li><a href="#edit" class="active fs-14" data-toggle="tab"> Edit</a></li>
                        <li><a href="#users" class=" fs-14" data-toggle="tab"> Users</a></li>



                    </ul>
                </div>
            </div>
        </div>
    </div><!-- /.profile-cover -->
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="border-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="edit">
                        <div class="card">
                            <form action="{{ route('ugroups.update', $group->id) }}" method="POST" id="editProfile"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-header">
                                    <div class="card-title">Edit group</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-6 col-sm-6">
                                            <div class="input-box">
                                                <label class="input-label">Name</label>
                                                <input type="text" class="google-input" name="name" id="name"
                                                    value="{{ $group->name }}" />
                                            </div>
                                            @error('name')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="input-box">

                                                <select class="google-input" name="role" tabindex="null">
                                                    <option selected disabled>Select Role</option>
                                                    @foreach ($roles as $role)
                                                        <option @if($group->hasRole($role->name)) selected @endif value="{{ $role->name }}">{{$role->id}} - {{$role->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('role')
                                            <div class="text-danger">{{ $message }}</div>
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
                    <div class="tab-pane " id="users">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Users Data</div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap" id="groub_users" style="width: 100%;">
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

                </div>
            </div>
        </div>
    </div>

    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- INTERNAL File uploads js -->
    <script src="{{ asset('assets/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/filupload.js') }}"></script>

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

    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!--INTERNAL Form Advanced Element -->
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>

    <script src="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/plugins/wysiwyag/jquery.richtext.js">
    </script>
    <script>
        var table = $('#groub_users').DataTable({
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
            ajax: "{{ route('ugroups.view2', $group->id) }}",

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
            .appendTo('#groub_users_wrapper .col-md-6:eq(0)');


        $(document).ready(function() {
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


        });

        $(function(e) {
            $('.content').richText();
            $('.content2').richText();
        });

