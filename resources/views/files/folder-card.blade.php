<div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card border p-0 shadow-none view-folder" data-id="{{ $folder->id }}" data-path="{{ route('viewfolder', $folder->id) }}">
        <div class="d-flex align-items-center px-4 pt-4">
            <label class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="example-checkbox2"
                    value="option2">
                <span class="custom-control-label"></span>
            </label>
            <div class="float-right ml-auto">
                <a href="#" class="option-dots" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right">

                    <a class="dropdown-item folder-edit"
                        data-path="{{ route('showfolder', $folder->id) }}" href="#"><i
                            class="fe fe-edit mr-2"></i> View Or Edit</a>
                    
                    <a class="dropdown-item folder-delete" data-id="{{ $folder->id }}"
                        href="#"><i class="fe fe-trash mr-2"></i>
                        Delete</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0 text-center">
            <div class="file-manger-icon">
                <img src="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/images/files/folder.png"
                    alt="img" class="br-7">
            </div>
            <h6 class="mb-1 font-weight-semibold mt-4">{{ $folder->name }}</h6>
        </div>
    </div>
</div>