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

                    <div class="mt-1 ml-lg-5">
                        <h4 class="pro-user-username mb-3 font-weight-bold">{{ $subscription->id }} </h4>
                        <ul class="mb-0 pro-details">
                            <p class="mb-3"><strong>Start Date: </strong> {{ $subscription->start_date }}</p>
                            <p class="mb-3"><strong>End Date: </strong> {{ $subscription->end_date }}</p>
                            <p class="mb-3"><strong>User : </strong> <a
                                    href="{{ route('profile.index', $subscription->user->id) }}">
                                    {{ $subscription->user->name }}</a></p>
                            <p class="mb-3"><strong>Plan :</strong> <a
                                    href="{{ route('plans.view', $subscription->plan->id) }}">{{ $subscription->plan->name }}</a>
                            </p>
                            <p class="mb-3"><strong>Status: </strong> {{ $subscription->status }}</p>
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
                        <form action="{{ route('subscriptions.update', $subscription->id) }}" method="POST"
                            id="editProfile" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                <div class="card-title">Edit Subscription</div>
                            </div>
                            <div class="card-body">
                                <div class="row">



                                    <div class="col-sm-6 col-md-6">
                                        <div class="input-box">
                                            <label class="form-label">Start Date<span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="google-input" name="start_date" id="start_date"
                                                value="{{ $subscription->start_date }}" />
                                        </div>
                                        @error('start_date')
                                            <label id="start_date-error" class="error"
                                                for="start_date">{{ $message }}</label>
                                        @enderror
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="input-box">
                                            <label class="form-label">End Date<span class="text-danger">*</span></label>
                                            <input type="text" name="end_date" class="google-input" id="end_date"
                                                value="{{ $subscription->end_date }}">
                                        </div>
                                        @error('end_date')
                                            <label id="name-error" class="error"
                                                for="name">{{ $message }}</label>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="input-box">
                                            <label class="form-label">Plan<span class="text-danger">*</span></label>
                                            <select class="default-select form-control wide mb-3" name="plan_id"
                                                tabindex="null">
                                                @foreach ($plans as $plan)
                                                    <option @selected($subscription->plan_id == $plan->id) value="{{ $plan->id }}">{{ $plan->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('plan_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="input-box">
                                            <label class="form-label">User<span class="text-danger">*</span></label>
                                            <select class="default-select form-control wide mb-3" name="user_id"
                                                tabindex="null">
                                                @foreach ($users as $user)
                                                    <option @selected($user->id == $subscription->user_id) value="{{ $user->id }}">{{ $user->id . ' - '  . $user->username }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('user_id')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-sm-6 col-md-6">
                                        <div class="input-box">
                                            <label class="form-label">Status<span class="text-danger">*</span></label>
                                            <div class="form-check">
                                                <input  {{ $subscription->status === 'pending' ? 'checked' : '' }} class="form-check-input" id="pending" type="radio" name="status" value="pending">
                                                <label class="form-check-label" for="pending">
                                                    Pending
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input {{ $subscription->status === 'active' ? 'checked' : '' }} class="form-check-input" id="active" type="radio" name="status" value="active" >
                                                <label class="form-check-label" for="active">
                                                    Active
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input  {{ $subscription->status === 'inactive' ? 'checked' : '' }} class="form-check-input" id="inactive" type="radio" name="status" value="inactive" >
                                                <label class="form-check-label" for="inactive">
                                                    Inactive
                                                </label>
                                            </div>
                                        </div>
                                        @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                     @enderror
                                    </div>


                                    {{-- <div class="col-sm-6 col-md-6">

                                        <label class="form-label">Status<span class="text-danger">*</span></label>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio" for="pending">
                                                <input  @if($subscription->status ? $subscription->status == 'pending' : '') checked @endif
                                                  class="custom-control-input" id="pending" type="radio"
                                                    name="status" value="pending">

                                                <span class="custom-control-label">Pending</span>
                                            </label>

                                            <label class="custom-control custom-radio" for="active">
                                                <input @if($subscription->status ? $subscription->status == 'active' : '') checked @endif

                                                    class="form-check-input" id="active" type="radio"
                                                    name="status" value="active">
                                                <span class="custom-control-label">Active</span>
                                            </label>

                                            <label class="custom-control custom-radio" for="inactive">
                                                <input @if($subscription->status ? $subscription->status == 'inactive' : '') checked @endif
                                                    class="form-check-input" id="inactive" type="radio"
                                                    name="status" value="inactive">
                                                <span class="custom-control-label">Inactive</span>
                                            </label>
                                        </div>

                                        @error('status')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div> --}}

                                </div>
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
<script>
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
</script>
