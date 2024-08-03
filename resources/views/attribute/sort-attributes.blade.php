<!-- https://codepen.io/Gobi_Ilai/pen/LLQdqJ -->
@extends('layouts.master')
<style>
    /* Add your styles for the selected item here */
    .selected-item {
        background-color: #ffeeba !important;
        /* Change this to the desired color */
        color: #856404 !important;
        /* Change this to the desired text color */
    }

    .tag-deleted {
        float: right;
        margin: -3px -10px 0 0;
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

    button.sub-add {
        float: right;
        position: relative;
        z-index: 999;
    }

    div#role_form_modal {
        z-index: 9999999;
    }

    .dd-handle {
        width: 80% !important;
        float: left;
    }

    .dd-handle .dd-handle {}


    li.dd-item:after {
        content: no-close-quote;
        display: table;
        clear: both;
    }

    #tbl-field>tbody>tr>td {
        min-height: 97px;
        text-align: center !important;
        line-height: 70px;
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

    li.dd-item.is_delete>.dd-handle {
        background: #f41919 !important;
        color: wheat;
        color: white !important;
    }
</style>
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/nested_menu.css') }}">

    <link href="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        ul.my-1.mx-2.p-0 {
            line-height: 1;
            font-size: 11px;
            text-align: left;
        }

        ul.my-1.mx-2.p-0 li {
            margin-bottom: 7px;
        }

        span.fast-btn {
            width: 100%;
            height: 216px;
            background: #f9f9f9;
            text-align: center;
            line-height: 216px;
            cursor: pointer;
            border: 1px solid #ddd;
            font-weight: bold;
        }

        .row.align-items-end.justify-content-end.tt {
            margin-top: -65px;
        }


        .radio-spacing {
            margin-right: 20px;
            /* Adjust the spacing as needed */
        }




        li.dd-item {
            max-width: 100%;
            position: relative;
        }

        div#admin_nestable {
            max-width: 100%;
            width: 100%;
        }


        button.sub-add {
            float: none !important;
        }

        .dd-handle {
            float: none !important;
            width: 100% !important;
            padding: 0 50px;
            height: 40px;
            line-height: 40px;
            border-radius: 3px;
            background: #ebeef1;
            margin-bottom: 5px;
            border-color: #00000012;
        }

        .dd-item>button {
            float: none !important;
            position: absolute;
            left: 0;
            height: 40px;
            width: 40px;
            background: #38cb89;
            top: 0;
        }

        button.sub-add {
            left: auto;
            right: 0;
            background: #705ec8;
        }

        ol.dd-list {
            /* width: 100% !important; */
        }

        .dd-list {
            /* padding: 0 15px; */
        }

        .admin_nested_form {
            padding: 0 20px 0 0;
        }

        .selected-item {
            background: #705ec838 !important;
            color: #333 !important;
        }

        li.dd-item.is_delete>.dd-handle {
            background: #ef4b4b6b !important;
            color: #333 !important;
        }

        li.dd-item.no-pad .dd-handle {
            padding-left: 15px;
            padding-right: 15px;
        }

        .dd-list .dd-list {
            padding-left: 40px;
        }




        .dd-handle {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            /* padding-top: 3px; */
            /* padding-bottom: 7px; */
            line-height: 0;
        }

        .swal2-container.swal2-center.swal2-backdrop-show {
            z-index: 99999999;
        }

        .attribute-item {
            padding: 10px;
            margin: 5px 0;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            cursor: move;
        }
        .attribute-item:hover {
            background-color: #f1f1f1;
        }
        .module-select-container {
            margin-bottom: 20px;
        }

    </style>
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Attributes</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>Sort</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Attribute</a></li>
            </ol>
        </div>
    </div>
@endsection

@section('content')



<div class="card">
    <div class="card-header">
        <h4 class="card-title" style="width: 100%">

            Sort Attributes
        </h4>


    </div>
    <div class="card-body">


        <div class="row">
            <div class="col-sm-12 input-box module-select-container">
                <label class="form-label" for="module">Module<span class="text-red">*</span></label>
                <div class="row">
                    <div class="col-6">
                <select name="module" class="google-input module" id="module-select" required="">
                    <option value="" selected="" disabled>Select Module</option>
                    @foreach ($models as $model)
                        <option value="{{ $model->id }}">{{ $model->name }}</option>
                    @endforeach

                </select>
                    </div>

                </div>
            </div>



        </div>

        <div class="row">
        <div id="attributes-list" class="col-sm-6 input-box">

        </div>
        </div>





    </div>
</div>
</div>






@endsection



@section('js')
    <script src="{{ asset('assets/js/storfront_nestable.js') }}"></script>
    <script src="{{ asset('assets/js/admin_nestable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- INTERNAL Sweet alert js -->
    <script src="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>

    <!-- user interactive forms -->
    <script src="{{ URL::asset('assets/js/user interactive/userInteractiveForms.js') }}"></script>
    <script src="//code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>



$(document).ready(function() {

            $('#module-select').change(function() {
                let moduleId = $(this).val();
                if (moduleId) {
                    $.ajax({
                        url: `sattributes/${moduleId}`,
                        method: 'GET',
                        success: function(data) {
                            let attributesList = $('#attributes-list');
                            attributesList.empty();
                            data.forEach(function(attribute) {
                                attributesList.append(`<div class="attribute-item" data-id="${attribute.id}">${attribute.name}</div>`);
                            });
                            initializeDragAndDrop();
                        }
                    });
                }
            });


            function initializeDragAndDrop() {
                $('#attributes-list').sortable({
                    update: function(event, ui) {
                        let sequenceData = {};
                        $('#attributes-list .attribute-item').each(function(index) {
                            sequenceData[index] = $(this).data('id');
                        });

                        $.ajax({
                            url: 'attributes/update-sequence',
                            method: 'POST',
                            data: {
                                _token: $('input[name="_token"]').val(),
                                sequence: sequenceData
                            },
                            success: function(response) {
                                if (response.status == 'success') {

                                    var selectedModule = $('#module-select').val();
                if (selectedModule) {
                    $.ajax({
                        url: "{{ url('regenerate-views') }}/" + selectedModule,
                        method: "GET",
                        success: function(data) {
                            if (data.status == 'success') {

                                Swal.fire({
                                    title: "Success",
                                    text: "Views regenerated successfully.",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                });

                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            Swal.fire({
                                    title: "Success",
                                    text: "Views regenerated successfully.",
                                    icon: "success",
                                    confirmButtonText: "OK",
                                });
                        }
                    });
                } else {
                    console.warn("No module selected.");
                }



                                }
                            }
                        });
                    }
                });
            }
        });



        function convertMenuToJson(menu, includeClass, parentId = 0) {
            var result = [];
            menu.children("li").each(function(index) {
                var menuItem = $(this).find('.' + includeClass);
                var id = menuItem.val();
                var sequence = index;

                var jsonItem = {
                    id: id,
                    parent: parentId,
                    sequence: sequence,
                };

                var subMenu = $(this).children("ol");
                if (subMenu.length > 0) {
                    jsonItem.children = convertMenuToJson(subMenu, includeClass, id);
                }
                result.push(jsonItem);
            });

            return result;
        }



        function enabledDisabledAdminFormField(type, data, menuId) {
            if (data.is_deleted == 1) {
                let isDeletedHtml = `<span class="tag tag-deleted tag-red">Deleted</span>`;
                $('#admin_menu_list li[data-id="' + menuId + '"] .admin-menu:first').after(isDeletedHtml)

                let selectedItem = $('#admin_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 0) {
                    selectedItemDataAttr.is_deleted = 1
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", true);
                $(type + ' input[type=checkbox]').attr('disabled', true);
                $(type + ' textarea').attr("disabled", true);
                $(type + ' #restore-admin-menu').attr("disabled", false);
                $(type + ' #submit-admin-menu').attr("disabled", false);

                $(type + ' #restore-admin-menu').removeClass("d-none");
                $(type + ' #remove-admin-menu').addClass("d-none");
            } else {
                $('#admin_menu_list li[data-id="' + menuId + '"]').find(".tag-deleted").remove()

                let selectedItem = $('#admin_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 1) {
                    selectedItemDataAttr.is_deleted = 0
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", false);
                $(type + ' input[type=checkbox]').attr('disabled', false);
                $(type + ' textarea').attr("disabled", false);

                $(type + ' #remove-admin-menu').removeClass("d-none");
                $(type + ' #restore-admin-menu').addClass("d-none");
            }
        }


    </script>

    @include('module_manager.js.functions')
    @include('module_manager.js.actions')
    {{-- @include('module_manager.js.menu.menu') --}}
@endsection
