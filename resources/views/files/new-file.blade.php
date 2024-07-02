
<form action="{{ route('files') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
    @csrf
    <div class="row">

        {{-- <div class="col-lg-12 col-sm-12">
            <div class="input-box">
                <label for="name" class="input-label">Name</label>
                <input type="text" class="google-input" name="name" id="name" value="" />
            </div>
            @error('name')
                <label id="name-error" class="error" for="name">{{ $message }}</label>
            @enderror
        </div> --}}

        <div class="col-lg-12 col-sm-12">
            <label class="form-label">image</label>
            <input type="file" class="dropify" name="file" data-default-file="" data-height="180" />
        </div>

        <input type="hidden" value="0" id="folder_id" name="folder_id" />
    </div>



    <div class="card-footer">
        <button class="btn btn-primary" type="submit">Save</button>
        <a href="{{ route('plans.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

