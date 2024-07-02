<form action="{{ route('users.store') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
    @csrf

    {{-- <input type="hidden" name="role" value="user"/> --}}
    <div class="row">

        <div class="col-lg-12 col-sm-12">
            <label class="form-label">avatar</label>
            <input type="file" class="dropify" name="avatar"
                data-default-file=""
                data-height="180" />
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="name" class="input-label">Name</label>
                <input type="text" class="google-input" name="name" id="name" value="" />
            </div>
            @error('name')
                <label id="name-error" class="error" for="name">{{ $message }}</label>
            @enderror
        </div>


        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="username" class="input-label">username</label>
                <input type="text" class="google-input" name="username" id="username" value="" />
            </div>
            @error('username')
                <label id="username-error" class="error" for="username">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="email" class="input-label">email</label>
                <input type="email" class="google-input" name="email" id="email" value="" />
            </div>
            @error('email')
                <label id="email-error" class="error" for="email">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="phone" class="input-label">phone</label>
                <input type="text" class="google-input" name="phone" id="phone" value="" />
            </div>
            @error('phone')
                <label id="phone-error" class="error" for="phone">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="address" class="input-label">address</label>
                <input type="text" class="google-input" name="address" id="address" value="" />
            </div>
            @error('address')
                <label id="address-error" class="error" for="address">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="website" class="input-label">website</label>
                <input type="text" class="google-input" name="website" id="website" value="" />
            </div>
            @error('website')
                <label id="website-error" class="error" for="website">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label for="name" class="input-label">password</label>
                <input type="password" class="google-input" name="password" id="password" value="" />
            </div>
            @error('password')
                <label id="password-error" class="error" for="password">{{ $message }}</label>
            @enderror
        </div>
        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <select class=" google-input" name="group_id[]" tabindex="null" multiple>
                    <option selected disabled>Select User Group</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{$group->id}} - {{$group->name}}</option>
                    @endforeach
                </select>
            </div>
            @error('ugroup_id')
                <label id="user_id-error" class="error" for="ugroup_id">{{ $message }}</label>
            @enderror

        </div>

        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <select class=" google-input" name="role" tabindex="null" >
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


        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <select class=" google-input" name="access_table" tabindex="null">
                    <option disabled>Select Access Scoup</option>
                    <option  value="Global">Global</option>
                    <option  value="Group">Group</option>
                    <option  value="Individual">Individual</option>

                </select>
            </div>
            @error('access_table')
                <label id="user_id-error" class="error"
                    for="access_table">{{ $message }}</label>
            @enderror
        </div>
    </div>




    <div class="card-footer">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('main_mailbox.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
