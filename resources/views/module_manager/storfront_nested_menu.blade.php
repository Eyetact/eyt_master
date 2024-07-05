<form action="{{ route('module_manager.menu_update') }}" id="storfront_nested_form" method="POST" autocomplete="off" novalidate="novalidate">
    @csrf
    <textarea name="storfront_json" class="hide" id="storfront_json" cols="30" rows="10"></textarea>
    <input type="hidden" name="type" value="storfront">
</form>

<div class="storfront_nested_form">
    <div class="dd nestable" id="storfront_nestable">
        {{-- <ol class="dd-list" id="storfront_menu_list">
            @foreach ($storfrontMenu as $sMenu)
                <li class="dd-item" data-json="{{json_encode($sMenu)}}" data-id="{{$sMenu->id}}" data-name="{{$sMenu->name}}" data-meta_title="{{$sMenu->meta_title}}" data-meta_description="{{$sMenu->meta_description}}" data-code="{{$sMenu->code}}" data-path="{{$sMenu->path}}" data-is_enable="{{$sMenu->is_enable}}" data-include_in_menu="{{$sMenu->include_in_menu}}"  data-assigned_attributes="{{$sMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($sMenu->created_date))}}">
                    <div class="dd-handle">
                        {{$sMenu->name}} <input type="hidden" class="storfront-menu" value="{{$sMenu->id}}">
                        @if ($sMenu->is_deleted)
                            <span class="tag tag-deleted  tag-red">Deleted</span>
                        @endif
                    </div>

                    @if ($sMenu->children->count())
                        <ol class="dd-list">
                            @foreach ($sMenu->children as $cMenu)
                                <li class="dd-item" data-json="{{json_encode($cMenu)}}" data-id="{{$cMenu->id}}" data-name="{{$cMenu->name}}" data-meta_title="{{$cMenu->meta_title}}" data-meta_description="{{$cMenu->meta_description}}" data-code="{{$cMenu->code}}" data-path="{{$cMenu->path}}" data-is_enable="{{$cMenu->is_enable}}" data-include_in_menu="{{$cMenu->include_in_menu}}"  data-assigned_attributes="{{$cMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($cMenu->created_date))}}">
                                    <div class="dd-handle">
                                        {{$cMenu->name}}<input type="hidden" class="storfront-menu" value="{{$cMenu->id}}">
                                        @if ($cMenu->is_deleted)
                                            <span class="tag tag-deleted  tag-red">Deleted</span>
                                        @endif
                                    </div>

                                    @if ($cMenu->children->count())
                                        <ol class="dd-list">
                                            @foreach ($cMenu->children as $ccMenu)
                                                <li class="dd-item" data-json="{{json_encode($ccMenu)}}" data-id="{{$ccMenu->id}}" data-name="{{$ccMenu->name}}" data-meta_title="{{$ccMenu->meta_title}}" data-meta_description="{{$ccMenu->meta_description}}" data-code="{{$ccMenu->code}}" data-path="{{$ccMenu->path}}" data-is_enable="{{$ccMenu->is_enable}}" data-include_in_menu="{{$ccMenu->include_in_menu}}"  data-assigned_attributes="{{$ccMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($ccMenu->created_date))}}">
                                                    <div class="dd-handle">
                                                        {{$ccMenu->name}}<input type="hidden" class="storfront-menu" value="{{$ccMenu->id}}">
                                                        @if ($ccMenu->is_deleted)
                                                            <span class="tag tag-deleted  tag-red">Deleted</span>
                                                        @endif
                                                    </div>

                                                    @if ($ccMenu->children->count())
                                                        <ol class="dd-list">
                                                            @foreach ($ccMenu->children as $cccMenu)
                                                                <li class="dd-item" data-json="{{json_encode($cccMenu)}}" data-id="{{$cccMenu->id}}" data-name="{{$cccMenu->name}}" data-meta_title="{{$cccMenu->meta_title}}" data-meta_description="{{$cccMenu->meta_description}}" data-code="{{$cccMenu->code}}" data-path="{{$cccMenu->path}}" data-is_enable="{{$cccMenu->is_enable}}" data-include_in_menu="{{$cccMenu->include_in_menu}}"  data-assigned_attributes="{{$cccMenu->assigned_attributes}}" data-created_date="{{date('m-d-Y',strtotime($cccMenu->created_date))}}">
                                                                    <div class="dd-handle">
                                                                        {{$cccMenu->name}}<input type="hidden" class="storfront-menu" value="{{$cccMenu->id}}">
                                                                        @if ($cccMenu->is_deleted)
                                                                            <span class="tag tag-deleted  tag-red">Deleted</span>
                                                                        @endif
                                                                    </div>
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
        </ol> --}}




        <ol class="dd-list" id="storfront_menu_list">
            @foreach ($storfrontMenu as $aMenu)
                <li class="dd-item @if ($aMenu->is_delete) is_delete @endif @if ($aMenu->children->count() == 0) no-pad @endif"
                    data-path="{{ route('module_manager.edit', $aMenu->module_id) }}"
                    data-id="{{ $aMenu->module_id_id }}" data-json="{{ json_encode($aMenu) }}"
                    data-id="{{ $aMenu->id }}" data-name="{{ $aMenu->name }}" data-module="{{ $aMenu->module_id }}"
                    data-code="{{ $aMenu->code }}" data-path="{{ $aMenu->path }}"
                    data-is_enable="{{ $aMenu->is_enable }}" data-include_in_menu="{{ $aMenu->include_in_menu }}"
                    data-assigned_attributes="{{ $aMenu->assigned_attributes }}"
                    data-created_date="{{ date('m-d-Y', strtotime($aMenu->created_date)) }}">
                    <div class="dd-handle">
                        {{ $aMenu->name }}
                        {{-- - ( {{ $aMenu->children->count() }} ) --}}
                        <div>
                            @if ($aMenu->module->parent_id > 0)
                                                @php
                                                    $m = \App\Models\Module::find($aMenu->module->parent_id);
                                                @endphp
                                                <span class="badge badge-danger">
                                                    @if ($aMenu->module->addable)
                                                        Addable
                                                    @elseif($aaMenu->module->shared)
                                                        Shared
                                                    @endif
                                                    Sub to {{ $m->name }}

                                                </span>
                                            @endif
                            @if (empty($aMenu->module->migration))
                                <span class="badge badge-danger">Label</span>
                            @endif
                            @if ($aMenu->is_delete)
                                <span class="badge badge-danger">Deleted</span>
                            @endif
                        </div>
                        <input type="hidden" class="admin-menu" value="{{ $aMenu->id }}">
                        @if ($aMenu->is_deleted)
                            <span class="tag tag-deleted  tag-red">Deleted</span>
                        @endif
                    </div>
                    {{-- @if ($aMenu->is_delete == 0)
                        <button data-path="{{ route('module_manager.addSub', $aMenu->module_id) }}" class="sub-add"
                            type="button">+</button>
                    @endif --}}


                    @if ($aMenu->children->count())
                        <ol class="dd-list">
                            @foreach ($aMenu->children()->orderBy('sequence', 'asc')->get() as $aaMenu)
                                <li class="dd-item @if ($aaMenu->is_delete) is_delete @endif @if ($aaMenu->children->count() == 0) no-pad @endif"
                                    data-path="{{ route('module_manager.edit', $aaMenu->module_id) }}"
                                    data-json="{{ json_encode($aaMenu) }}" data-id="{{ $aaMenu->id }}"
                                    data-name="{{ $aaMenu->name }}" data-module="{{ $aaMenu->module_id }}"
                                    data-code="{{ $aaMenu->code }}" data-path="{{ $aaMenu->path }}"
                                    data-is_enable="{{ $aaMenu->is_enable }}"
                                    data-include_in_menu="{{ $aaMenu->include_in_menu }}"
                                    data-assigned_attributes="{{ $aaMenu->assigned_attributes }}"
                                    data-created_date="{{ date('m-d-Y', strtotime($aaMenu->created_date)) }}">
                                    <div class="dd-handle">
                                        {{ $aaMenu->name }}
                                        {{-- - ( {{ $aaMenu->children->count() }} ) --}}
                                        <div>
                                            @if ($aaMenu->module->parent_id > 0)
                                                @php
                                                    $m = \App\Models\Module::find($aaMenu->module->parent_id);
                                                @endphp
                                                <span class="badge badge-danger">
                                                    @if ($aaMenu->module->addable)
                                                        Addable
                                                    @elseif($aaMenu->module->shared)
                                                        Shared
                                                    @endif
                                                    Sub to {{ $m->name }}

                                                </span>
                                            @endif
                                            @if (empty($aaMenu->module->migration))
                                                <span class="badge badge-danger">Label</span>
                                            @endif
                                            @if ($aaMenu->is_delete)
                                                <span class="badge badge-danger">Deleted</span>
                                            @endif
                                        </div>
                                        <input type="hidden" class="admin-menu" value="{{ $aaMenu->id }}">
                                        @if ($aaMenu->is_deleted)
                                            <span class="tag tag-deleted  tag-red">Deleted</span>
                                        @endif
                                    </div>
                                    {{-- @if ($aaMenu->is_delete == 0)
                                        <button data-path="{{ route('module_manager.addSub', $aaMenu->module_id) }}"
                                            class="sub-add" type="button">+</button>
                                    @endif --}}

                                    @if ($aaMenu->children->count())
                                        <ol class="dd-list">
                                            @foreach ($aaMenu->children as $aaaMenu)
                                                <li class="dd-item @if ($aaaMenu->is_delete) is_delete @endif @if ($aaaMenu->children->count() == 0) no-pad @endif"
                                                    data-json="{{ json_encode($aaaMenu) }}"
                                                    data-id="{{ $aaaMenu->id }}" data-name="{{ $aaaMenu->name }}"
                                                    data-path="{{ route('module_manager.edit', $aaaMenu->module_id) }}"
                                                    data-module="{{ $aaaMenu->module_id }}"
                                                    data-code="{{ $aaaMenu->code }}" data-path="{{ $aaaMenu->path }}"
                                                    data-is_enable="{{ $aaaMenu->is_enable }}"
                                                    data-include_in_menu="{{ $aaaMenu->include_in_menu }}"
                                                    data-assigned_attributes="{{ $aaaMenu->assigned_attributes }}"
                                                    data-created_date="{{ date('m-d-Y', strtotime($aaaMenu->created_date)) }}">
                                                    <div class="dd-handle">
                                                        {{ $aaaMenu->name }}
                                                        <div>
                                                            @if ($aaaMenu->module->parent_id > 0)
                                                @php
                                                    $m = \App\Models\Module::find($aaaMenu->module->parent_id);
                                                @endphp
                                                <span class="badge badge-danger">
                                                    @if ($aaaMenu->module->addable)
                                                        Addable
                                                    @elseif($aaMenu->module->shared)
                                                        Shared
                                                    @endif
                                                    Sub to {{ $m->name }}

                                                </span>
                                            @endif
                                                            @if (empty($aaaMenu->module->migration))
                                                                <span class="badge badge-danger">Label</span>
                                                            @endif
                                                            @if ($aaaMenu->is_delete)
                                                                <span class="badge badge-danger">Deleted</span>
                                                            @endif
                                                        </div>
                                                        <input type="hidden" class="admin-menu"
                                                            value="{{ $aaaMenu->id }}">
                                                        @if ($aaaMenu->is_deleted)
                                                            <span class="tag tag-deleted  tag-red">Deleted</span>
                                                        @endif
                                                    </div>


                                                    {{--
                                                    @if ($aaaMenu->children->count())
                                                        <ol class="dd-list">
                                                            @foreach ($aaaMenu->children as $adMenu)
                                                                <li class="dd-item @if ($adMenu->is_delete) is_delete @endif"
                                                                    data-json="{{ json_encode($adMenu) }}"
                                                                    data-id="{{ $adMenu->id }}"
                                                                    data-name="{{ $adMenu->name }}"
                                                                    data-module="{{ $adMenu->module }}"
                                                                    data-code="{{ $adMenu->code }}"
                                                                    data-path="{{ $adMenu->path }}"
                                                                    data-is_enable="{{ $adMenu->is_enable }}"
                                                                    data-include_in_menu="{{ $adMenu->include_in_menu }}"
                                                                    data-assigned_attributes="{{ $adMenu->assigned_attributes }}"
                                                                    data-created_date="{{ date('m-d-Y', strtotime($adMenu->created_date)) }}">
                                                                    <div class="dd-handle">
                                                                        {{ $adMenu->name }}<input type="hidden"
                                                                            class="admin-menu"
                                                                            value="{{ $adMenu->id }}">
                                                                        @if ($adMenu->is_deleted)
                                                                            <span
                                                                                class="tag tag-deleted  tag-red">Deleted</span>
                                                                        @endif
                                                                    </div>


                                                                </li>
                                                            @endforeach
                                                        </ol>
                                                    @endif --}}
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
</div>

{{-- @if (!empty($storfrontMenu))
    <div class="card-footer text-right">
        <input class="btn btn-primary" id="storfront_save" type="submit" value="Save Storfront Menu">
    </div>
@endif --}}
