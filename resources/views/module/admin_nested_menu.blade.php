<form action="{{ route('menu.menu_update') }}" id="admin_nested_form" method="POST" autocomplete="off" novalidate="novalidate">
    @csrf
    <input type="hidden" name="type" value="admin">
    <textarea name="admin_json" class="hide" id="admin_json" cols="30" rows="10"></textarea>
</form>
<div class="dd nestable" id="admin_nestable">
    <ol class="dd-list" id="admin_menu_list">
    @foreach ($adminMenu as $aMenu)
        <li class="dd-item" data-id="{{$aMenu->id}}" data-name="{{$aMenu->name}}" data-module="{{$aMenu->module}}" data-code="{{$aMenu->code}}" data-path="{{$aMenu->path}}" data-is_enable="{{$aMenu->is_enable}}" data-include_in_menu="{{$aMenu->include_in_menu}}"  data-assigned_attributes="{{$aMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($aMenu->created_date))}}">
            <div class="dd-handle">{{$aMenu->name}} <input type="hidden" class="admin-menu" value="{{$aMenu->id}}"></div>


            <span class="button-delete btn btn-icon  btn-danger delete-attribute delete-attribute"
                data-owner-id="{{$aMenu->id}}">
            <i class="fa fa-trash-o"></i>
            </span>

            <span class="button-edit btn btn-icon btn-warning"
                data-owner-id="{{$aMenu->id}}">
            <i class="fa fa-edit" data-toggle="tooltip" title="" data-original-title="Edit"></i>
            </span>

            @if ($aMenu->children->count())
            <ol class="dd-list">
                @foreach ($aMenu->children as $aaMenu)
                    <li class="dd-item" data-id="{{$aaMenu->id}}" data-name="{{$aaMenu->name}}" data-module="{{$aaMenu->module}}" data-code="{{$aaMenu->code}}" data-path="{{$aaMenu->path}}" data-is_enable="{{$aaMenu->is_enable}}" data-include_in_menu="{{$aaMenu->include_in_menu}}"  data-assigned_attributes="{{$aaMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($aaMenu->created_date))}}">

                        <div class="dd-handle">{{$aaMenu->name}}<input type="hidden" class="admin-menu" value="{{$aaMenu->id}}"></div>

                        <span class="button-delete btn btn-icon  btn-danger delete-attribute delete-attribute"
                            data-owner-id="{{$aaMenu->id}}">
                        <i class="fa fa-trash-o"></i>
                        </span>
                        <span class="button-edit btn btn-icon btn-warning"
                            data-owner-id="{{$aaMenu->id}}">
                        <i class="fa fa-edit" data-toggle="tooltip" title="" data-original-title="Edit"></i>
                        </span>
                        @if ($aaMenu->children->count())
                        <ol class="dd-list">
                            @foreach ($aaMenu->children as $aaaMenu)
                                <li class="dd-item" data-id="{{$aaaMenu->id}}" data-name="{{$aaaMenu->name}}" data-module="{{$aaaMenu->module}}" data-code="{{$aaaMenu->code}}" data-path="{{$aaaMenu->path}}" data-is_enable="{{$aaaMenu->is_enable}}" data-include_in_menu="{{$aaaMenu->include_in_menu}}"  data-assigned_attributes="{{$aaaMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($aaaMenu->created_date))}}">

                                    <div class="dd-handle">{{$aaaMenu->name}}<input type="hidden" class="admin-menu" value="{{$aaaMenu->id}}"></div>

                                    <span class="button-delete btn btn-icon  btn-danger delete-attribute delete-attribute"
                                        data-owner-id="{{$aaaMenu->id}}">
                                    <i class="fa fa-trash-o"></i>
                                    </span>
                                    <span class="button-edit btn btn-icon btn-warning"
                                        data-owner-id="{{$aaaMenu->id}}">
                                    <i class="fa fa-edit" data-toggle="tooltip" title="" data-original-title="Edit"></i>
                                    </span>

                                    @if ($aaaMenu->children->count())
                                    <ol class="dd-list">
                                        @foreach ($aaaMenu->children as $adMenu)
                                            <li class="dd-item" data-id="{{$adMenu->id}}" data-name="{{$adMenu->name}}" data-module="{{$adMenu->module}}" data-code="{{$adMenu->code}}" data-path="{{$adMenu->path}}" data-is_enable="{{$adMenu->is_enable}}" data-include_in_menu="{{$adMenu->include_in_menu}}"  data-assigned_attributes="{{$adMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($adMenu->created_date))}}">

                                                <div class="dd-handle">{{$adMenu->name}}<input type="hidden" class="admin-menu" value="{{$adMenu->id}}"></div>

                                                <span class="button-delete btn btn-icon  btn-danger delete-attribute delete-attribute"
                                                    data-owner-id="{{$adMenu->id}}">
                                                <i class="fa fa-trash-o"></i>
                                                </span>
                                                <span class="button-edit btn btn-icon btn-warning"
                                                    data-owner-id="{{$adMenu->id}}">
                                                <i class="fa fa-edit" data-toggle="tooltip" title="" data-original-title="Edit"></i>
                                                </span>
                                            </li>
                                        @endforeach
                                    </ol>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                        @endif
                    </li>

                @endforeach
            </ol>
            @endif
        </li>
    @endforeach
    </ol>
</div>
@if (!empty($adminMenu))
<div class="card-footer text-right">
    <input class="btn btn-primary" id="admin_save" type="submit" value="Save admin Menu">
</div>
@endif

{{-- <div class="dd nestable" id="nestable">
    <ol class="dd-list">

    <li class="dd-item" data-id="1" data-name="Home" data-slug="home-slug-1" data-new="0" data-deleted="0">
        <div class="dd-handle">Home </div>
        <span class="button-delete btn btn-danger btn-xs pull-right"
            data-owner-id="1">
        <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span class="button-edit btn btn-success btn-xs pull-right"
            data-owner-id="1">
        <i class="fa fa-pencil" aria-hidden="true"></i>
        </span>
    </li>

    <li class="dd-item" data-id="2" data-name="About Us" data-slug="about-slug-2" data-new="0" data-deleted="0">
        <div class="dd-handle">About Us</div>
        <span class="button-delete btn btn-danger btn-xs pull-right"
            data-owner-id="2">
        <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span class="button-edit btn btn-success btn-xs pull-right"
            data-owner-id="2">
        <i class="fa fa-pencil" aria-hidden="true"></i>
        </span>
    </li>

    <li class="dd-item" data-id="3" data-name="Services" data-slug="services-slug-3" data-new="0" data-deleted="0">
        <div class="dd-handle">Services</div>
        <span class="button-delete btn btn-danger btn-xs pull-right"
            data-owner-id="3">
        <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span class="button-edit btn btn-success btn-xs pull-right"
            data-owner-id="3">
        <i class="fa fa-pencil" aria-hidden="true"></i>
        </span>

        <ol class="dd-list">

        <li class="dd-item" data-id="4" data-name="UI/UX Design" data-slug="uiux-slug-4" data-new="0" data-deleted="0">
            <div class="dd-handle">UI/UX Design</div>
            <span class="button-delete btn btn-danger btn-xs pull-right"
                data-owner-id="4">
            <i class="fa fa-times" aria-hidden="true"></i>
            </span>
            <span class="button-edit btn btn-success btn-xs pull-right"
                data-owner-id="4">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            </span>
        </li>

        <li class="dd-item" data-id="5" data-name="Web Design" data-slug="webdesign-slug-5" data-new="0" data-deleted="0">
            <div class="dd-handle">Web Design </div>
            <span class="button-delete btn btn-danger btn-xs pull-right"
                data-owner-id="5">
            <i class="fa fa-times" aria-hidden="true"></i>
            </span>
            <span class="button-edit btn btn-success btn-xs pull-right"
                data-owner-id="5">
            <i class="fa fa-pencil" aria-hidden="true"></i>
            </span>
        </li>

        </ol>
    </li>
    <li class="dd-item" data-id="6" data-name="Contact Us" data-slug="contact-slug-6" data-new="0" data-deleted="0">
        <div class="dd-handle">Contact Us</div>
        <span class="button-delete btn btn-danger btn-xs pull-right"
            data-owner-id="6">
        <i class="fa fa-times" aria-hidden="true"></i>
        </span>
        <span class="button-edit btn btn-success btn-xs pull-right"
            data-owner-id="6">
        <i class="fa fa-pencil" aria-hidden="true"></i>
        </span>
    </li>

    </ol>
</div>
<div class="card-footer text-right">
    <input class="btn btn-primary" type="submit" value="Save Admin Menu">
</div> --}}
