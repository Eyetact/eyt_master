<form action="{{ route('subscriptions.store') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
    @csrf
    <div class="row">



        {{-- <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label class="form-label">Start Date<span class="text-danger">*</span></label>
                <input type="date" class="google-input" name="start_date" id="start_date" value="" />
            </div>
            @error('start_date')
                <label id="start_date-error" class="error" for="start_date">{{ $message }}</label>
            @enderror
        </div>


        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label class="form-label">End Date<span class="text-danger">*</span></label>
                <input type="date" class="google-input" name="end_date" id="end_date" value="" />
            </div>
            @error('end_date')
                <label id="end_date-error" class="error" for="end_date">{{ $message }}</label>
            @enderror
        </div> --}}


        <div class="col-sm-6 col-md-6">
            <div class="input-box">
                <label class="form-label">Plan<span class="text-danger">*</span></label>
                <select class="google-input" name="plan_id" tabindex="null">
                    <option selected disabled>Select Plan</option>
                    @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{$plan->name}}</option>
                    @endforeach
                </select>
            </div>
            @error('plan_id')
                <label id="plan_id-error" class="error" for="plan_id">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-6">

            <div class="input-box">
                <label class="form-label">Customer Group<span class="text-danger">*</span></label>
                <select class="google-input" name="group_id" tabindex="null">
                    <option selected disabled>Select Group</option>
                    @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{$group->id}} - {{$group->name}}</option>
                    @endforeach
                </select>
            </div>
            @error('group_id')
                <label id="user_id-error" class="error" for="group_id">{{ $message }}</label>
            @enderror
            <span>OR</span>
            <div class="input-box">

                <label class="form-label">Customer<span class="text-danger">*</span></label>
                <select class="google-input" name="user_id" tabindex="null">
                    <option selected disabled>Select Customer</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{$user->id}} - {{$user->username}}</option>
                    @endforeach
                </select>
            </div>
            @error('user_id')
                <label id="user_id-error" class="error" for="user_id">{{ $message }}</label>
            @enderror
        </div>




    </div>



    <div class="card-footer">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>