@csrf
<input type="hidden" name="menu_type" value="admin">
<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="">
            <div class="card-header">
                <h3 class="card-title">Admin </h3>
                &nbsp &nbsp
                <span id="currentEditName"></span>
            </div>
            <div class="card-body pb-2">
                <div class="row">
                    <div class="col-sm-12 form-group">
                        <label class="form-label" for="name">Name <span class="text-red">*</span></label>
                        <input type="text" name="name" id="aname" class="form-control" value="">
                        <input type="hidden" name="id" id="aid" value="">
                    </div>

                    <div class="col-sm-12 form-group">
                        <label class="form-label" for="code">Code <span class="text-red">*</span></label>
                        <input type="text" name="code" id="code" class="form-control" value="">

                    </div>

                    <div class="col-sm-12 form-group">
                        <label class="form-label" for="path">Path <span class="text-red">*</span></label>
                        <input type="text" name="path" id="apath" class="form-control" value="">
                    </div>

                    <div class="col-sm-12 form-group">
                        <label class="form-label" for="path">Sidebar Name <span class="text-red">*</span></label>
                        <input type="text" name="sidebar_name" id="sidebar_name" class="form-control" value="">
                    </div>

                    <div class="form-group col-sm-6">
                        <label class="custom-switch form-label">
                            <input type="checkbox" name="include_in_menu" id="ainclude_in_menu"
                                class="custom-switch-input" id="is_enable">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Include in menu</span>
                        </label>
                    </div>

                    <div class="form-group col-sm-6">
                        <label class="custom-switch form-label">
                            <input type="checkbox" name="is_system" id="is_system"
                                class="custom-switch-input" id="is_system">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">System(Master)</span>
                        </label>
                    </div>
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

                    {{-- <span class="fast-btn">
                        Add Attributes
                    </span>

                    <div class="fast">
                        <div class="attr_header row flex justify-content-end my-5 align-items-end">
                            <input title="Reset form" class="btn btn-success" id="add_new" type="button"
                                value="+ Add">
                        </div>

                        <table class="table table-bordered align-items-center mb-0" id="tbl-field">
                            <thead>
                                <tr>
                                    <th width="30">#</th>
                                    <th>{{ __('Field name') }}</th>
                                    <th>{{ __('Column Type') }}</th>
                                    <th width="200">{{ __('Length') }}</th>
                                    <th>{{ __('Input Type') }}</th>
                                    <th>{{ __('Required') }}</th>
                                    <th>{{ __('Options') }}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr draggable="true" containment="tbody" ondragstart="dragStart()"
                                    ondragover="dragOver()" style="cursor: move;">
                                    <td class="text-center">1</td>
                                    <td>
                                        <div class="input-box">
                                            <input type="text" name="fields[]" class="form-control google-input"
                                                placeholder="{{ __('Field Name') }}" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-box">
                                            <select name="column_types[]"
                                                class="form-select  google-input form-column-types" required>
                                                <option value="" disabled selected>
                                                    --{{ __('Select column type') }}--
                                                </option>
                                                @foreach (['string', 'integer', 'text', 'bigInteger', 'boolean', 'char', 'date', 'time', 'year', 'dateTime', 'decimal', 'double', 'enum', 'float', 'foreignId', 'tinyInteger', 'mediumInteger', 'tinyText', 'mediumText', 'longText'] as $type)
                                                    <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="select_options[]" class="form-option">
                                            <input type="hidden" name="constrains[]" class="form-constrain">
                                            <input type="hidden" name="foreign_ids[]" class="form-foreign-id">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="input-box">
                                                    <input type="number" name="min_lengths[]"
                                                        class=" google-input form-control form-min-lengths"
                                                        min="1" placeholder="Min">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="input-box">
                                                    <input type="number" name="max_lengths[]"
                                                        class="  google-input form-control form-max-lengths"
                                                        min="1" placeholder="Max">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-box">
                                            <select name="input_types[]"
                                                class="form-select form-input-types  google-input" required>
                                                <option value="" disabled selected>--
                                                    {{ __('Select input type') }}
                                                    --</option>
                                                <option value="" disabled>
                                                    {{ __('Select the column type first') }}
                                                </option>
                                            </select>
                                        </div>
                                        <input type="hidden" name="mimes[]" class="form-mimes">
                                        <input type="hidden" name="file_types[]" class="form-file-types">
                                        <input type="hidden" name="files_sizes[]" class="form-file-sizes">
                                        <input type="hidden" name="steps[]" class="form-step" placeholder="step">
                                    </td>

                                    <td class="d-flex align-items-center justify-center custom-td">

                                        <div class="form-check form-switch ">


                                            <div class="form-group col-sm-6">
                                                <label class="custom-switch form-label">
                                                    <input type="checkbox" name="requireds[]"
                                                        class="custom-switch-input switch-requireds" id="requireds[]"
                                                        checked>
                                                    <span class="custom-switch-indicator"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="default_values[]" class="form-default-value"
                                            placeholder="{{ __('Default Value (optional)') }}">
                                    </td>

                                    <td>
                                        <div class="row">
                                            <div class="form-group col-sm-12">
                                                <label class="custom-switch form-label">
                                                    <input type="checkbox" name="is_enable"
                                                        class="custom-switch-input" id="is_enable[]">
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">Status</span>
                                                </label>
                                            </div>
                                            <div class="form-group col-sm-12">
                                                <label class="custom-switch form-label">
                                                    <input type="checkbox" name="is_system"
                                                        class="custom-switch-input" id="is_system[]">
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="custom-switch-description">System</span>
                                                </label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-outline-danger btn-xs btn-delete">
                                            x
                                        </button>
                                    </td>

                                </tr>




                            </tbody>
                        </table>
                    </div> --}}





                </div>
            </div>
            <div class="card-footer text-right">
                <input title="Reset form" class="btn btn-danger d-none" id="remove-admin-menu" type="button"
                    value="Delete">
                <input title="Reset form" class="btn btn-success d-none" id="restore-admin-menu" type="button"
                    value="Restore">
                <input title="Save module" class="btn btn-primary" id="submit-admin-menu" type="submit"
                    value="Save">
                {{-- <input title="Reset form" class="btn btn-warning" type="reset" value="Reset"> --}}
            </div>
        </div>
    </div>
</div>
