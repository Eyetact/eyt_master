@extends('layouts.master')
@section('css')
    <!-- INTERNAL File Uploads css -->
    <link href="{{ asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css-->
    <link href="{{ asset('assets/plugins/fileupload/css/fileupload.css') }}" rel="stylesheet" type="text/css" />

    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />
    <link href="https://laravel.spruko.com/admitro/Vertical-IconSidedar-Light/assets/plugins/wysiwyag/richtext.css"
        rel="stylesheet" />
    <style>
        .dropdown-toggle:after {
            content: none !important;
        }

        li.dropdown-item button,
        li.dropdown-item a {
            border: none;
            background: transparent;
            color: #333;
            padding: 0px 10px;
        }

        li.dropdown-item {
            padding: 10px;
            text-align: left;
        }

        .dt-buttons.btn-group {
            float: left;
        }

        .parent {
            animation: unset !important;
        }

        table {
            max-width: 99% !important;
            width: 99% !important;
        }

        .spinner3 {
            margin: 100px auto;
            width: 40px;
            height: 40px;
            position: relative;
            text-align: center;
            -webkit-animation: sk-rotate 2.0s infinite linear;
            animation: sk-rotate 2.0s infinite linear;
        }

        .dot1 {
            width: 60%;
            height: 60%;
            display: inline-block;
            position: absolute;
            top: 0;
            border-radius: 100%;
            -webkit-animation: sk-bounce 2.0s infinite ease-in-out;
            animation: sk-bounce 2.0s infinite ease-in-out;
        }

        .dot2 {
            width: 60%;
            height: 60%;
            display: inline-block;
            position: absolute;
            top: 0;
            border-radius: 100%;
            -webkit-animation: sk-bounce 2.0s infinite ease-in-out;
            animation: sk-bounce 2.0s infinite ease-in-out;
            top: auto;
            bottom: 0;
            -webkit-animation-delay: -1.0s;
            animation-delay: -1.0s;
        }

        @-webkit-keyframes sk-rotate {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes sk-rotate {
            100% {
                transform: rotate(360deg);
                -webkit-transform: rotate(360deg);
            }
        }

        .cloader {
            position: absolute;
            z-index: 999;
            background: #ffffff94;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .view-folder {
            cursor: pointer;
        }
    </style>
@endsection
@push('styles')
    <!-- INTERNAL Sumoselect css-->
    <link rel="stylesheet" href="{{ asset('assets/plugins/sumoselect/sumoselect.css') }}    ">

    <!-- INTERNAL File Uploads css -->
    <link href="{{ asset('assets/plugins/fancyuploder/fancy_fileupload.css') }}" rel="stylesheet" />

    <!-- INTERNAL File Uploads css-->
    <link href="{{ asset('assets/plugins/fileupload/css/fileupload.css') }}" rel="stylesheet" type="text/css" />
@endpush
@section('page-header')
    <div class="page-header">

        <div class="page-leftheader">
            <h4 class="page-title mb-0">File Manager</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-grid mr-2 fs-14"></i>Apps</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">File Manager </a></li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        @include('files.sidebar')
        <div class="col-lg-9 col-xl-9">
            <div class="row">
                <div class="col-6 mb-4">
                    <div class="btn-list">
                        <a href="#" id="new_file" class="btn btn-primary"><i class="fe fe-plus"></i> Upload New
                            Files</a>
                        <a href="#" id="new_folder" class="btn btn-outline-secondary"><i class="fe fe-folder"></i> New
                            folder</a>
                    </div>
                </div>
                <div class="col-6 col-auto">
                    <div class="form-group">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="fe fe-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Search Files" id="search"
                                fdprocessedid="lqp7z">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="files_container">
                <div class="cloader">
                    <div class="spinner3">
                        <div class="dot1"></div>
                        <div class="dot2"></div>
                    </div>
                </div>
                @if (!empty($folder))
                    @include('files.folder', ['folder' => $folder])
                @else
                    @include('files.index-content')
                @endif
            </div>

        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="role_form_modal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add Role</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="current-id" value="0" />
@endsection
@section('js')
    <!-- INTERNAL File uploads js -->
    <script src="{{ asset('assets/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/filupload.js') }}"></script>


    <!-- INTERNAL Data tables -->
    <script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/pdfmake.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/vfs_fonts.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/datatables.js') }}"></script>
    <script src="{{ URL::asset('assets/js/popover.js') }}"></script>

    <!-- INTERNAL Sweet alert js -->
    <script src="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>


    <!-- INTERNAL Select2 js -->
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- INTERNAL File uploads js -->
    <script src="{{ asset('assets/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('assets/js/filupload.js') }}"></script>

    <!--INTERNAL Sumoselect js-->
    <script src="{{ asset('assets/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!--INTERNAL Form Advanced Element -->
    <script src="{{ asset('assets/js/formelementadvnced.js') }}"></script>
    <script src="{{ asset('assets/js/form-elements.js') }}"></script>
    <script src="{{ asset('assets/js/file-upload.js') }}"></script>
    <script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>


    <script type="text/javascript">
        $(".cloader").hide('slow');
        $(document).ajaxStart(function() {
            $(".cloader").show();
        }).ajaxStop(function() {
            $(".cloader").hide('slow');
        });
        $(document).on('click', '#new_folder', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('newfolder') }}",
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add Folder");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
            return false
        });

        $(document).on('click', '#new_file', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('newfile') }}",
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add File");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                    $('#folder_id').val($('#current-id').val())
                }
            });
            return false
        });

        $(document).on('click', '.play-file', function(event) {
            event.preventDefault()
            path = $(this).data('path');
            name = $(this).data('name');
            $(".cloader").show();

            $.ajax({
                url: path,
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html(name);
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
            return false
        });

        $(document).on('click', '.share-file', function(event) {
            event.preventDefault()
            path = $(this).data('path');
            name = $(this).data('name');
            $(".cloader").show();

            $.ajax({
                url: path,
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html(name);
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
            return false
        });

        $(document).on('click', '#copyt', function(event) {
            event.preventDefault()

            $('#texttocopy').select();
            document.execCommand("copy");
            $temp.remove();


            // Create a "hidden" input
            var aux = document.createElement("input");

            // Assign it the value of the specified element
            aux.setAttribute("value", document.getElementById('texttocopy').innerHTML);

            // Append it to the body
            document.body.appendChild(aux);

            // Highlight its content
            aux.select();

            // Copy the highlighted text
            document.execCommand("copy");

            // Remove it from the body
            document.body.removeChild(aux);
            return false
        });

        //TODO :: change id
        $(document).on('click', '#images', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('images', auth()->user()->id) }}",
                success: function(response) {
                    //  console.log(response);
                    $("#files_container").html(response);
                    $('#new_folder').show()

                }
            });
            return false;
        });

        $(document).on('click', '#videos', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('videos', auth()->user()->id) }}",
                success: function(response) {
                    //  console.log(response);
                    $("#files_container").html(response);
                    $('#new_folder').show()

                }
            });
            return false;
        });

        $(document).on('click', '#home', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('files') }}",
                success: function(response) {
                    //  console.log(response);
                    $("#files_container").html(response);
                    $('#current-id').val(0)
                    $('#new_folder').show()

                }
            });
            return false;
        });

        $(document).on('click', '.view-folder', function(event) {
            event.preventDefault()
            path = $(this).data('path');
            id = $(this).data('id');
            $(".cloader").show();

            $.ajax({
                url: path,
                success: function(response) {
                    //  console.log(response);
                    $("#files_container").html(response);
                    $('#current-id').val(id)
                    $('#new_folder').hide()


                }
            });
            return false;
        });

        $(document).on('change', '#search', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "file/search/" + $('#search').val(),
                success: function(response) {
                    // alert('works')

                    //  console.log(response);
                    $("#files_container").html(response);

                }
            });
            return false;
        });

        $(document).on('click', '#docs', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('docs', auth()->user()->id) }}",
                success: function(response) {
                    //  console.log(response);
                    $("#files_container").html(response);
                    $('#new_folder').show()

                }
            });
            return false;
        });

        $(document).on('click', '#music', function(event) {
            event.preventDefault()
            $(".cloader").show();

            // window.addEventListener('load', function() {
            // }, false);
            $.ajax({
                url: "{{ route('music', auth()->user()->id) }}",
                success: function(response) {
                    //  console.log(response);
                    $("#files_container").html(response);
                    $('#new_folder').show()

                }
            });
            return false;
        });




        $(document).on('click', '.folder-edit', function(e) {
            e.preventDefault(); // Prevent the default link behavior

            var path = $(this).data('path'); // Get the path from the data-path attribute

            $.ajax({
                url: path,
                success: function(response) {
                    $(".modal-body").html(response);
                    $(".modal-title").html("Edit Folder");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
        });



        $(document).on('click', '.file-edit', function(e) {
            e.preventDefault(); // Prevent the default link behavior

            var path = $(this).data('path'); // Get the path from the data-path attribute

            $.ajax({
                url: path,
                success: function(response) {
                    $(".modal-body").html(response);
                    $(".modal-title").html("Edit File");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
        });

        $(document).on('click', '.folder-delete', function() {
            var id = $(this).attr("data-id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this attribute!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,


            }, function(willDelete) {
                if (willDelete) {

                    $.ajax({
                        type: "POST",
                        url: '{{ url('/') }}/folder/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swal({
                                title: response.msg
                            }, function(result) {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });

        $(document).on('click', '.file-delete', function() {
            var id = $(this).attr("data-id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this attribute!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                showCancelButton: true,


            }, function(willDelete) {
                if (willDelete) {

                    $.ajax({
                        type: "POST",
                        url: '{{ url('/') }}/file/delete/' + id,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            swal({
                                title: response.msg
                            }, function(result) {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
