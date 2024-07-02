<form action="{{ route('module_manager.storSub', $id) }}" id="moduleCreate" method="POST" autocomplete="off">
    @csrf

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Basics</h3>
                </div>
                <div class="card-body pb-2">
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

                                        {{-- <div class="col-sm-12 input-box">
                                            <label class="form-label" for="module">Module<span
                                                    class="text-red">*</span></label>

                                            @php
                                                $module_ids = \App\Models\Module::where(
                                                    'user_id',
                                                    auth()->user()->id,
                                                )->pluck('id');
                                            @endphp
                                            <select name="module" class="google-input module" id="module" required>
                                                <option value="" selected>Select Module</option>
                                                @foreach (\App\Models\MenuManager::where('parent', '0')->where('menu_type', 'admin')->whereIn('module_id', $module_ids)->orderBy('sequence', 'asc')->get() as $module)
                                                    <option value="{{ $module->id }}"
                                                        @if (in_array($module->id, [1, 2, 3])) disabled @endif>
                                                        {{ $module->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <label id="module-error" class="error text-red hide" for="module"></label>

                                        </div> --}}


                                        <div class="col-sm-12 form-group">
                                            <label class="form-label" for="name">Name <span
                                                    class="text-red">*</span></label>
                                            <input type="text" name="name" id="aname" class="form-control"
                                                value="">
                                            <input type="hidden" name="id" id="aid" value="">
                                        </div>

                                        <div class="col-sm-12 form-group">
                                            <label class="form-label" for="code">Code <span
                                                    class="text-red">*</span></label>
                                            <input type="text" name="code" id="code" class="form-control"
                                                value="">

                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer text-right">
                                    <input title="Reset form" class="btn btn-danger d-none" id="remove-admin-menu"
                                        type="button" value="Delete">
                                    <input title="Reset form" class="btn btn-success d-none" id="restore-admin-menu"
                                        type="button" value="Restore">
                                    <input title="Save module" class="btn btn-primary" id="submit-admin-menu"
                                        type="submit" value="Save">
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
