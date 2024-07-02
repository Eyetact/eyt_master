<html lang="en">

<head>
    <title>Gmail Style Inbox page - coderglass</title>
    <link href="{{ asset('mailbox/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">
    <link href="{{ asset('mailbox/css/tab.css') }}" rel="stylesheet" id="bootstrap-css">
    <script src="{{ asset('mailbox/js/jquery-1.10.2.min.js') }}"></script>
    <script src="{{ asset('mailbox/js/bootstrap.min.js') }}"></script>
    <style>
        .custom_btn_cls {
            min-height: 34px;
        }
    </style>
</head>

<body>
    <div class="container">
        <hr />
        <div class="row">
            <div class="col-sm-12 col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <!-- <h3 class="card-title">Compose New Message (Replay mail)</h3> -->
                        <h3><?php echo $messages->getSubject()[0]; ?></h3><span><?php echo $messages->getFrom()[0]->mail; ?></span>
                        <?php echo $messages->getHTMLBody(); ?>
                    </div>
                    <br />
                    <!-- /.card-header -->
                    <form action="{{ route('sendReply',request()->id) }}" method="POST" enctype="multipart/form-data"
                        id="mailboxForm">
                        @csrf
                        <div class="card-body">
                            <input class="form-control" type="hidden" name="reply_email"
                                placeholder="From mail for replay to:" value="<?php echo $messages->getFrom()[0]->mail; ?>">
                            <input class="form-control" name="subject" placeholder="Subject:"
                                value="<?php echo $messages->getSubject()[0]; ?>" type="hidden">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="exampleFormControlInput1">Message : </label>
                                <div class="col-sm-10">
                                    <textarea id="compose-textarea" name="body" class="form-control" style="height: 300px"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label" for="exampleFormControlInput1">Upload file :
                                </label>
                                <div class="col-sm-10">
                                    <div class="btn btn-default btn-file">
                                        <input type="file" name="attachment">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success"><i class="far fa-envelope"></i> Send</button>
                        </div>
                    </form>
                    <!-- /.card-footer -->
                </div>
            </div>
        </div>
    </div>
</body>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> --}}
<script type="text/javascript">
    window.alert = function() {};
    var defaultCSS = document.getElementById('bootstrap-css');

    function changeCSS(css) {
        if (css) $('head > link').filter(':first').replaceWith('<link rel="stylesheet" href="' + css +
            '" type="text/css" />');
        else $('head > link').filter(':first').replaceWith(defaultCSS);
    }
    $(document).ready(function() {
        var iframe_height = parseInt($('html').height());
        window.parent.postMessage(iframe_height, 'http://127.0.0.1:8000/');
    });
</script>

</html>
