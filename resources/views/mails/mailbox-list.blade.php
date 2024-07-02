@extends('layouts.master')
@section('css')
    <style>
        .hidden {
            display: none;
        }
    </style>
@endsection
@section('page-header')
@endsection
@section('content')
    <!-- Row -->
    <div class="row mt-3">
        <div class="col-md-12 col-lg-4 col-xl-3">
            <div class="card">
                <div class="list-group list-group-transparent mb-0 mail-inbox pb-3">
                    <div class="mt-4 mb-4 ml-4 mr-4 text-center">
                        <a href="javascript:void(0)" data-toggle="modal" data-target="#composeMailModal"
                            class="btn btn-primary btn-block compose-btn">Compose</a>
                    </div>
                    <a href="#" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fe fe-inbox fs-18 mr-2"></i> Inbox <span
                            class="ml-auto badge badge-success">{{ $total }}</span>
                    </a>
                </div>
            </div>
        </div>

            <div class="col-md-12 col-lg-8 col-xl-9">
                <div class="card">
                    <div class="card-body p-6">
                        <div class="inbox-body">
                            <div class="mail-option">
                                {{ $inbox_data->links() }}
                            </div>
                            <div class="table-responsive mt-3">

                                @if (empty($inbox_data))
                                    <!-- no result when nothing to show on list -->
                                    <div class="no-results">
                                        <i class="feather icon-info font-large-2"></i>
                                        <h5>No Items Found</h5>
                                    </div>
                                @else
                                    <table class="table table-inbox table-hover text-nowrap">
                                        <tbody>
                                            @foreach ($inbox_data->reverse() as $key => $value)
                                                <tr class="evt-open-mail"
                                                    data-href="{{ url('/fetch') . '/' . request()->id . '/' . $value->getUid() }}">
                                                    <td class="view-message dont-show font-weight-semibold">
                                                        {{ $value->getSubject()[0] }}
                                                    </td>
                                                    <td class="view-message">
                                                        {{ $value->getFrom() }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>


    <div class="modal fade" id="composeMailModal" tabindex="-1" role="dialog" aria-labelledby="composeMailModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="composeMailModalLabel">Compose Mail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('sendReply', request()->id) }}" method="POST" id="mailboxForm"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-sm-12 col-md-12">
                                <div class="input-box">
                                    <label for="from" class="input-label">From</label>
                                    <input type="text" class="google-input" id="From" placeholder="{{$mail_data->imaps->email}}"
                                        value="{{$mail_data->imaps->email}}" readonly>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div class="input-box">
                                    {{-- <label for="emailTo" class="input-label">To</label> --}}
                                    <input type="email" id="emailTo" class="google-input" name="reply_email"
                                        placeholder="To" required>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div class="input-box">
                                    {{-- <label for="emailSubject" class="input-label">Subject</label> --}}
                                    <input type="text" id="emailSubject" class="google-input" placeholder="Subject"
                                        name="subject" required>
                                </div>
                            </div>

                            <div class="col-sm-12 col-md-12">
                                <div class="input-box">
                                    <textarea id="editor" name="body" class="google-input" style="height: 250px"></textarea>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-12">
                                <div class="form-group mt-2">
                                    <input type="file" name="attachment">
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button class="btn btn-primary" type="submit">Save</button>
                            <a href="{{ route('main_mailbox.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        $(document).on("click", ".evt-open-mail", function() {
            var href = $(this).data('href');
            window.location.href = href;
        })
    </script>
@endpush
