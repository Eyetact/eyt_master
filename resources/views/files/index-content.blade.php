@foreach (auth()->user()->folders as $folder)
@include('files.folder-card')
@endforeach
@foreach (auth()->user()->files->where('folder_id', 0) as $file)
    @include('files.file-card')
@endforeach
