<div class="session-messages">
    @if (session('status') === true)
        <div class="alert alert-success" id="alertMessage" style="display: none;">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ session('message') }}
            <button class="close" onclick="this.parentElement.style.display='none';">&times;</button>
        </div>
    @elseif(session('status') === false)
        <div class="alert alert-danger" id="alertMessage" style="display: none;">
            &nbsp;&nbsp;{{ session('message') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        @if (session('status') === true)
            toastr.success("{{ session('message') }}", "Success");
        @elseif (session('status') === false)
            toastr.error("{{ session('message') }}", "Error");
        @endif
    });
</script>
