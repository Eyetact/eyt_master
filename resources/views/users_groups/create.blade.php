
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">add group</h3>
        </div>
        <div class="card-body pb-2">
            <form action="{{ route('ugroups.store') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
                @csrf
                <div class="row">

                    <div class="col-lg-4 col-sm-4">
                        <div class="input-box">
                            <label for="name" class="input-label">Name</label>
                            <input type="text" class="google-input" name="name" id="name" value="" />
                        </div>
                        @error('name')
                            <label id="name-error" class="error" for="name">{{ $message }}</label>
                        @enderror
                    </div>


                    <div class="col-sm-4 col-md-4">
                        <div class="input-box">
                            <select class=" google-input" name="role" tabindex="null">
                                <option selected disabled>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}">{{$role->id}} - {{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('role_name')
                            <label id="role_name-error" class="error" for="role_name">{{ $message }}</label>
                        @enderror

                    </div>

                    <div class="col-sm-4 col-md-4">
                        <div class="input-box">
                            <select class="google-input" name="group_id" tabindex="null">
                                <option value="">-- Parent --</option>

                                @foreach ($parents_group as $group)
                                    <option value="{{ $group->id }}">{{$group->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('group_id')
                            <label id="group_id-error" class="error" for="group_id">{{ $message }}</label>
                        @enderror
                    </div>


                </div>



                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a href="{{ route('ugroups.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
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

    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!--INTERNAL Form Advanced Element -->
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/plugins/wysiwyag/jquery.richtext.js">
    </script>

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
                name: {
                    required: true,
                },
                email: {
                    required: true,
                },
                password: {
                    required: true,
                }

            },
            messages: {},
            errorPlacement: function(error, element) {
                error.insertAfter($(element).parent());
            },
        });

        $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").on(
            "keyup",
            function() {
                var $input = $(this);
                if ($input.val() != '') {
                    $input.parents(".input-box").addClass("focus");
                } else {
                    $input.parents(".input-box").removeClass("focus");
                }
            });
        $("input[type='text'], input[type='number'], input[type='password'], input[type='email'], input[type='tel']").each(
            function(index, element) {
                var value = $(element).val();
                if (value != '') {
                    $(this).parents('.input-box').addClass('focus');
                } else {
                    $(this).parents('.input-box').removeClass('focus');
                }
            });
        // });

        $(function(e) {
            $('.content').richText();
            $('.content2').richText();
        });
    </script>
