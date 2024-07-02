<div class="card mt-5">
    <div class="card-body">
        <form action="{{ route('module_manager.update', $module->id) }}" method="POST" id="mailboxForm"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="menu_type" value="admin">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div class="">
                        <div class="card-header">
                            <h3 class="card-title">{{ $module->name }} </h3>
                            &nbsp &nbsp
                            <span id="currentEditName"></span>
                        </div>
                        <div class="card-body pb-2">
                            <div class="row">
                                @if(!empty($module->migration))

                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="name">Name <span
                                            class="text-red">*</span></label>
                                    <input type="text" name="name" id="aname" class="form-control"
                                        value="{{ $module->name }}">
                                    <input type="hidden" name="id" id="aid" value="">
                                </div>
                                @endif

                                @if(!empty($module->migration))


                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="name">Code <span
                                            class="text-red">*</span></label>
                                    <input type="text" readonly id="aname" class="form-control"
                                        value="{{ $module->code }}">

                                </div>
                                @endif
                                @if(!empty($module->migration))
                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="path">Path <span
                                            class="text-red">*</span></label>
                                    <input type="text" readonly id="apath" class="form-control"
                                        value="{{ $module->menu->path }}">
                                </div>
                                @endif

                                <div class="col-sm-12 form-group">
                                    <label class="form-label" for="path">Sidebar Name <span
                                            class="text-red">*</span></label>
                                    <input type="text" name="sidebar_name" id="sidebar_name" class="form-control"
                                        value="{{ $module->menu->sidebar_name }}">
                                </div>

                                @if($module->menu->menu_type == 'admin')

                                <div class="form-group col-sm-4">
                                    <label class="custom-switch form-label">
                                        <input type="checkbox" @checked($module->menu->include_in_menu) name="include_in_menu"
                                            id="ainclude_in_menu" class="custom-switch-input" id="is_enable">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Include in menu</span>
                                    </label>
                                </div>

                                @endif

                                @if(!empty($module->migration))


                                <div class="form-group col-sm-4">
                                    <label class="custom-switch form-label">
                                        <input type="checkbox" @checked($module->is_system) name="is_system"
                                            id="is_system" class="custom-switch-input" id="is_system">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description">Global</span>
                                    </label>
                                </div>
                                @endif
                                <div class="form-group col-sm-4">
                                    <label class="custom-switch form-label">
                                        <input type="checkbox" @checked($module->status) name="status" id="status"
                                            class="custom-switch-input" id="status" checked>
                                        <span class="custom-switch-indicator"></span>
                                        <span
                                            class="custom-switch-description">Status</span>
                                    </label>
                                </div>
                                @if(!empty($module->migration))

                                <div class="col-sm-12 input-box">
                                    <label class="form-label"
                                        for="module">Type<span
                                            class="text-red">*</span></label>

                                    
                                    <select name="mtype"
                                        class="google-input module" id="mtype"
                                        required>
                                        <option disabled value="" selected>Select</option>
                                        <option @selected($module->type == 'stander')  value="stander" >Stander</option>
                                        <option @selected($module->type == 'sortable') value="sortable" >Sortable</option>
                                       
                                    </select>
                                   

                                </div>
                                @endif
                                {{-- <div class="col-sm-12 form-group">
                            <label class="form-label" for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" class="form-control" value="">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea class="form-control" name="meta_description" autocomplete="off" id="description" rows="2"></textarea>
                        </div> --}}
                                {{-- <div class="col-sm-12 form-group">
                        <label class="form-label" for="created_date">Created Date <span
                                class="text-red">*</span></label>
                        <input type="date" name="created_date" id="acreated_date" class="form-control"
                            value="">
                    </div> --}}






                            </div>
                        </div>
                        <div class="card-footer text-right">

                            @role('super')
                                <a title="Delete" class="btn btn-danger" href="{{ route('force-delete', $module->id) }}"
                                    type="button" alt="">Force Delete</a>
                            @endrole

                            @if ($module->is_delete == 0)
                            <input title="Reset form" class="btn btn-danger" data-id="{{ $module->id }}"
                                id="remove-admin-menu" type="button" value="Delete">
                        @endif
                            @if ($module->is_delete == 1)
                                <input title="Reset form" class="btn btn-success" data-id="{{ $module->id }}"
                                 id="restore-admin-menu" type="button"
                                    value="Restore">
                            @endif

                            <input title="Save module" class="btn btn-primary" id="submit-admin-menu" type="submit"
                                value="Save">
                            {{-- <input title="Reset form" class="btn btn-warning" type="reset" value="Reset"> --}}
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
