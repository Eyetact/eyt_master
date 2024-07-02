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
                                {{ count(App\Models\MenuManager::where('menu_type', 'storfront')->get()) }} )</div>
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
                                {{ count(App\Models\MenuManager::where('menu_type', 'admin')->get()) }} )</div>
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
                                    id="admin_form" method="POST" autocomplete="off" novalidate="novalidate">
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

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="code2">Code <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="code" id="code"
                                                                class="form-control" value="">

                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Path <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="path" id="apath"
                                                                class="form-control" value="">
                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Sidebar Name <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="sidebar_name" id="sidebar_name"
                                                                class="form-control" value="">
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
        <div class="modal-dialog modal-lg">
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

                                <form
                                    action="{{ $menu->id == null ? route('module_manager.storeFront') : route('module_manager.update', ['menu' => $menu->id]) }}"
                                    id="admin_form" method="POST" autocomplete="off" novalidate="novalidate">
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
                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="code2">Code <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="code" id="code"
                                                                class="form-control" value="">

                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Path <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="path" id="apath"
                                                                class="form-control" value="">
                                                        </div>

                                                        <div class="col-sm-12 form-group">
                                                            <label class="form-label" for="path">Sidebar Name <span
                                                                    class="text-red">*</span></label>
                                                            <input type="text" name="sidebar_name" id="sidebar_name"
                                                                class="form-control" value="">
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
    <script src="{{ asset('assets/js/storfront_nestable.js') }}"></script>
    <script src="{{ asset('assets/js/admin_nestable.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- INTERNAL Sweet alert js -->
    <script src="{{ URL::asset('assets/plugins/sweet-alert/jquery.sweet-modal.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/sweet-alert.js') }}"></script>
    <script src="//code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {

            $(document).on('change', '.module', function() {
                var id = $(this).find(':selected').val();

                var parent = $(this).parent().parent().parent().parent().find('#attr_id');
                $.ajax({
                    url: '{{ url('/') }}/attribute-by-module2/' + id,
                    success: function(response) {
                        console.log(response);
                        // $('.child-drop').remove()
                        parent.html(`${response}`);
                        $('.subbb').show();
                    }
                });
            })


            $(document).on('change', '#attr_id', function() {
                var id = $(this).find(':selected').val();

                if (id != undefined) {
                    $('.form-subb').show();

                }

            })

            $(document).on('change', '#shared', function() {
                var id = $(this).val();
                console.log(id);
                if ($(this).is(':checked')) {
                    $('.added').show();
                } else {
                    $('.added').hide();

                }

            })


            $("#addMenuLabel").on('shown.bs.modal', function() {
                // alert('aaa')
                $('.label-form').hide();
                $('.sub-form').hide();


                $("#label").on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.sub-form').hide();
                        $('.main-form').hide();
                        $('.label-form').show();
                        $('#sub').val(0).prop('checked', false)
                        $('.sub-con').hide();
                    } else {
                        $('.label-form').hide();
                        $('.sub-form').hide();
                        $('.main-form').show();
                        $('.sub-con').show();

                    }

                });

                $("#sub").on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.label-form').hide();
                        $('.main-form').hide();
                        $('.sub-form').show();
                        $('#label').val(0).prop('checked', false)
                        $('.label-con').hide();

                    } else {
                        $('.sub-form').hide();
                        $('.label-form').hide();
                        $('.label-con').show();
                        $('.main-form').show();

                    }

                });
            });



            $('.fast').hide();

            $(".fast-btn").click(function() {
                $(this).hide();
                $(".fast").toggle();
            });
            // console.log($(".storfront_nested_form").find('li:first').find('.dd-handle:first'));
            // $(".storfront_nested_form").find('li:first').find('.dd-handle:first').trigger('mousedown');

            // $('#storfront_form_edit').submit(function(e) {
            //     e.preventDefault(); // Prevent the form from submitting the traditional way

            //     // Serialize the form data
            //     var formData = $(this).serialize();

            //     var edit_sid = $(this).find('#sid').val();
            //     // Send an AJAX request
            //     $.ajax({
            //         type: 'POST',
            //         url: '' + '/' +
            //             edit_sid, // Replace with your actual route
            //         data: formData,
            //         success: function(response) {
            //             // Handle the success response
            //             console.log('AJAX request succeeded:', response);
            //             $('#addMenuModal').modal(
            //                 'hide'); // Hide the modal after successful submission
            //             location.reload();
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle the error response
            //             console.error('AJAX request failed:', status, error);

            //             if (xhr.status === 422) {
            //                 // If it's a validation error, display the errors in the modal
            //                 var errors = xhr.responseJSON.errors;
            //                 displayValidationErrors(errors);
            //             } else {
            //                 // Handle other types of errors as needed
            //                 alert('An unexpected error occurred. Please try again.');
            //             }
            //         }
            //     });
            // });

            // $('#storfront_form').submit(function(e) {
            //     e.preventDefault(); // Prevent the form from submitting the traditional way

            //     // Serialize the form data
            //     var formData = $(this).serialize();

            //     // Send an AJAX request
            //     $.ajax({
            //         type: 'POST',
            //         url: '{{ route('module_manager.store') }}', // Replace with your actual route
            //         data: formData,
            //         success: function(response) {
            //             // Handle the success response
            //             console.log('AJAX request succeeded:', response);
            //             $('#addMenuModal').modal(
            //                 'hide'); // Hide the modal after successful submission
            //             location.reload();
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle the error response
            //             console.error('AJAX request failed:', status, error);

            //             if (xhr.status === 422) {
            //                 // If it's a validation error, display the errors in the modal
            //                 var errors = xhr.responseJSON.errors;
            //                 displayValidationErrors(errors);
            //             } else {
            //                 // Handle other types of errors as needed
            //                 alert('An unexpected error occurred. Please try again.');
            //             }
            //         }
            //     });
            // });

            $('#admin_form').submit(function(e) {
                e.preventDefault(); // Prevent the form from submitting the traditional way

                // Serialize the form data
                var formData = $(this).serialize();

                // Send an AJAX request
                $.ajax({
                    type: 'POST',
                    url: '{{ route('module_manager.store') }}', // Replace with your actual route
                    data: formData,
                    success: function(response) {
                        // Handle the success response
                        console.log('AJAX request succeeded:', response);
                        $('#addMenuModal').modal(
                            'hide'); // Hide the modal after successful submission
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle the error response
                        console.error('AJAX request failed:', status, error);

                        if (xhr.status === 422) {
                            // If it's a validation error, display the errors in the modal
                            var errors = xhr.responseJSON.errors;
                            displayValidationErrors(errors);
                        } else {
                            // Handle other types of errors as needed
                            alert('An unexpected error occurred. Please try again.');
                        }
                    }
                });
            });

            // Function to display validation errors in the modal
            function displayValidationErrors(errors) {
                var errorList = '<ul>';
                $.each(errors, function(key, value) {
                    errorList += '<li>' + value[0] +
                        '</li>'; // Assuming you only want to display the first error message
                });
                errorList += '</ul>';

                // Display errors in the modal or wherever you want
                $('#validationErrors').removeClass('hide');
                $('#validationErrors').html(errorList);
                $('.modal-body').scrollTop(0);
            }

            $("#storfront_div").hide();
            $("#admin_div").hide();

            $("#storfront_edit_div").hide();
            $("#admin_edit_div").hide();

            $('body').on('change', '#module_type', function() {
                console.log($(this).val())
                if ($(this).val() == "storfront") {
                    $("#admin_div").hide();
                    $("#storfront_div").show();
                } else if ($(this).val() == "admin") {
                    $("#storfront_div").hide();
                    $("#admin_div").show();
                } else {
                    $("#storfront_div").hide();
                    $("#admin_div").hide();
                }
            });

            // $('#storfront_nestable').on('mousedown', '.dd-handle', function(event) {
            //     var type = '#storfront_form_edit';

            //     $("#storfront_edit_div").show();
            //     $("#admin_edit_div").hide();

            //     $('#storfront_nestable .dd-handle').removeClass('selected-item');
            //     $('#admin_nestable .dd-handle').removeClass('selected-item');
            //     $(this).addClass('selected-item');

            //     var singleData = $(this).parent().data("json");
            //     console.log(singleData);

            //     console.log("PMD isDeleted", singleData.is_deleted)
            //     // alert("aaa")
            //     if (singleData.is_deleted == 1) {
            //         $(this).addClass('deleted-item');
            //     } else {
            //         $(this).removeClass('deleted-item');
            //     }

            //     enabledDisabledStoreFrontFormField(type, singleData)

            //     $("#storfront_form_edit #sid").val(singleData.id);
            //     $("#storfront_form_edit #sname").val(singleData.name);
            //     $("#storfront_form_edit #scode").val(singleData.code);
            //     $("#storfront_form_edit #spath").val(singleData.path);
            //     $("#storfront_form_edit #sis_enable").prop('checked', singleData.status);
            //     $("#storfront_form_edit #sinclude_in_menu").prop('checked', singleData.include_in_menu);
            //     $("#storfront_form_edit #smeta_title").val(singleData.meta_title);
            //     $("#storfront_form_edit #smeta_description").val(singleData.meta_description);
            //     $("#storfront_form_edit #screated_date").val(singleData.created_date);
            //     $("#storfront_form_edit #sassigned_attributes").val(singleData.assigned_attributes);
            // });

            $('#storfront_nestable').on('mousedown', '.dd-handle', function(event) {
                var type = '#storfront_form_edit';

                $("#admin_edit_div").hide();
                $("#storfront_edit_div").show();

                $('#admin_nestable .dd-handle').removeClass('selected-item');
                $('#storfront_nestable .dd-handle').removeClass('selected-item');
                $(this).addClass('selected-item');

                var singleData = $(this).parent().data("json");
                var path = $(this).parent().data("path");
                console.log("PMD 1", singleData)
                // alert(singleData.module_id)

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: path,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        $('.editc').html(response);

                    },
                    error: function(error) {
                        console.error(error);
                    }
                });



                console.log("PMD isDeleted", singleData.is_deleted)

                enabledDisabledAdminFormField(type, singleData)

                $("#admin_form_edit #aid").val(singleData.id);
                $("#admin_form_edit #aname").val(singleData.name);
                $("#admin_form_edit #acode").val(singleData.code);
                $("#admin_form_edit #apath").val(singleData.path);
                $("#admin_form_edit #ais_enable").prop('checked', singleData.status);
                $("#admin_form_edit #ainclude_in_menu").prop('checked', singleData.include_in_menu);
                $("#admin_form_edit #acreated_date").val(singleData.created_date);
                $("#admin_form_edit #aassigned_attributes").val(singleData.assigned_attributes);
            });


            $('#admin_nestable').on('mousedown', '.dd-handle', function(event) {
                var type = '#admin_form_edit';

                $("#admin_edit_div").show();
                $("#storfront_edit_div").hide();

                $('#admin_nestable .dd-handle').removeClass('selected-item');
                $('#storfront_nestable .dd-handle').removeClass('selected-item');
                $(this).addClass('selected-item');

                var singleData = $(this).parent().data("json");
                var path = $(this).parent().data("path");
                console.log("PMD 1", singleData)
                // alert(singleData.module_id)

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: path,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        $('.editc').html(response);

                    },
                    error: function(error) {
                        console.error(error);
                    }
                });



                console.log("PMD isDeleted", singleData.is_deleted)

                enabledDisabledAdminFormField(type, singleData)

                $("#admin_form_edit #aid").val(singleData.id);
                $("#admin_form_edit #aname").val(singleData.name);
                $("#admin_form_edit #acode").val(singleData.code);
                $("#admin_form_edit #apath").val(singleData.path);
                $("#admin_form_edit #ais_enable").prop('checked', singleData.status);
                $("#admin_form_edit #ainclude_in_menu").prop('checked', singleData.include_in_menu);
                $("#admin_form_edit #acreated_date").val(singleData.created_date);
                $("#admin_form_edit #aassigned_attributes").val(singleData.assigned_attributes);
            });

            $('body').on('change', '.storfront_nested_form .nestable', function() {
                var menu = $("#storfront_menu_list");
                var jsonResult = convertMenuToJson(menu, 'storfront-menu');

                $("#storfront_json").text(JSON.stringify(jsonResult, null, 2));

                var data = {
                    type: "storfront",
                    storfront_json: JSON.stringify(jsonResult, null, 2)
                }
                saveMenu(data);
            });

            $('body').on('change', '.admin_nested_form .nestable', function() {
                var menu = $("#admin_menu_list");
                var jsonResult = convertMenuToJson(menu, 'admin-menu');

                $("#admin_json").text(JSON.stringify(jsonResult, null, 2));
                var data = {
                    type: "",
                    admin_json: JSON.stringify(jsonResult, null, 2)
                }
                saveMenu(data);
            });

            function saveMenu(menuData) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('module_manager.menu_update') }}',
                    type: 'POST',
                    data: menuData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function(response) {
                        // if (response.success) {
                        //     alert('Menu change successfully.');
                        // }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }

            $('#storfront_form').validate({
                rules: {
                    name: {
                        required: true,
                    },
                    code: {
                        required: true,
                    },
                    path: {
                        required: true,
                    },
                    created_date: {
                        required: true,
                    },
                    assigned_attributes: {
                        required: true,
                    }
                }
            });

            $('#admin_form').validate({
                rules: {
                    module: {
                        required: true,
                    },
                    name: {
                        required: true,
                    },
                    code: {
                        required: true,
                    },
                    path: {
                        required: true,
                    },
                    created_date: {
                        required: true,
                    }
                }
            });
            
            $("#moduleCreateSub").on("submit", function(event) {
                if ($('#attr_id').val() <= 0) {
                    Swal.fire({
                        icon: "error",
                        title: "The parent module dos not have attribute ...",
                        text: "Something went wrong!",
                        footer: '<a href="{{ url('attribute') }}">Create ?</a>'
                    });
                   
                    event.preventDefault();

                    return;
                } else {
                    $("#moduleCreateSub").submit()
                }
                event.preventDefault();
            });

            // $('#moduleCreateSub').validate({
            //     rules: {
            //         module: {
            //             required: true,
            //         },
            //         name: {
            //             required: true,
            //         },
            //         code: {
            //             required: true,
            //         },
            //         path: {
            //             required: true,
            //         },
            //         attr_id: {
            //             required: true,

            //         },
            //         messages: {

            //             attr_id: "Please enter a valid email address",

            //         }
            //     },

            // });

            $("#storfront_li").click(function() {
                $("#admin_div").hide();
                $("#storfront_div").show();
            });

            // Storefront remove event
            $('body').on('click', '#remove-store-front-menu', function() {
                var type = '#storfront_form_edit';
                let modelID = $(this).data('id');
                let isDeleted = 1;
                updateIsdeleted(modelID);
            });

            // Storefront restore event
            $('body').on('click', '#restore-store-front-menu', function() {
                var type = '#storfront_form_edit';
                let menuId = $("#storfront_form_edit #sid").val();
                let isDeleted = 0;
                updateIsdeleted(type, menuId, isDeleted);
            });

            // Admin remove event
            $('body').on('click', '#remove-admin-menu', function() {
                let modelID = $(this).data('id');
                let isDelete = 1;
                updateIsdeleted(modelID, isDelete);
            });

            // Admin restore event
            $('body').on('click', '#restore-admin-menu', function() {
                let modelID = $(this).data('id');
                let isDelete = 0;
                updateIsdeleted(modelID, isDelete);
            });
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

        function updateIsdeleted(modelID, isDelete) {
            var deleteText =
                "Data would be restored, Are You Sure ?"
            if (isDelete == 1) {
                deleteText =
                    "Data would be there till 30 days and Once deleted, you will not be able to recover this details !"
            }
            swal({
                title: "Are you sure?",
                text: deleteText,
                icon: "warning",
                showCancelButton: true,
                type: "warning",
                // confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Yes, I am sure!',
                cancelButtonText: "No, cancel it!",
                // closeOnConfirm: false,
                // closeOnCancel: false,
                dangerMode: true,
            }, function(willDelete) {
                if (willDelete) {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajax({
                        url: '{{ route('module_manager.deleteORRestore') }}',
                        type: 'POST',
                        data: {
                            model_id: modelID,
                            is_delete: isDelete
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        success: function(response) {
                            swal({
                                title: response.msg
                            }, function(result) {
                                location.reload();
                            });
                        },
                        error: function(error) {
                            console.error(error);
                        }
                    });
                }
            });
        }

        function enabledDisabledStoreFrontFormField(type, data, menuId) {
            if (data.is_deleted == 1) {
                let isDeletedHtml = `<span class="tag tag-deleted tag-red">Deleted</span>`;
                $('#storfront_menu_list li[data-id="' + menuId + '"] .storfront-menu:first').after(isDeletedHtml)

                let selectedItem = $('#storfront_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 0) {
                    selectedItemDataAttr.is_deleted = 1
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", true);
                $(type + ' input[type=checkbox]').attr('disabled', true);
                $(type + ' textarea').attr("disabled", true);
                $(type + ' #restore-store-front-menu').attr("disabled", false);
                $(type + ' #submit-store-front-menu').attr("disabled", false);

                $(type + ' #restore-store-front-menu').removeClass("d-none");
                $(type + ' #remove-store-front-menu').addClass("d-none");
            } else {
                $('#storfront_menu_list li[data-id="' + menuId + '"]').find(".tag-deleted").remove()

                let selectedItem = $('#storfront_menu_list li[data-id="' + menuId + '"]');
                let selectedItemDataAttr = selectedItem.data("json");
                if (selectedItemDataAttr && selectedItemDataAttr.is_deleted == 1) {
                    selectedItemDataAttr.is_deleted = 0
                }
                selectedItem.attr('json', selectedItemDataAttr);

                $(type + ' input').prop("disabled", false);
                $(type + ' input[type=checkbox]').attr('disabled', false);
                $(type + ' textarea').attr("disabled", false);

                $(type + ' #remove-store-front-menu').removeClass("d-none");
                $(type + ' #restore-store-front-menu').addClass("d-none");
            }
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

        $(document).on('click', '.sub-add', function(event) {
            event.preventDefault();
            var path = $(this).data('path');
            // alert(id)
            $.ajax({
                url: path,
                success: function(response) {
                    console.log(path);
                    $(".modal-body").html(response);
                    $(".modal-title").html("Add sub Module");
                    $("#role_form_modal").modal('show');
                    $('.dropify').dropify();
                }
            });
        });
    </script>

    @include('module_manager.js.functions')
    @include('module_manager.js.actions')
@endsection
