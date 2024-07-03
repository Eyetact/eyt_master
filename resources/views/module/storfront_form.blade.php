<form action="{{ $menu->id == null ? route('menu.store') : route('menu.update', ['menu' => $menu->id]) }}"
    id="storfront_form" method="POST" autocomplete="off" novalidate="novalidate">
    @csrf
    <input type="hidden" name="menu_type" value="storfront">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Storfront</h3>
                    &nbsp &nbsp
                    <span id="currentEditName"></span>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        {{-- <div class="col-sm-12 form-group">
                            <label class="form-label" for="module">Module<span class="text-red">*</span></label>
                            <select name="module" class="form-control module" id="module">
                                <option value="" selected>Select Module</option>
                                @foreach ($moduleData as $module)
                                    <option value="{{$module->id}}" >{{$module->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <div class="col-sm-12 form-group">
                            <label class="form-label" for="name">Name <span class="text-red">*</span></label>
                            <input type="text" name="name" id="sname" class="form-control" value="">
                            <span class="text-red-500"> @error('name')
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form-label" for="code">Code <span class="text-red">*</span></label>
                            <input type="text" name="code" id="scode" class="form-control" value="">
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form-label" for="path">Path <span class="text-red">*</span></label>
                            <input type="text" name="path" id="spath" class="form-control" value="">
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="custom-switch form-label">
                                <input type="checkbox" name="is_enable" id="sis_enable" class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Status</span>
                            </label>
                        </div>
                        <div class="form-group col-sm-6">
                            <label class="custom-switch form-label">
                                <input type="checkbox" name="include_in_menu" id="sinclude_in_menu"
                                    class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Include in menu</span>
                            </label>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form-label" for="meta_title">Meta Title</label>
                            <input type="text" name="meta_title" id="smeta_title" class="form-control"
                                value="">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label" for="meta_description">Meta Description</label>
                            <textarea class="form-control" name="meta_description" id="smeta_description" autocomplete="off" id="description"
                                rows="2"></textarea>
                        </div>
                        <div class="col-sm-12 form-group">
                            <label class="form-label" for="created_date">Created Date <span
                                    class="text-red">*</span></label>
                            <input type="date" name="created_date" id="screated_date" class="form-control"
                                value="">
                        </div>
                        <div class="form-group col-sm-12">
                            <label class="form-label" for="assigned_attributes">Assigned Attributes <span
                                    class="text-red">*</span></label>
                            <textarea class="form-control" name="assigned_attributes" id="sassigned_attributes"autocomplete="off" id="description"
                                rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <input title="Save module" class="btn btn-primary" type="submit" value="Create">
                    <input title="Reset form" class="btn btn-warning" type="reset" value="Reset">
                </div>
            </div>
        </div>
    </div>
</form>
