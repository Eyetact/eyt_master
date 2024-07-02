<form action="{{ route('groups.store') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
    @csrf
    <div class="row">




        @if(auth()->user()->hasRole('super'))


        <div class="col-lg-4 col-sm-4">
            <div class="input-box">
                <label for="name" class="input-label">Name</label>
                <input type="text" class="google-input" name="name" id="name" value="" />
            </div>
            @error('name')
                <label id="name-error" class="error" for="name">{{ $message }}</label>
            @enderror
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="input-box">
                <select class="google-input" name="type" tabindex="null">
                    <option value="" selected disabled>-- select type --</option>


                        <option value="admin">Admin</option>
                        <option value="public_vendor">Public Vendor</option>

                </select>
            </div>
            @error('type')
                <label id="type-error" class="error" for="type">{{ $message }}</label>
            @enderror
        </div>


        <div class="col-sm-6 col-md-4">
            <div class="input-box">
                <select class="google-input" name="group_id" tabindex="null">
                    <option value="" selected disabled>-- Parent --</option>

                    @foreach ($parents_group as $group)
                        <option value="{{ $group->id }}">{{$group->name}}</option>
                    @endforeach
                </select>
            </div>
            @error('group_id')
                <label id="group_id-error" class="error" for="group_id">{{ $message }}</label>
            @enderror
        </div>

        @else
        <div class="col-lg-6 col-sm-6">
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
                <select class="google-input" name="group_id" tabindex="null">
                    <option value="" selected disabled>-- Parent --</option>

                    @foreach ($parents_group as $group)
                        <option value="{{ $group->id }}">{{$group->name}}</option>
                    @endforeach
                </select>
            </div>
            @error('group_id')
                <label id="group_id-error" class="error" for="group_id">{{ $message }}</label>
            @enderror
        </div>
        @endif






    </div>



    <div class="card-footer">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
