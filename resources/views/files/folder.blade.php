@foreach (auth()->user()->files->where('folder_id', $folder->id) as $file)
    @include('files.file-card')
@endforeach
