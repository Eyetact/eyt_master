@extends('layouts.master')
@section('css')
    <!-- Data table css -->
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <!-- Slect2 css -->
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />
    <style>
        .multi-item {
            background: #e9e9e9;
            padding: 15px;
            margin: 15px 0;
            border: 1px dashed:#ddd;
        }

        .modal-xl {
            max-width: 1140px !important;
        }

        .attr_header {
            width: 100% !important;
        }

        #attr_tbl {
            cursor: move;
        }

        #tbl-field>tbody>tr>td {
            min-height: 97px;
            max-height: 97px;
            text-align: center !important;
            line-height: 51px;
        }

        #tbl-field>tbody>tr>td label {
            line-height: 1;
        }

        #tbl-field .form-check.form-switch {
            padding: 0;
            display: flex;
            align-items: center;
            /* justify-content: center; */
            min-height: 50px;
        }

        #tbl-field .form-check.form-switch div {
            margin: 0;
        }

        .input-box .google-input[disabled] {
            background: #f3f3f3;
        }
    </style>
@endsection
@section('page-header')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Attributes</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-layout mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Attributes</a></li>
            </ol>
        </div>
        <div class="page-rightheader">
            <div class="btn btn-list">
                <a id="add_new" href="#" class="btn btn-info" data-toggle="tooltip" title=""
                    data-original-title="Add new"><i class="fe fe-plus mr-1"></i> Add new </a>
            </div>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('content')
    <!-- Row -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Attributes Data</div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap" id="attribute_table">
                            <thead>
                                <tr>
                                    <th width="50px">No.</th>
                                    <th>Name</th>
                                    <th>Module</th>
                                    <th>Column Type</th>
                                    <th>Input Type</th>
                                    <th>Required</th>
                                    <th width="200px">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Row -->

    </div>
    </div><!-- end app-content-->
    </div>

    <div class="modal fade bd-example-modal-xl" id="role_form_modal" tabindex="-1" role="dialog"
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
@endsection
@section('js')
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

    <script type="text/javascript">
        $(document).on('click', '#edit_item', function() {
            // window.addEventListener('load', function() {

            // }, false);
            var path = $(this).data('path')
            $.ajax({
                url: path,
                success: function(response) {
                    console.log(path);
                    console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("edit Plan");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
        });
        $(document).on('click', '#add_new', function() {
            // window.addEventListener('load', function() {

            // }, false);
            $.ajax({
                url: "{{ route('attribute.create') }}",
                success: function(response) {
                    //  console.log(response);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add Attribute");
                    $("#role_form_modal").modal('show');
                }
            });
        });
        var table = $('#attribute_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('attribute.index') }}",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'module',
                    name: 'module'
                },
                {
                    data: 'type',
                    name: 'type'
                },
                {
                    data: 'input',
                    name: 'input'
                },
                {
                    data: 'required',
                    name: 'required'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            order: [
                [1, 'asc']
            ]
        });

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        $(document).on('click', '.toggle-btn', function(e) {
            var audit_typeId = $(this).attr("data-id");
            var currentState = $(this).attr('data-state');

            e.preventDefault();

            var toggleButton = $(this);
            swal({
                title: 'Confirm ' + currentState,
                text: 'Are you sure you want to ' + currentState + ' this attribute set?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirm',
            }, function(value) {
                if (value) {
                    $.ajax({
                        url: '{{ url('/') }}/attribute/' + audit_typeId + '/updateStatus',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            state: currentState
                        },
                        success: function(response) {
                            // Toggle the button state
                            if (currentState === 'disabled') {
                                toggleButton.data('state', 'enabled');
                                toggleButton.removeClass('btn-danger').addClass(
                                    'btn-success');
                                toggleButton.text('Enable');
                            } else {
                                toggleButton.data('state', 'disabled');
                                toggleButton.removeClass('btn-success').addClass(
                                    'btn-danger');
                                toggleButton.text('Disable');
                            }
                        },
                        error: function(xhr, status, error) {}
                    });
                }
            });
        });

        $(document).on('click', '.delete-attribute', function() {
            var id = $(this).attr("data-id");
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this attribute!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }, function(willDelete) {
                if (willDelete) {

                    $.ajax({
                        type: "GET",
                        url: '{{ url('/') }}/remove_attribute/' + id,
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

    @include('attribute.js.functions')

    <script>
        function generateNo() {
            let no = 1

            $('#tbl-field tbody tr').each(function(i) {
                $(this).find('td:nth-child(1)').html(no)
                if (i < 1) {
                    $(`.btn-delete:eq(${i})`).prop('disabled', true)
                } else {
                    $(`.btn-delete:eq(${i})`).prop('disabled', false)
                }
                no++
            })
        }

        $(document).on('click', '.btn-delete', function() {
            $(this).parent().parent().parent().remove()
            generateNo()
        })
        $(document).on('click', '#add_new_tr', function() {
            let table = $('#tbl-field tbody')

            let no = table.find('tr').length + 1

            let tr = `
            <tr draggable="true" containment="tbody" ondragstart="dragStart()" ondragover="dragOver()" style="cursor: move;">
                                            <td class="text-center">
                                                <div class="input-box">

                                                    ${no}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-box">
                                                    <input type="text" name="multi[${no}][name]"
                                                        class="form-control google-input"
                                                        placeholder="{{ __('Field Name') }}" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-box">
                                                    <select name="multi[${no}][type]"
                                                        class="form-select  google-input multi-type" required>
                                                        <option value="" disabled selected>
                                                            --{{ __('Select column type') }}--
                                                        </option>
                                                        <option value="text">Text</option>
                                                        <option value="textarea">Text Area</option>
                                                        <option value="texteditor">Text Editor</option>
                                                        <option value="text">Letters (a-z, A-Z) or Numbers (0-9)</option>
                                                        <option value="email">Email</option>
                                                        <option value="tel">Telepon</option>
                                                        <option value="url">Url</option>
                                                        <option value="search">Search</option>
                                                        <option value="number">Number</option>
                                                        <option value="number">Integer Number</option>
                                                        <option value="decimal">Decimal Number</option>
                                                        <option value="radio">Radio ( True, False )</option>
                                                        <option value="date">Date</option>
                                                        <option value="time">Time</option>
                                                        <option value="datalist">Datalist ( Year List )</option>
                                                        <option value="datetime-local">Datetime local</option>
                                                        <option value="select">Select</option>
                                                        <option value="foreignId">Lookup</option>
                                                        <option value="doubleMulti">Double Attribute</option>

                                                    </select>

                                                </div>
                                                <div class="select_options"></div>
                                            </td>





                                            <td>
                                                <div class="input-box">

                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-xs btn-delete">
                                                        x
                                                    </button>
                                                </div>
                                            </td>

                                        </tr>
            `

            table.append(tr)
        })

        $(document).on('change', '.select-module', function() {
            var id = $(this).find(':selected').data('id');
            var parent = $(this).parent().parent().parent().parent().find('.select_options');
            let index = parseInt($(this).parent().parent().parent().parent().parent().find('.text-center').find(
                '.input-box').html());
            // alert(index)
            $.ajax({
                url: '{{ url('/') }}/attribute-by-module/' + id,
                success: function(response) {
                    console.log(response);
                    parent.find('.child-drop').remove();
                    // parent.find('.condition-drop').remove();
                    parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="multi[${index}][attribute]" required>
                           ${response}
                        </select>
                    </div></div>`);

                    // if(index > 1)
                    if (1) {
                        // alert(id);

                        var listd = '';

//                         @foreach (App\Models\Attribute::where('type', 'foreignId')->get() as $it)

// listd +=
//     '<option value="{{ explode('_id', $it->code)[0] }}" >{{ $it->name }}</option>'
// @endforeach




                       if($('.multi-type').val() == 'foreignId')
                       {
                        $.ajax({
                url: '{{ url('/') }}/get-relations-multi/' + id,
                success: function(response) {
                    console.log(response);



                    parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="multi[${index}][source]" required>
                            <option value="disabled">disabled</option>
                            ${response}
                        </select>
                    </div></div>`);
                }
                });

            }


                    //     parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    // <div class="input-box form-on-update mt-2 form-on-update-foreign">
                    //     <select class="google-input " name="multi[${index}][source]" required>
                    //         <option value="disabled">disabled</option>
                    //         ${listd}
                    //     </select>
                    // </div></div>`);



                    if($('.secondary-drop').val() == 'lookprefix' || $('.secondary-drop').val() == 'looksuffix')
                       {

                        $.ajax({
                url: '{{ url('/') }}/get-belongs-to-multi/' + id,
                success: function(response) {
                    console.log(response);



                    parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input smodule2 " name="multi[${index}][source]" required>
                            <option value="disabled">disabled</option>
                            ${response}
                        </select>
                    </div></div>`);
                }
                });

            }


                    }
                }
            });
        })

        $(document).on('change','.smodule2',function(){



            $(this).closest('tr').find('.cd2').remove();
            var id = $(this).find(':selected').data('id');
            var parent = $(this).parent().parent().parent().parent().parent().find('.select_options');
            let index = parseInt($(this).parent().parent().parent().parent().parent().parent().find('.text-center').find(
                '.input-box').html());
            // alert(index)
            $.ajax({
                url: '{{ url('/') }}/attribute-by-module/' + id,
                success: function(response) {
                    console.log(response);

                    // parent.find('.condition-drop').remove();
                    parent.append(` <div class="input-box  form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input cd2" name="multi[${index}][attribute2]" required>
                           ${response}
                        </select>
                    </div></div>`);

                }
            });


        })




        $(document).on('change', '#module', function() {
            var id = $(this).find(':selected').val();
            // alert(index)
            $.ajax({
                url: '{{ url('/') }}/getsource/' + id,
                success: function(response) {
                    console.log(response);
                    $('#source').find('option').remove()
                    $('#source').append(`${response}`);
                }
            });
        })

        $(document).on('change', '#source', function() {
            var id = $(this).find(':selected').val();
            // alert(index)
            $.ajax({
                url: '{{ url('/') }}/gettarget/' + id,
                success: function(response) {
                    console.log(response);
                    $('#target').find('option').remove()
                    $('#target').append(`${response}`);
                }
            });
        })

        $(document).on('change', '#target', function() {
            var id = $(this).find(':selected').val();
            // alert(index)
            $('input[name=code]').val(id);
        })


        $(document).on('change', 'select[name=attribute]', function() {

            var selectedValue = $('.lookup-drop').val();
            var modifiedValue = selectedValue + '_' + $(this).val() + '_id';
            // alert(modifiedValue);

            $('.input-code').val(modifiedValue);
            $('.input-code').prop('readonly', true);

            // alert('hi 2');

            var list = `{!! $all !!}`;

            if ($('.form-input-types').val() == 'informatic' || $('.form-input-types').val() == 'doublefk') {





                $(`.options`).append(`
    <div class="input-box form-constrain fkey2 mt-2">
        <div class="input-box form-on-update mt-2 form-on-update-foreign">
            <select class="google-input lookup-drop2"  name="constrains2" required>
               ${list}
            </select>
        </div>
        <small class="text-secondary">
            <ul class="my-1 mx-2 p-0">
                <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                <li>Field name must be related model + "_id", e.g.: user_id</li>
            </ul>
        </small>
    </div>
    <div class="input-box form-foreign-id mt-2">
        <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
    </div>

    <input type="hidden" name="on_update_foreign" class="google-input" value="1">

    <input type="hidden" name="on_delete_foreign" class="google-input" value="1">


`);


            }

//             if ($('.fktype-radio:checked').val() == 'based') {





//                 var id = $('.lookup-drop').find(':selected').data('id');
//                 $.ajax({
//                     url: '{{ url('/') }}/get-relations-modules/' + id,
//                     success: function(response) {
//                         console.log(response);




//                         $(`.options`).append(`
//     <div class="input-box form-constrain fkey2 mt-2">
//         <div class="input-box form-on-update mt-2 form-on-update-foreign">
//             <select class="google-input lookup-drop2"  name="constrains2" required>
//                ${response}
//             </select>
//         </div>
//         <small class="text-secondary">
//             <ul class="my-1 mx-2 p-0">
//                 <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
//                 <li>Field name must be related model + "_id", e.g.: user_id</li>
//             </ul>
//         </small>
//     </div>
//     <div class="input-box form-foreign-id mt-2">
//         <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
//     </div>

//     <input type="hidden" name="on_update_foreign" class="google-input" value="1">

//     <input type="hidden" name="on_delete_foreign" class="google-input" value="1">


// `);


//                     }
//                 });

//             }

            if ($('.secondary-drop').val() == 'lookprefix' || $('.secondary-drop').val() == 'looksuffix') {

                var id = $('.lookup-drop').find(':selected').data('id');
                $.ajax({
                    url: '{{ url('/') }}/get-belongs-to/' + id,
                    success: function(response) {
                        console.log(response);


                        $(`.options`).append(`
<div class="input-box form-constrain fkey2 mt-2">
<div class="input-box form-on-update mt-2 form-on-update-foreign">
<select class="google-input lookup-drop2"  name="constrains2" required>
${response}
</select>
</div>
<small class="text-secondary">
<ul class="my-1 mx-2 p-0">
<li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
<li>Field name must be related model + "_id", e.g.: user_id</li>
</ul>
</small>
</div>
<div class="input-box form-foreign-id mt-2">
<input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
</div>

<input type="hidden" name="on_update_foreign" class="google-input" value="1">

<input type="hidden" name="on_delete_foreign" class="google-input" value="1">


`);


                    }
                });

            }

        });
        $(document).on('change', '.lookup-drop', function() {
            $('.cb').remove()
            $('.child-drop').remove();
            $('.cond-wrapper').remove();


            if ($(`.secondary-drop`).val() == "looksuffix" || $(`.secondary-drop`).val() == "lookprefix")

            {
                $('.fkey2').hide();
                $('.child-drop2').hide()


                $('select[name=attribute]').trigger('change');

            }

            if ($('.fktype-radio:checked').val() == 'based') {

                $('.lookup-drop2').remove();
                $('.child-drop2').remove();
                $('.cond2-wrapper').remove();

                $('.based-cond').remove()

                $('.condition-drop').remove()

                $('.condition-drop').trigger('change');

                $('select[name=attribute]').trigger('change');
            }

            var id = $(this).find(':selected').data('id');

            var parent = $(this).parent().parent().parent().parent().find('.options');
            $.ajax({
                url: '{{ url('/') }}/attribute-by-module/' + id,
                success: function(response) {
                    console.log(response);
                    if ($('.form-input-types').val() == 'informatic' || $('.form-input-types')
                        .val() ==
                        'doublefk' || $('.form-input-types').val() == 'doubleattr') {
                        parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="attribute" required>
                           ${response}
                        </select>
                    </div></div>



                    `);



                    }


                    if ($('.fktype-radio:checked').val() == 'based') {


                        $(`.options`).append(` <div class="input-box cond-wrapper form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input condition-drop" name="condition_attr" required>


                            ${response}

                        </select>
                    </div></div>
                    `);


                    }


                    if ($('.fktype-radio:checked').val() == 'condition') {

                        parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="attribute" required>
                           ${response}
                        </select>
                    </div></div>


                    <div class="form-group col-sm-4 cond-multiple cb">
                    <label class="custom-switch form-label">
                        <input type="checkbox" name="multiple" class="custom-switch-input" id="multiple">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Multiple</span>
                    </label>
                </div>

                    `);

                        $('.child-drop').hide();
                        $('.cond2-wrapper').remove();


                        $('.input-code').val('');

                        var selectedValue = $('.lookup-drop').val();
                        var modifiedValue = selectedValue + '_id';
                        // alert(modifiedValue);

                        $('.input-code').val(modifiedValue);
                        $('.input-code').prop('readonly', true);

                        parent.append(` <div class="input-box cond-wrapper form-constrain mt-2">
<div class="input-box form-on-update mt-2 form-on-update-foreign">
<select class="google-input condition-drop" name="condition_attr" required>
   ${response}
</select>
</div></div>
`);

                    }

                    if ($('.form-input-types').val() != 'informatic' && $('.form-input-types').val() !=
                        'doublefk' && $('.form-input-types').val() != 'doubleattr' && $(
                            '.fktype-radio:checked').val() != 'based' && $('.fktype-radio:checked')
                        .val() != 'condition') {
                        parent.append(` <div class="input-box child-drop form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="attribute" required>
                           ${response}
                        </select>
                    </div></div>


                    <div class="form-group col-sm-4 cb">
                    <label class="custom-switch form-label">
                        <input type="checkbox" name="multiple" class="custom-switch-input" id="multiple">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Multiple</span>
                    </label>
                </div>
                    `);


                        if ($('.form-input-types').val() == 'condition') {



                            parent.append(` <div class="input-box cond-wrapper form-constrain mt-2">
<div class="input-box form-on-update mt-2 form-on-update-foreign">
<select class="google-input condition-drop" name="condition_attr" required>
   ${response}
</select>
</div></div>
`);

                        }


                    }





                    var selectedValue = $('.lookup-drop').val();
                    var modifiedValue = selectedValue + '_id';
                    // alert(modifiedValue);

                    $('.input-code').val(modifiedValue);
                    $('.input-code').prop('readonly', true);



                }
            });
        })

        $(document).on('change', '.lookup-drop2', function() {

            var id = $(this).find(':selected').data('id');

            var parent = $(this).parent().parent().parent().parent().find('.options');
            $.ajax({
                url: '{{ url('/') }}/attribute-by-module/' + id,
                success: function(response) {
                    console.log(response);


                    if ($('.form-input-types').val() == 'doubleattr') {

                        parent.append(` <div class="input-box child-drop2 form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="attribute2" required>
                           ${response}
                        </select>
                    </div></div>



                    `);

                    }

                    if ($('.form-input-types').val() != 'doubleattr') {

                        parent.append(` <div class="input-box child-drop based-cond form-constrain mt-2">
<div class="input-box form-on-update mt-2 form-on-update-foreign">
    <select class="google-input " name="attribute2" required>
       ${response}
    </select>
</div></div>



`);

                    }

                    // if( $('.fktype-radio:checked').val() == 'based')
                    // {

                    //   $('.based-cond').hide()
                    //   $('.cond2-wrapper').remove();
                    //   $('.condition-drop').remove()



                    //     parent.append(` <div class="input-box cond-wrapper form-constrain mt-2">
                // <div class="input-box form-on-update mt-2 form-on-update-foreign">
                //     <select class="google-input condition-drop" name="condition_attr" required>
                //        ${response}
                //     </select>
                // </div></div>
                // `);



                    // }

                    if ($('.form-input-types').val() == 'condition') {

                        parent.append(` <div class="input-box cond-wrapper form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input condition-drop" name="condition_attr" required>
                           ${response}
                        </select>
                    </div></div>
                    `);

                    }



                }

            });
        })


        $(document).on('change', '.condition-drop', function() {
            $('.cond2-wrapper').remove()
            var conditionId = $(this).find(':selected').val();
            var modelId = $('.lookup-drop').find(':selected').data('id');

            var parent = $(this).parent().parent().parent().parent().find('.options');



            if ($('.fktype-radio:checked').val() == 'condition') {

                // alert($('.condition-drop').val())

                $('.cond2-wrapper').remove();

                $('.child-drop').val($('.condition-drop').val());

                $('.input-code').val('')

                var selectedValue = $('.lookup-drop').val();
                var modifiedValue = selectedValue + '_' + $('.child-drop').val() + '_id';
                // alert(modifiedValue);

                $('.input-code').val(modifiedValue);
                $('.input-code').prop('readonly', true);
            }


            if ($('.fktype-radio:checked').val() == 'based')

            {



                $('.input-code').val('');
                var selectedValue = $('.lookup-drop').val();
                var modifiedValue = selectedValue + '_' + $(this).val() + '_id';

                $('.input-code').val(modifiedValue);
                $('.input-code').prop('readonly', true);




                var id = $('.lookup-drop').find(':selected').data('id');
                $.ajax({
                    url: '{{ url('/') }}/get-relations-modules/' + id,
                    success: function(response) {
                        console.log(response);




                        $(`.options`).append(`
    <div class="input-box form-constrain fkey2 mt-2">
        <div class="input-box form-on-update mt-2 form-on-update-foreign">
            <select class="google-input lookup-drop2"  name="constrains2" required>
               ${response}
            </select>
        </div>
        <small class="text-secondary">
            <ul class="my-1 mx-2 p-0">
                <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                <li>Field name must be related model + "_id", e.g.: user_id</li>
            </ul>
        </small>
    </div>
    <div class="input-box form-foreign-id mt-2">
        <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
    </div>

    <input type="hidden" name="on_update_foreign" class="google-input" value="1">

    <input type="hidden" name="on_delete_foreign" class="google-input" value="1">


`);


                    }
                });



            }

            $.ajax({
                url: '{{ url('/') }}/data-by-module/' + modelId + '/' + conditionId,
                success: function(response) {
                    console.log(response);

                    parent.append(` <div class="input-box child cond2-wrapper form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input " name="condition_value[]" required multiple>
                           ${response}
                        </select>
                    </div></div>



                    `);

                }
            });


        })
        $(document).on('change', '.multi-type', function() {
            let index = parseInt($(this).parent().parent().parent().find('.text-center').find('.input-box')
                .html());
            // alert(index);
            $(this).parent().parent().find('.c-f').remove()
            $(this).parent().parent().find('.child-drop').remove()
            $(this).parent().parent().find('.s-option').remove()
            $(this).parent().parent().find('.select_options').html("")
            if ($(this).val() == 'select') {
                $(this).parent().parent().find('.select_options').append(`<div class="input-box s-option mt-2">
                <input type="text" name="multi[${index}][select_options]" class="google-input" placeholder="Seperate with '|', e.g.: water|fire">
            </div>`);
            }
            else if ($(this).val() == 'doubleMulti') {




                $(this).parent().parent().find('.select_options').append(`

                <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input primary-drop"  name="multi[${index}][primary]" required>
                            <option value="" disabled selected>-- Select primary attribute --</option>
                                  <option value="text">Text</option>
                                  <option value="integer">Integer Number</option>
                                  <option value="decimal">Decimal Number</option>
                                  <option value="select">Select</option>
                                  <option value="lookup">Lookup</option>
                        </select>
                    </div>
                    </div>

                    <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input secondary-drop"  name="multi[${index}][secondary]" required>
                            <option value="" disabled selected>-- Select secondary attribute --</option>
                                  <option value="prefix">fixed prefix</option>
                                  <option value="suffix">fixed suffix</option>


                        </select>
                    </div>
                    </div>

                    <div class="col-sm-12 input-box fixed-val">
                            <label class="form-label" for="fixed_value">Fixed value<span class="text-red">*</span></label>
                            <input type="text" name="multi[${index}][fixed_value]" id="fixed_value"
                                class="google-input @error('fixed_value') is-invalid @enderror"
                                value="">
                            @error('fixed_value')
                                <span class="error name-error">{{ $message }}</span>
                            @enderror
                        </div>

              `);

            }



            else if ($(this).val() == 'foreignId') {
                var list = `{!! $all !!}`;



                $(this).parent().parent().find('.select_options').append(` <div class="input-box c-f form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input select-module" name="multi[${index}][constrain]" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>

              `);

            }

        })


        $(document).on('change', '.secondary-drop', function() {


            if ($(`.secondary-drop`).val() == "looksuffix" || $(`.secondary-drop`).val() == "lookprefix") {

                // alert('hi');
                $('.fixed-val').hide();


                $('.fkey2').hide();


                $('.child-drop2').hide()
                $('select[name=attribute]').trigger('change');

            } else {


                $('.fkey2').hide();

                $('.fixed-val').show();
                $('.child-drop2').hide()
            }

        })

        $(document).on('change', '.primary-drop', function() {

            // alert($('.primary-drop').val());

            if ($(`.primary-drop`).val() == "select") {


            if($('.form-input-types').val() == 'doubleattr'){
                $(`.form-min-lengths`).hide()
                $(`.form-max-lengths`).hide()
                $('.fkey').hide();
                $('.fkey2').hide();
                $('select[name=attribute]').hide()
                $('.child-drop').hide()

                $('.fixed-val').show();

                $('.secondary-drop option[value="lookprefix"]').remove();
                $('.secondary-drop option[value="looksuffix"]').remove();

                $(`.options`).append(`
<div class="option_fields select-drop mt-5">
    <div class="form-group col-sm-12">
                        <label class="custom-switch form-label">
                            <input type="checkbox" name="is_multi" class="custom-switch-input" id="multi-select" >
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Multi Select</span>
                        </label>
                    </div>

            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap table-light draggable-table"
                    id="type_options">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th></th>
                            <th class="text-white">Is Default</th>
                            <th class="text-white">Label</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4"><button id="addRow" type="button"
                                    class="btn btn-info">Add Option</button></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
`)
            }

             if($('.form-input-types').val() == 'multi'){

                let index = parseInt($(this).parent().parent().parent().parent().parent().find('.text-center').find('.input-box')
                .html());
                // alert(index);

                $(this).closest('tr').find('.child-drop').remove();
                $(this).closest('tr').find('.select-module').remove();
                $(this).closest('tr').find('.smodule2').remove();
                $(this).closest('tr').find('.cd2').remove();




                $(this).closest('tr').find('.select_options').append(`<div class="input-box s-option mt-2">
                <input type="text" name="multi[${index}][select_options]" class="google-input" placeholder="Seperate with '|', e.g.: water|fire">
            </div>`);
            }

        }

            if ($(`.primary-drop`).val() == "lookup") {


                 if($('.form-input-types').val() == 'doubleattr')
                 {

                $('.select-drop').hide();

                $(`.form-min-lengths`).hide()
                $(`.form-max-lengths`).hide()



                $(`.secondary-drop`).append(`
                <option value="lookprefix">Lookup prefix</option>
                                  <option value="looksuffix">Lookup suffix</option>

                                  `)

                var list = `{!! $all !!}`;
                // alert( list )

                $(`.options`).append(`
                <div class="input-box form-constrain fkey mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">


            `)
                 }

                 if($('.form-input-types').val() == 'multi'){

                    $(this).closest('tr').find('.s-option').remove();

                    $(`.secondary-drop`).append(`
                <option value="lookprefix">Lookup prefix</option>
                                  <option value="looksuffix">Lookup suffix</option>

                                  `)

                    var list = `{!! $all !!}`;
                    let index = parseInt($(this).parent().parent().parent().parent().parent().find('.text-center').find('.input-box')
                .html());

                alert(index);


                $(this).closest('tr').find('.select_options').append(` <div class="input-box c-f form-constrain mt-2">
    <div class="input-box form-on-update mt-2 form-on-update-foreign">
        <select class="google-input select-module" name="multi[${index}][constrain]" required>
           ${list}
        </select>
    </div>
    <small class="text-secondary">
        <ul class="my-1 mx-2 p-0">
            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
            <li>Field name must be related model + "_id", e.g.: user_id</li>
        </ul>
    </small>
</div>

`);


                 }
            }




            if ($(`.primary-drop`).val() != "select" && $(`.primary-drop`).val() != "lookup") {

                $('.select-drop').hide();
                $('.fkey').hide();
                $('.fkey2').hide();
                $('.child-drop').hide()
                $('select[name=attribute]').hide()

                $(`.form-min-lengths`).show()
                $(`.form-max-lengths`).show()

                $('.secondary-drop option[value="lookprefix"]').remove();
                $('.secondary-drop option[value="looksufffix"]').remove();



                if($('.form-input-types').val() == 'multi'){

                $(this).closest('tr').find('.child-drop').remove();
                $(this).closest('tr').find('.select-module').remove();
                $(this).closest('tr').find('.smodule2').remove();
                $(this).closest('tr').find('.cd2').remove();
                $(this).closest('tr').find('.s-option').remove();
                }

            }

        })

        $(document).on('change', '.form-column-types', function() {
            // alert($(this).val())
            var index = 0;
            let switchRequired = $(`.switch-requireds`)

            switchRequired.prop('checked', true)
            switchRequired.prop('disabled', false)

            $('.input-code').prop('readonly', false);



            $(`.form-default-value`).remove()
            $(`.custom-values`).append(`
            <div class="form-group form-default-value ">
                <input type="hidden" name="default_values">
            </div>
        `)

            if ($(this).val() == 'enum') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                if ($(`.form-input-types`).val() == "select") {
                    $(`.options`).append(`
            <div class="option_fields mt-5">
                <div class="form-group col-sm-12">
                                    <label class="custom-switch form-label">
                                        <input type="checkbox" name="is_multi" class="custom-switch-input" id="multi-select" >
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Multi Select</span>
                                    </label>
                                </div>

                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap table-light draggable-table"
                                id="type_options">
                                <thead class="bg-gray-700 text-white">
                                    <tr>
                                        <th></th>
                                        <th class="text-white">Is Default</th>
                                        <th class="text-white">Label</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4"><button id="addRow" type="button"
                                                class="btn btn-info">Add Option</button></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            `)
                }

                if ($(`.form-input-types`).val() == "radioselect") {
                    $(`.options`).append(`
            <div class="option_fields mt-5">

                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap table-light draggable-table"
                                id="type_options">
                                <thead class="bg-gray-700 text-white">
                                    <tr>
                                        <th></th>
                                        <th class="text-white">Is Default</th>
                                        <th class="text-white">Label</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4"><button id="addRow" type="button"
                                                class="btn btn-info">Add Option</button></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            `)
                }


            } else if ($(this).val() == 'multi') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`
                <div class="multi-options">
                                <div class="attr_header row flex justify-content-end my-5 align-items-end">
                                    <input title="Reset form" class="btn btn-success" id="add_new_tr" type="button"
                                        value="+ Add">
                                </div>

                                <table class="table table-bordered align-items-center mb-0" id="tbl-field">
                                    <thead>
                                        <tr>
                                            <th width="30">#</th>
                                            <th>{{ __('Field name') }}</th>
                                            <th>{{ __('Column Type') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr draggable="true" containment="tbody" ondragstart="dragStart()"
                                            ondragover="dragOver()" style="cursor: move;">
                                            <td class="text-center">
                                                <div class="input-box">

                                                1
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-box">
                                                    <input type="text" name="multi[1][name]"
                                                        class="form-control google-input"
                                                        placeholder="{{ __('Field Name') }}" required>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-box">
                                                    <select name="multi[1][type]"
                                                        class="form-select  google-input multi-type" required>
                                                        <option value="" disabled selected>
                                                            --{{ __('Select column type') }}--
                                                        </option>
                                                        <option value="text">Text</option>
                                                        <option value="text">Letters (a-z, A-Z) or Numbers (0-9)</option>
                                                        <option value="textarea">Text Area</option>
                                                        <option value="texteditor">Text Editor</option>
                                                        <option value="email">Email</option>
                                                        <option value="tel">Telepon</option>
                                                        <option value="url">Url</option>
                                                        <option value="search">Search</option>
                                                        <option value="number">Number</option>
                                                        <option value="number">Integer Number</option>
                                                        <option value="decimal">Decimal Number</option>
                                                        <option value="radio">Radio ( True, False )</option>
                                                        <option value="date">Date</option>
                                                        <option value="time">Time</option>
                                                        <option value="select">Select</option>
                                                        <option value="foreignId">Lookup</option>
                                                        <option value="doubleMulti">Double Attribute</option>

                                                    </select>
                                                </div>
                                                <div class="select_options"></div>
                                            </td>





                                            <td>
                                                <div class="input-box">

                                                    <button type="button"
                                                        class="btn btn-outline-danger btn-xs btn-delete">
                                                        x
                                                    </button>
                                                </div>
                                            </td>

                                        </tr>




                                    </tbody>
                                </table>
                            </div>
                `)

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="radio">Radio</option>
            //     <option value="datalist">Datalist</option>
            // `)


            } else if ($(this).val() == 'date') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="date">Date</option>
            //     <option value="month">Month</option>
            // `)

            } else if ($(this).val() == 'time') {
                checkMinAndMaxLength(index)
                removeAllInputHidden(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="time">Time</option>
            // `)

                $(`.form-min-lengths`).prop('readonly', true).hide()
                $(`.form-max-lengths`).prop('readonly', true).hide()
                $(`.form-min-lengths`).val('')
                $(`.form-max-lengths`).val('')

            } else if ($(this).val() == 'year') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="datalist">Datalist</option>
            // `)

            } else if ($(this).val() == 'dateTime') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="datetime-local">Datetime local</option>
            // `)

            } else if ($(this).val() == 'foreignId') {
                // alert('hi');
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                $('.source-card').show();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`
                <input type="hidden" name="select_options" class="form-option">
            `)

                // var list = `<option>aaaa</option>`;
                var list = `{!! $all !!}`;
                // alert( list )

                $(`.options`).append(`
                <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">


            `)

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="datalist">Datalist</option>
            // `)

            } else if ($(this).val() == 'fk') {
                // alert('hi');
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`

                        <div class="col-md-12 fktypes">
        <p>Type</p>
        <div class="custom-controls-stacked">
            <label class="custom-control custom-radio" for="basic">


                    <input class="custom-control-input fktype-radio" type="radio"
                        name="fk_type" id="basic" value="basic"
                       required>

                <span class="custom-control-label">Basic</span>

            </label>
        </div>

        <div class="custom-controls-stacked">
            <label class="custom-control custom-radio" for="condition">


                    <input class="custom-control-input fktype-radio" type="radio"
                        name="fk_type" id="condition" value="condition"
                       required>

                <span class="custom-control-label">Condition</span>

            </label>
        </div>

        <div class="custom-controls-stacked">
            <label class="custom-control custom-radio" for="based">


                    <input class="custom-control-input fktype-radio" type="radio"
                        name="fk_type" id="based" value="based"
                       required>

                <span class="custom-control-label">Based On</span>

            </label>
        </div>

    </div>
            `)

            } else if ($(this).val() == 'informatic') {
                // alert('hi');
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`
                <input type="hidden" name="select_options" class="form-option">
            `)

                // var list = `<option>aaaa</option>`;
                var list = `{!! $all !!}`;
                // alert( list )

                $(`.options`).append(`
                <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">





            `)

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="datalist">Datalist</option>
            // `)

            } else if ($(this).val() == 'doublefk') {
                // alert('hi');
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`
                <input type="hidden" name="select_options" class="form-option">
            `)

                // var list = `<option>aaaa</option>`;

                var list = `{!! $all !!}`;
                // alert( list )

                $(`.options`).append(`
                <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">





            `)

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="datalist">Datalist</option>
            // `)

            } else if ($(this).val() == 'doubleattr') {
                // alert('hi');
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`
                <input type="hidden" name="select_options" class="form-option">
            `)

                // var list = `<option>aaaa</option>`;


                // alert( list )

                $(`.options`).append(`
                <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input primary-drop"  name="primary" required>
                            <option value="" disabled selected>-- Select primary attribute --</option>
                                  <option value="text">Text</option>
                                  <option value="integer">Integer Number</option>
                                  <option value="decimal">Decimal Number</option>
                                  <option value="select">Select</option>
                                  <option value="lookup">Lookup</option>
                        </select>
                    </div>
                    </div>

                    <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input secondary-drop"  name="secondary" required>
                            <option value="" disabled selected>-- Select secondary attribute --</option>
                                  <option value="prefix">fixed prefix</option>
                                  <option value="suffix">fixed suffix</option>


                        </select>
                    </div>
                    </div>

                    <div class="col-sm-12 input-box fixed-val">
                            <label class="form-label" for="fixed_value">Fixed value<span class="text-red">*</span></label>
                            <input type="text" name="fixed_value" id="fixed_value"
                                class="google-input @error('fixed_value') is-invalid @enderror"
                                value="">
                            @error('fixed_value')
                                <span class="error name-error">{{ $message }}</span>
                            @enderror
                        </div>


            `)



                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="datalist">Datalist</option>
            // `)

            } else if ($(this).val() == 'condition') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                $('.source-card').show();
                $('.deff-class').hide();

                $(`.form-option`).remove()

                $(`.options`).append(`
                <input type="hidden" name="select_options" class="form-option">
            `)

                // var list = `<option>aaaa</option>`;
                var list = `{!! $all !!}`;
                // alert( list )

                $(`.options`).append(`
                <div class="input-box form-constrain mt-2">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">


            `)

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="datalist">Datalist</option>
            // `)

            } else if (
                $(this).val() == 'text' ||
                $(this).val() == 'longText' ||
                $(this).val() == 'mediumText' ||
                $(this).val() == 'tinyText' ||
                $(this).val() == 'string'
            ) {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="text">Text</option>
            //     <option value="textarea">Textarea</option>
            //     <option value="email">Email</option>
            //     <option value="tel">Telepon</option>
            //     <option value="password">Password</option>
            //     <option value="url">Url</option>
            //     <option value="search">Search</option>
            //     <option value="file">File</option>
            //     <option value="hidden">Hidden</option>
            //     <option value="no-input">No Input</option>
            // `)

            } else if (
                $(this).val() == 'integer' ||
                $(this).val() == 'mediumInteger' ||
                $(this).val() == 'bigInteger' ||
                $(this).val() == 'decimal' ||
                $(this).val() == 'double' ||
                $(this).val() == 'float' ||
                $(this).val() == 'tinyInteger'
            ) {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();
                $('.deff-class').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="number">Number</option>
            //     <option value="range">Range</option>
            //     <option value="hidden">Hidden</option>
            //     <option value="no-input">No Input</option>
            // `)

            } else if ($(this).val() == 'boolean') {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)
                $('.source-card').hide();

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="select">Select</option>
            //     <option value="radio">Radio</option>
            // `)

            } else {
                removeAllInputHidden(index)
                checkMinAndMaxLength(index)
                addColumTypeHidden(index)

                //     $(`.form-input-types`).html(`
            //     <option value="" disabled selected>-- Select input type --</option>
            //     <option value="text">Text</option>
            //     <option value="email">Email</option>
            //     <option value="tel">Telepon</option>
            //     <option value="url">Url</option>
            //     <option value="week">Week</option>
            //     <option value="color">Color</option>
            //     <option value="search">Search</option>
            //     <option value="file">File</option>
            //     <option value="hidden">Hidden</option>
            //     <option value="no-input">No Input</option>
            // `)
            }
        });


        $(document).on('change', '.fktype-radio', function() {

                // alert($('.fktype-radio:checked').val());

                if ($('.fktype-radio:checked').val() == 'basic') {

                    $('.fkey1').hide();
                    $('select[name=attribute]').hide();
                    $('.cond-multiple').hide()

                    // $('.options').remove();


                    $('.form-column-types').val('fk').trigger('change');

                    $('.fktypes:last-child').remove();


                    $(`.options`).append(`
                        <input type="hidden" name="select_options" class="form-option">
                    `)

                    // var list = `<option>aaaa</option>`;
                    var list = `{!! $all !!}`;
                    // alert( list )

                    $(`.options`).append(`
                <div class="input-box form-constrain mt-2 fkey1">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">



            `);


                }

                if ($('.fktype-radio:checked').val() == 'condition') {

                    $('.fkey1').hide();
                    $('select[name=attribute]').hide();

                    // $('.options').remove();


                    $('.form-column-types').val('fk').trigger('change');

                    $('.fktypes:last-child').remove();

                    $(`.options`).append(`
                        <input type="hidden" name="select_options" class="form-option">
                    `)

                    // var list = `<option>aaaa</option>`;
                    var list = `{!! $all !!}`;
                    // alert( list )

                    $(`.options`).append(`
                <div class="input-box form-constrain mt-2 fkey1">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">



            `);


                }


                if ($('.fktype-radio:checked').val() == 'based') {

                    $('.fkey1').hide();
                    $('select[name=attribute]').hide();
                    $('.cond-multiple').hide()

                    // $('.options').remove();


                    $('.form-column-types').val('fk').trigger('change');

                    $('.fktypes:last-child').remove();


                    $(`.options`).append(`
                <input type="hidden" name="select_options" class="form-option">
            `)

                    // var list = `<option>aaaa</option>`;

                    var list = `{!! $all !!}`;
                    // alert( list )

                    $(`.options`).append(`
                <div class="input-box form-constrain mt-2 fkey1">
                    <div class="input-box form-on-update mt-2 form-on-update-foreign">
                        <select class="google-input lookup-drop"  name="constrains" required>
                           ${list}
                        </select>
                    </div>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids" class="google-input" placeholder="Foreign key (optional)">
                </div>

                <input type="hidden" name="on_update_foreign" class="google-input" value="1">

                <input type="hidden" name="on_delete_foreign" class="google-input" value="1">





            `)


                }


            }


        )

        $(document).on('change', '.switch-requireds', function() {
            let index = 0;
            $(`.form-default-value`).remove()
            // alert($('.form-input-types').val());


            let inputTypeDefaultValue = setInputTypeDefaultValue(index)

            if ($(this).is(':checked')) {
                $(`.custom-values`).append(`
                <div class="input-boc form-default-value ">
                    <input type="hidden" name="default_values">
                </div>
            `)
            } else {

                if ($('.form-input-types').val() == 'switch')

                {


                    $(`.custom-values`).append(`
                <div class="input-box form-default-value ">
                    <input type="${inputTypeDefaultValue}" readonly name="default_values" class="google-input" placeholder="Default Value (optional)">
                </div>
            `)
                } else {
                    $(`.custom-values`).append(`
                <div class="input-box form-default-value ">
                    <input type="${inputTypeDefaultValue}" name="default_values" class="google-input" placeholder="Default Value (optional)">
                </div>
            `)
                }
            }
        })

        $(document).on('change', '.form-input-types', function() {
            // alert('hi');
            // alert('welocme');
            let index = 0
            let minLength = $(`.form-min-lengths`)
            let maxLength = $(`.form-max-lengths`)
            let switchRequired = $(`.switch-requireds`)


            removeInputTypeHidden(index)
            switchRequired.prop('checked', true)
            switchRequired.prop('disabled', false)


            $(`.form-default-value`).remove()
            $(`.options`).html('')
            $(`.custom-value`).append(`
            <div class="form-group form-default-value ">
                <input type="hidden" name="default_values">
            </div>
        `)

            switch ($(this).val()) {
                case 'text':
                case 'textarea':
                case 'texteditor':
                case 'file':
                case 'image':
                case 'email':
                case 'tel':
                case 'password':
                case 'url':
                case 'search':
                    $('#type').val('text').trigger('change')
                    break;
                case 'decimal':
                case 'number':
                case 'range':
                    $('#type').val('double').trigger('change')
                    break;
                case 'radio':
                case 'switch':
                    $('#type').val('boolean').trigger('change')
                    break;
                case 'date':
                case 'month':
                    $('#type').val('date').trigger('change')
                    break;
                case 'time':
                    $('#type').val('time').trigger('change')
                    break;
                case 'datalist':
                    $('#type').val('year').trigger('change')
                    break;
                case 'datetime-local':
                    $('#type').val('dateTime').trigger('change')
                    break;
                case 'select':
                case 'radioselect':
                    $('#type').val('enum').trigger('change')
                    break;
                case 'multi':
                    $('#type').val('multi').trigger('change')
                    break;
                case 'foreignId':
                    $('#type').val('foreignId').trigger('change')
                    break;
                case 'fk':
                    $('#type').val('fk').trigger('change')
                    break;

                case 'informatic':
                    $('#type').val('informatic').trigger('change')
                    break;
                case 'doublefk':
                    $('#type').val('doublefk').trigger('change')
                    break;
                case 'doubleattr':
                    $('#type').val('doubleattr').trigger('change')
                    break;
                case 'condition':
                    $('#type').val('condition').trigger('change')
                    break;
                case 'assign':
                    $('#type').val('assign').trigger('change')
                    break;


                default:
                    break;
            }

            if ($(this).val() == 'file') {
                minLength.prop('readonly', true).hide()
                maxLength.prop('readonly', true).hide()
                minLength.val('')
                maxLength.val('')
                $('#deff').hide();

                $(`.input-options`).append(`
            <div class="input-box mt-2 form-file-types">

            <input type="hidden" name="file_types" value="file" >

            </div>
            <div class="input-box form-file-sizes">
                <input type="number" name="files_sizes" class="google-input" placeholder="Max size(kb), e.g.: 1024" required>
            </div>
            <input type="hidden" name="mimes" class="form-mimes">

            <input type="hidden" name="steps" class="form-step" >
            `)
                return;

            }
            if ($(this).val() == 'image') {
                minLength.prop('readonly', true).hide()
                maxLength.prop('readonly', true).hide()
                minLength.val('')
                maxLength.val('')

                $(`.input-options`).append(`
            <div class="input-box mt-2 form-file-types">
                <input type="hidden" name="file_types" value="image" >

            </div>
            <div class="input-box form-file-sizes">
                <input type="number" name="files_sizes" class="google-input" placeholder="Max size(kb), e.g.: 1024" required>
            </div>
            <input type="hidden" name="mimes" class="form-mimes">
            <input type="hidden" name="steps" class="form-step">
            `)

            } else if (
                $(this).val() == 'email' ||
                $(this).val() == 'select' ||
                $(this).val() == 'radioselect' ||
                $(this).val() == 'datalist' ||
                $(this).val() == 'radio' ||
                $(this).val() == 'date' ||
                $(this).val() == 'month' ||
                $(this).val() == 'password' ||
                $(this).val() == 'number'
            ) {
                minLength.prop('readonly', true).hide()
                maxLength.prop('readonly', true).hide()
                minLength.val('')
                maxLength.val('')

                addInputTypeHidden(index)

            } else if ($(this).val() == 'text' || $(this).val() == 'tel') {
                minLength.prop('readonly', false).show()
                maxLength.prop('readonly', false).show()

                addInputTypeHidden(index)

            } else if ($(this).val() == 'switch') {
                $(`.input-options`).append(`
                <div class="form-group col-sm-4 deff-class" style="margin-top:12px;">
                    <label class="custom-switch form-label">
                        <input type="checkbox" name="deff" class="custom-switch-input" id="deff"
                           >
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Default Value</span>
                    </label>
                </div>
            `)

                minLength.prop('readonly', true).hide()
                maxLength.prop('readonly', true).hide()
                minLength.val('')
                maxLength.val('')

                addInputTypeHidden(index)


                $('input[name="default_values"]').val('0');


                $('#deff').change(function() {

                    if ($(this).is(':checked')) {

                        // $('.switch-requireds').val(0).trigger('change');
                        $('input[name="default_values"]').val('1');
                    } else {
                        // $('.switch-requireds').val(1).trigger('change');

                        $('input[name="default_values"]').val('0');
                    }
                });




            } else if ($(this).val() == 'range') {
                $(`.input-options`).append(`
                <div class="input-box form-step mt-4">
                    <input type="number" name="steps" class="google-input" placeholder="Step (optional)">
                </div>
                <input type="hidden" name="file_types" class="form-file-types">
                <input type="hidden" name="files_sizes" class="form-file-sizes">
                <input type="hidden" name="mimes" class="form-mimes">
            `)

                minLength.prop('readonly', false).show()
                maxLength.prop('readonly', false).show()
                minLength.prop('required', true)
                maxLength.prop('required', true)

                // addInputTypeHidden(index)

            } else if ($(this).val() == 'hidden' || $(this).val() == 'no-input') {
                minLength.prop('readonly', true).hide()
                maxLength.prop('readonly', true).hide()
                minLength.val('')
                maxLength.val('')

                let inputTypeDefaultValue = setInputTypeDefaultValue(index)

                $(`.form-default-value`).remove()

                $(`.input-options`).append(`
                <div class="input-box form-default-value ">
                    <input type="${inputTypeDefaultValue}" name="default_values" class="google-input" placeholder="Default Value (optional)">
                </div>
            `)

                switchRequired.prop('checked', false)
                switchRequired.prop('disabled', true)
                addInputTypeHidden(index)

            } else if (
                $(this).val() == 'time' ||
                $(this).val() == 'week' ||
                $(this).val() == 'color' ||
                $(this).val() == 'datetime-local'
            ) {
                minLength.prop('readonly', true).hide()
                maxLength.prop('readonly', true).hide()
                minLength.val('')
                maxLength.val('')
                addInputTypeHidden(index)

            } else {
                addInputTypeHidden(index)
                minLength.prop('readonly', false).show()
                maxLength.prop('readonly', false).show()
            }
        });








        $(document).on("click", "#addRow", function() {
            var html = '';
            var index = parseInt($(this).parent().parent().parent().parent().find('.text-center:last').html()) >
                0 ?
                parseInt($(this).parent().parent().parent().parent().find('.text-center:last').html()) + 1 : 1

            html +=
                '<tr><td class="text-center" scope="row">' + index +
                '</td><td><input type="radio" name="fields_info_radio" onchange="addValue(' +
                index + ')" class="m-input mr-2"><input type="hidden" value="0" id="fields_info[' + index +
                '][default]" name="fields_info[' + index +
                '][default]"></td><td><input type="text" name="fields_info[' + index +
                '][value]" class="form-control m-input mr-2"  autocomplete="off"></td><td><button type="button" class="btn btn-danger removeSection"><i class="fa fa-trash"></i></button></td></tr>';
            $('.option_fields tbody').append(html);

        });

        function addValue(index) {
            console.log(index);
            $('[id^="fields_info"]').each(function() {
                $(this).val(0);
            });
            $("#fields_info\\[" + index + "\\]\\[default\\]").val(1);

        }
        $(document).on('click', '.removeSection', function() {
            $(this).closest('tr').remove();
            index--;
        });

        jQuery.validator.addMethod("notEqual", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");

        jQuery.validator.addMethod("notEqual2", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");


        jQuery.validator.addMethod("notEqual3", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");

        jQuery.validator.addMethod("notEqual4", function(value, element, param) {
            return this.optional(element) || value != param;
        }, "Please change the name, you can't enter id");

        $(document).on('change', '#attributeCreate', function() {
            $("#attributeCreate").validate({
                onkeyup: function(el, e) {

                    $(el).valid();
                },
                // errorClass: "invalid-feedback is-invalid",
                // validClass: 'valid-feedback is-valid',
                ignore: ":hidden",
                rules: {
                    name: {
                        required: true,
                        maxlength: 255,
                    },
                    code: {
                        required: true,
                        maxlength: 255,
                        notEqual: 'id',
                        notEqual2: 'ID',
                        notEqual3: 'iD',
                        notEqual4: 'Id',
                    },
                    input_types: {
                        required: true,
                        maxlength: 255,
                    },

                },
                messages: {},
                errorPlacement: function(error, element) {
                    error.insertAfter($(element).parent());
                },
            });
            // alert($('#attributeCreate').valid());
        });
    </script>
@endsection
