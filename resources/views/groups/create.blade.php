<form action="{{ route('groups.store') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
    @csrf
    <div class="row">

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
        <a href="{{ route('groups.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
