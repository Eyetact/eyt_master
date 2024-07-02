<div id="addMenuModal" class="modal fade">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header pd-x-20">
                <h6 class="modal-title">Message Preview</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body pd-20">
                    <div class="form-group row">
                        <label for="inputName" class="col-md-3 form-label">Module Type</label>
                        <div class="col-md-9">
                            <select name="type" class="form-control custom-select select2" id="module_type">
                                <option value="">Select</option>
                                <option value="storfront">Storfront</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                    <div id="validationErrors" class="alert alert-danger hide">
                    </div>
                    {{-- <form class="form-horizontal"> --}}
                        <div id="storfront_div">
                            <form action="{{ $menu->id == null ? route('module_manager.store') : route('module_manager.update', ['menu' => $menu->id]) }}" id="storfront_form" method="POST" autocomplete="off" novalidate="novalidate">
                                @include('module_manager.storfront_form')
                            </form>
                        </div>
                        <div id="admin_div">
                            <form
                                action="{{ $menu->id == null ? route('module_manager.store') : route('module_manager.update', ['menu' => $menu->id]) }}"
                                id="admin_form" method="POST" autocomplete="off" novalidate="novalidate">
                                @include('module_manager.admin_form')
                            </form>
                        </div>

                        {{-- <div class="modal-footer">
                            <button type="button" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div> --}}
                    {{-- </form> --}}
            </div>
        </div>
    </div>
</div>
