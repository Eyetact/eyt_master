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
    </style>
@endsection
@include('layouts.messages.header-messages')
@section('page-header')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title mb-0">Modules</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fe fe-file-text mr-2 fs-14"></i>Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="#">Add Modules</a></li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    @php
        $storfrontMenu = \App\Helpers\Helper::getMenu('storfront');
        $adminMenu = \App\Helpers\Helper::getMenu('admin');
    @endphp

    <div class="row">
        <div class="col-lg-12 col-xl-6 col-md-12 col-sm-12">
            @if (auth()->user()->hasRole('super'))
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" style="width: 100%">
                            <div class="row">
                                <div class="col-10" style="padding-top: 10px">Front - (
                                    <span
                                        id="FrontForm-counter">{{ count(App\Models\MenuManager::where('menu_type', 'storfront')->get()) }}</span>
                                    )
                                </div>
                                <div class="col-1">

                                    <button type="button" data-target="#FrontForm" data-toggle="modal"
                                        class="btn btn-primary">Add</button>

                                </div>
                            </div>

                        </h4>
                    </div>

                    <div class="card-body">
                        @include('module_manager.storfront_nested_menu')
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h4 class="card-title" style="width: 100%">
                        <div class="row">
                            <div class="col-10" style="padding-top: 10px">Admin - (
                                <span
                                    id="addMenuLabel-counter">{{ count(App\Models\MenuManager::where('menu_type', 'admin')->get()) }}
                                </span>)
                            </div>
                            <div class="col-1">
                                @if (auth()->user()->checkAllowdMode())
                                    <button type="button" data-target="#addMenuLabel" data-toggle="modal"
                                        class="btn btn-primary">Add</button>
                                @endif
                            </div>
                        </div>
                    </h4>


                </div>
                <div class="card-body">
                    @include('module_manager.admin_nested_menu')
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-6 col-md-12 col-sm-12">
            <div class="row align-items-end justify-content-end tt">

                {{-- @php
                    echo auth()->user()->model_limit;
                    echo auth()->user()->current_model_limit

                @endphp --}}

                {{-- @if (auth()->user()->checkAllowdMode())
                    <div class="col-2"><button type="button" data-target="#addMenuModal" data-toggle="modal"
                            class="btn btn-primary">Add</button></div>
                @endif --}}

            </div>
            <div class="editc"></div>
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
    <div class="modal fade bd-example-modal-lg" id="addMenuLabel" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="form-group col-sm-4 label-con">
                            <label class="custom-switch form-label">
                                <input type="checkbox" class="custom-switch-input" id="label">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Label</span>
                            </label>
                        </div>

                        <div class="form-group col-sm-4 sub-con">
                            <label class="custom-switch form-label ">
                                <input type="checkbox" class="custom-switch-input" id="sub">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Sub</span>
                            </label>
                        </div>


                        <div class="col-12">
                            <div class="main-form">

                                <form
                                    action="{{ $menu->id == null ? route('module_manager.store') : route('module_manager.update', ['menu' => $menu->id]) }}"
                                    id="admin-form" method="POST" autocomplete="off" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" name="menu_type" value="admin">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="">

                                                <div class="">
                                                    <div class="row">
                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="name">Name <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="name" id="aname"
                                                                class="form-control" value="">
                                                            <input type="hidden" name="id" id="aid"
                                                                value="">
                                                            <span id="name-admin-error"
                                                                class="error text-danger d-none error-message"></span>
                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="code2">Code <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="code" id="code"
                                                                class="form-control" value="">
                                                            <span id="code-admin-error"
                                                                class="error text-danger d-none error-message"></span>
                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Path <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="path" id="apath"
                                                                class="form-control" value="">
                                                            <span id="path-admin-error"
                                                                class="error text-danger d-none error-message"></span>
                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Sidebar Name <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="sidebar_name" id="sidebar_name"
                                                                class="form-control" value="">
                                                            <span id="sidebar_name-admin-error"
                                                                class="error text-danger d-none error-message"></span>
                                                        </div>

                                                        <div class="form-group col-sm-4">
                                                            <label class="custom-switch form-label">
                                                                <input type="checkbox" name="include_in_menu"
                                                                    id="ainclude_in_menu" class="custom-switch-input"
                                                                    id="is_enable">
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description">Include in
                                                                    menu</span>
                                                            </label>
                                                        </div>

                                                        <div class="form-group col-sm-4">
                                                            <label class="custom-switch form-label">
                                                                <input type="checkbox" name="is_system" id="is_system"
                                                                    class="custom-switch-input" id="is_system">
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description">Global</span>
                                                            </label>
                                                        </div>

                                                        <div class="form-group col-sm-4">
                                                            <label class="custom-switch form-label">
                                                                <input type="checkbox" name="status" id="status"
                                                                    class="custom-switch-input" id="status" checked>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description">Status</span>
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-12 input-box">
                                                            <label class="form-label" for="module">Type<span
                                                                    class="text-red">*</span></label>


                                                            <select name="mtype" class="google-input module"
                                                                id="mtype" required>
                                                                <option disabled value="" selected>Select</option>
                                                                <option value="stander">Stander</option>
                                                                <option value="sortable">Sortable</option>

                                                            </select>
                                                            <span id="mtype-admin-error"
                                                                class="error text-danger d-none"></span>


                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <input title="Reset form" class="btn btn-danger d-none"
                                                        id="remove-admin-menu" type="button" value="Delete">
                                                    <input title="Reset form" class="btn btn-success d-none"
                                                        id="restore-admin-menu" type="button" value="Restore">
                                                    <input title="Save module" class="btn btn-primary"
                                                        id="submit-admin-menu" type="submit" value="Save">
                                                    {{-- <input title="Reset form" class="btn btn-warning" type="reset" value="Reset"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                            </div>

                            <div class="label-form" style="display: none">
                                <form action="{{ route('module_manager.storelabel') }}" id="moduleCreate" method="POST"
                                    autocomplete="off">
                                    @csrf
                                    <input type="hidden" name="menu_type" value="admin">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="">

                                                <div class="">
                                                    <div class="row">
                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="name">Name <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="name" id="aname"
                                                                class="form-control" value="">
                                                            <input type="hidden" name="id" id="aid"
                                                                value="">
                                                        </div>



                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <input title="Reset form" class="btn btn-danger d-none"
                                                        id="remove-admin-menu" type="button" value="Delete">
                                                    <input title="Reset form" class="btn btn-success d-none"
                                                        id="restore-admin-menu" type="button" value="Restore">
                                                    <input title="Save module" class="btn btn-primary"
                                                        id="submit-admin-menu" type="submit" value="Save">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="sub-form" style="display: none">
                                <form action="{{ route('module_manager.storSubPost') }}" id="moduleCreateSub"
                                    method="POST" autocomplete="off">
                                    @csrf

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="">

                                                <div class="">
                                                    <input type="hidden" name="menu_type" value="admin">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12">
                                                            <div class="">

                                                                <div class="">
                                                                    <div class="row">

                                                                        <div class="form-group col-sm-4 sub-con">
                                                                            <label class="custom-switch form-label ">
                                                                                <input type="checkbox"
                                                                                    class="custom-switch-input"
                                                                                    id="shared" name="shared">
                                                                                <span
                                                                                    class="custom-switch-indicator"></span>
                                                                                <span
                                                                                    class="custom-switch-description">Shared</span>
                                                                            </label>
                                                                        </div>

                                                                        <div class="form-group col-sm-4 added"
                                                                            style="display: none">
                                                                            <label class="custom-switch form-label ">
                                                                                <input type="checkbox"
                                                                                    class="custom-switch-input"
                                                                                    id="addable" name="addable">
                                                                                <span
                                                                                    class="custom-switch-indicator"></span>
                                                                                <span
                                                                                    class="custom-switch-description">Addable</span>
                                                                            </label>
                                                                        </div>

                                                                        <div class="col-sm-12 input-box">
                                                                            <label class="form-label"
                                                                                for="module">Parent<span
                                                                                    class="text-red">*</span></label>

                                                                            @php
                                                                                $module_ids = \App\Models\Module::where(
                                                                                    'user_id',
                                                                                    auth()->user()->id,
                                                                                )
                                                                                    ->where('migration', '!=', null)
                                                                                    ->pluck('id');
                                                                            @endphp
                                                                            <select name="parent_id"
                                                                                class="google-input module" id="module"
                                                                                required>
                                                                                <option disabled value="" selected>
                                                                                    Select
                                                                                    Module</option>
                                                                                @foreach (\App\Models\MenuManager::where('menu_type', 'admin')->whereIn('module_id', $module_ids)->orderBy('sequence', 'asc')->get() as $item)
                                                                                    <option
                                                                                        value="{{ $item->module->id }}">
                                                                                        {{ $item->name }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                            <label id="module-error"
                                                                                class="error text-red hide"
                                                                                for="module"></label>

                                                                        </div>
                                                                        <div class="col-sm-12 input-box subbb"
                                                                            style="display: none">
                                                                            <label class="form-label"
                                                                                for="module">Attribute<span
                                                                                    class="text-red">*</span></label>


                                                                            <select name="attr_id" class="google-input "
                                                                                id="attr_id">
                                                                                <option value="" selected>Select
                                                                                    Attribute</option>

                                                                            </select>
                                                                            <label id="module-error"
                                                                                class="error text-red hide"
                                                                                for="module"></label>

                                                                        </div>




                                                                        <div class="col-12 row form-subb"
                                                                            style="display: none">

                                                                            <div class="col-sm-12 form-group">
                                                                                <label class="form-label"
                                                                                    for="name">Name <span
                                                                                        class="text-red">*</span></label>
                                                                                <input type="text" name="name"
                                                                                    id="aname" class="form-control"
                                                                                    value="">
                                                                                <input type="hidden" name="id"
                                                                                    id="aid" value="">
                                                                            </div>

                                                                            <div class="col-sm-12 form-group">
                                                                                <label class="form-label"
                                                                                    for="code2">Code <span
                                                                                        class="text-red">*</span></label>
                                                                                <input type="text" name="code"
                                                                                    id="code" class="form-control"
                                                                                    value="">

                                                                            </div>

                                                                            <div class="col-sm-12 form-group">
                                                                                <label class="form-label"
                                                                                    for="path">Path <span
                                                                                        class="text-red">*</span></label>
                                                                                <input type="text" name="path"
                                                                                    id="apath" class="form-control"
                                                                                    value="">
                                                                            </div>

                                                                            <div class="col-sm-12 form-group">
                                                                                <label class="form-label"
                                                                                    for="path">Sidebar Name <span
                                                                                        class="text-red">*</span></label>
                                                                                <input type="text" name="sidebar_name"
                                                                                    id="sidebar_name" class="form-control"
                                                                                    value="">
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <label class="custom-switch form-label">
                                                                                    <input type="checkbox"
                                                                                        name="include_in_menu"
                                                                                        id="ainclude_in_menu"
                                                                                        class="custom-switch-input"
                                                                                        id="is_enable">
                                                                                    <span
                                                                                        class="custom-switch-indicator"></span>
                                                                                    <span
                                                                                        class="custom-switch-description">Include
                                                                                        in
                                                                                        menu</span>
                                                                                </label>
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <label class="custom-switch form-label">
                                                                                    <input type="checkbox"
                                                                                        name="is_system" id="is_system"
                                                                                        class="custom-switch-input"
                                                                                        id="is_system">
                                                                                    <span
                                                                                        class="custom-switch-indicator"></span>
                                                                                    <span
                                                                                        class="custom-switch-description">Global</span>
                                                                                </label>
                                                                            </div>

                                                                            <div class="form-group col-sm-4">
                                                                                <label class="custom-switch form-label">
                                                                                    <input type="checkbox" name="status"
                                                                                        id="status"
                                                                                        class="custom-switch-input"
                                                                                        id="status" checked>
                                                                                    <span
                                                                                        class="custom-switch-indicator"></span>
                                                                                    <span
                                                                                        class="custom-switch-description">Status</span>
                                                                                </label>
                                                                            </div>

                                                                            <div class="col-sm-12 input-box">
                                                                                <label class="form-label"
                                                                                    for="module">Type<span
                                                                                        class="text-red">*</span></label>


                                                                                <select name="mtype"
                                                                                    class="google-input module"
                                                                                    id="mtype">
                                                                                    <option disabled value=""
                                                                                        selected>Select</option>
                                                                                    <option value="stander">Stander
                                                                                    </option>
                                                                                    <option value="sortable">Sortable
                                                                                    </option>

                                                                                </select>


                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                                <div class="card-footer text-right">
                                                                    <input title="Reset form"
                                                                        class="btn btn-danger d-none"
                                                                        id="remove-admin-menu" type="button"
                                                                        value="Delete">
                                                                    <input title="Reset form"
                                                                        class="btn btn-success d-none"
                                                                        id="restore-admin-menu" type="button"
                                                                        value="Restore">
                                                                    <input title="Save module" class="btn btn-primary"
                                                                        id="submit-admin-menu" type="submit"
                                                                        value="Save">
                                                                    {{-- <input title="Reset form" class="btn btn-warning" type="reset" value="Reset"> --}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>











                    </div>

                </div>
            </div>
        </div>
    </div>






    <div class="modal fade bd-example-modal-lg" id="FrontForm" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" id="store-front-form-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel">Add</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                            aria-hidden="true">×</span> </button>
                </div>
                <div class="modal-body">

                    <div class="row">



                        <div class="col-12">
                            <div class="main-form">

                                <form id="storefront-form" autocomplete="off" novalidate="novalidate">
                                    @csrf
                                    <input type="hidden" name="menu_type" value="storfront">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12">
                                            <div class="">

                                                <div class="">
                                                    <div class="row">
                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="name">Name <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="name" id="aname"
                                                                class="form-control" value="">
                                                            <input type="hidden" name="id" id="aid"
                                                                value="">
                                                            <span id="name-storefront-error"
                                                                class="error text-danger d-none error-message"></span>

                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="code2">Code <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="code" id="code"
                                                                class="form-control" value="">
                                                            <span id="code-storefront-error"
                                                                class="error text-danger d-none error-message"></span>

                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Path <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="path" id="apath"
                                                                class="form-control" value="">
                                                            <span id="path-storefront-error"
                                                                class="error text-danger d-none error-message"></span>

                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="sidebar_name">Sidebar Name
                                                                <span class="text-red">*</span></label>
                                                            <input type="text" name="sidebar_name" id="sidebar_name"
                                                                class="form-control" value="">
                                                            <span id="sidebar_name-storefront-error"
                                                                class="error text-danger d-none error-message"></span>

                                                        </div>

                                                        {{-- <div class="form-group col-sm-4">
                                                            <label class="custom-switch form-label">
                                                                <input type="checkbox" name="include_in_menu"
                                                                    id="ainclude_in_menu" class="custom-switch-input"
                                                                    id="is_enable">
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description">Include in
                                                                    menu</span>
                                                            </label>
                                                        </div> --}}

                                                        <div class="form-group col-sm-4">
                                                            <label class="custom-switch form-label">
                                                                <input type="checkbox" name="is_system" id="is_system"
                                                                    class="custom-switch-input" id="is_system">
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description">Global</span>
                                                            </label>
                                                        </div>

                                                        <div class="form-group col-sm-4">
                                                            <label class="custom-switch form-label">
                                                                <input type="checkbox" name="status" id="status"
                                                                    class="custom-switch-input" id="status" checked>
                                                                <span class="custom-switch-indicator"></span>
                                                                <span class="custom-switch-description">Status</span>
                                                            </label>
                                                        </div>

                                                        <div class="col-sm-12 input-box">
                                                            <label class="form-label" for="module">Type<span
                                                                    class="text-red">*</span></label>


                                                            <select name="mtype" class="google-input module"
                                                                id="mtype" required>
                                                                <option disabled value="" selected>Select</option>
                                                                <option value="stander">Stander</option>
                                                                <option value="sortable">Sortable</option>

                                                            </select>
                                                            <span id="mtype-storefront-error"
                                                                class="error text-danger d-none error-message"></span>



                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <input title="Reset form" class="btn btn-danger d-none"
                                                        id="remove-admin-menu" type="button" value="Delete">
                                                    <input title="Reset form" class="btn btn-success d-none"
                                                        id="restore-admin-menu" type="button" value="Restore">
                                                    <button title="Save module" class="btn btn-primary"
                                                        id="storefront-form-submit" type="submit">Save</button>
                                                    {{-- <input title="Reset form" class="btn btn-warning" type="reset" value="Reset"> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                            </div>

                        </div>




                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

{{-- import model start --}}
@include('module_manager.menu_item_model')
{{-- import model end --}}


@section('js')
    @include('includes.js.headers')
    @include('module_manager.js.functions')
    @include('module_manager.js.actions')
    @include('module_manager.js.menu.menu')
@endsection
