@switch($file->type)
    @case('mp4')
    <video width="100%"  controls>
        <source src="{{ asset($file->path) }}" type="video/mp4">
        Your browser does not support HTML video.
      </video>
      
        @break
    @case('mp3')
    @case('wav')
    @case('flac')
    <figure width="100%">
        <audio style="width: 100%;" width="100%"  controls src="{{ asset($file->path) }}">
        </audio>
      </figure>
        @break

        @case('png')
        @case('gif')
        @case('jpeg')
        @case('jpg')

        <center>
            <img src="{{ asset($file->path) }}" class="img-fluid"/>
        </center>

        @break

    @default

    <object data="{{ asset($file->path) }}" type="application/pdf" width="100%" height="500px">
        <p>Unable to display  file. <a href="{{ asset($file->path) }}">Download</a> instead.</p>
      </object>
        
@endswitch