@extends('layouts.master')

@section('css')
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Mailboxes</h4>
            <span>Select any mailbox to check mails</span>
        </div>
    </div>
@endsection


@section('content')
    <div class="container-fluid">
        <div class="row">
            @foreach ($allMailbox as $mailbox)
                <div class="col-md-12 col-lg-4" onclick="gotoMail({{ $mailbox->id }})">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $mailbox->mailer_id }}</h5>
                            <p class="card-text">{{ $mailbox->email }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('script')
    <script>
        function gotoMail(mail_id) {
            window.location.href = "{{ url('mails') }}/" + mail_id;
        }
    </script>
@endpush
