<div class="col-xl-3 col-md-4 col-sm-6">
    <div class="card border p-0 shadow-none">
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




                    <a class="dropdown-item play-file" href="#"
                        data-path="{{ route('open.file', $file->id) }}"
                        data-name="{{ $file->name }}"> <i
                            class="fe fe-play-circle mr-2"></i>Play</a>

                    <a class="dropdown-item file-edit" data-path="{{ route('showfile', $file->id) }}"
                        href="#"><i class="fe fe-edit mr-2"></i>View Or Edit</a>
                 
                    <a class="dropdown-item share-file" data-path="{{ route('sharefile', $file->id) }}"
                        data-name="{{ $file->name }}" >
                        <i class="fe fe-share mr-2"></i> Share
                    </a>

                    <a class="dropdown-item" href="{{ route('downloadfile', $file->id) }}">
                        <i class="fe fe-download mr-2"></i> Download
                    </a>

                    <a class="dropdown-item file-delete" data-id="{{ $file->id }}"
                        href="#"><i class="fe fe-trash mr-2"></i> Delete</a>
                </div>
            </div>
        </div>
        <div class="card-body pt-0 text-center">
            <div class="file-manger-icon">
                @switch($file->type)
                    @case('doc')
                        <img src="{{ asset('assets/word.png') }}" alt="img" class="br-7">
                    @break

                    @case('pdf')
                        <img src="{{ asset('assets/pdf.png') }}" alt="img" class="br-7">
                    @break

                    @case('xls')
                        <img src="{{ asset('assets/xls.png') }}" alt="img" class="br-7">
                    @break

                    @case('mp4')
                        <img src="{{ asset('assets/v.gif') }}" alt="img" class="br-7">
                    @break
                    @case('ppt')
                    @case('pptx')
                        <img src="{{ asset('assets/ppt.png') }}" alt="img" class="br-7">
                    @break

                    @case('mp3')
                    @case('wav')

                    @case('flac')
                        <div class="file-manger-icon">
                            <i class="fa fa-music text-secondary"></i>
                        </div>
                    @break

                    @default
                        <img src="{{ asset($file->path) }}" alt="img" class="br-7">
                    @break
                @endswitch
            </div>
            <h6 class="mb-1 font-weight-semibold mt-4">{{ $file->name }}</h6>
        </div>
    </div>
</div>