
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
    </style>
    <!--/app header-->
    <div class="main-proifle">
        <div class="row">
            <div class="col-lg-8">
                <div class="box-widget widget-user">
                    <div class="widget-user-image1 d-sm-flex">
                        {{-- <div class="img-container">
                            <img alt="Plan Image" class="rounded-circle profile-img border p-2"
                                src="{{ asset($plan->image) }}">


                        </div> --}}
                        <div class="mt-1 ml-lg-5">
                            <h4 class="pro-user-username mb-3 font-weight-bold">{{ $folder->name }} </h4>
                            <ul class="mb-0 pro-details">
                                <p class="mb-3"><strong>Name : </strong> {{ $folder->name }}</p>


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Row -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12">
            <div class="border-0">
                <div class="tab-content">
                    <div class="tab-pane active" id="myProfile">
                        <div class="card">
                            <form action="{{ route('folder.update', $folder->id) }}" method="POST" id="editProfile"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="card-header">
                                    <div class="card-title">Edit Folder</div>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-lg-12 col-sm-12">
                                            <div class="input-box">
                                                <label class="input-label">Name</label>
                                                <input type="text" class="google-input" name="name" id="name"
                                                    value="{{ $folder->name }}" />
                                            </div>
                                            @error('name')
                                                <label id="name-error" class="error"
                                                    for="name">{{ $message }}</label>
                                            @enderror
                                        </div>






                                <div class="card-footer text-right">
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/plugins/wysiwyag/jquery.richtext.js">
    </script>

    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

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
