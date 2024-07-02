<div class="session-messages">
    @if (session('status') === true)
        <div class="alert alert-success" id="alertMessage">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ session('message') }}
            <button class="close" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @elseif(session('status') === false)
        <div class="alert alert-danger" id="alertMessage">
            &nbsp;&nbsp;{{ session('message') }}
        </div>
    @endif
</div>
