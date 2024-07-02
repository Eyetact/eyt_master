
            <form action="{{ route('permission.store') }}" method="POST" id="mailboxForm" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="name">Permission</label>
                        <div class="permission">
                            <div class="each-input">
                                <input
                                    class="permission Input permissionInput form-control @error('name') is-invalid @enderror"
                                    name="name[0]" type="text" placeholder="Enter permission name"
                                    value="{{ old('name[0]') }}">

                            </div>
                            <div class="append-list"></div>
                        </div>

                        <!-- <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" required="" placeholder="Name"> -->
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter name.</div>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="module">Module</label>

                        <select class="form-control custom-select @error('module') is-invalid @enderror" name="module"
                            id="moduleId">
                            @if (count($moduleList))
                                @foreach ($moduleList as $module)
                                    <option value="{{ $module->id }}"
                                        {{ isset($value) && $module->id == $value->module ? 'selected' : '' }}>
                                        {{ $module->name }} </option>
                                @endforeach
                            @else
                                <option disabled value="No module found selected">No module found selected</option>
                            @endif
                        </select>
                        <!-- <input class="form-control @error('module') is-invalid @enderror" name="module" type="text" required="" placeholder="Module"> -->
                        @error('module')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter module.</div>
                    </div>

                    {{-- <div class="form-group col-sm-3">

                            <label>Type<span class="text-danger">*</span></label>
                            <select class="form-control custom-select" name="type" tabindex="null">
                                <option selected disabled>Select Type</option>
                                <option value="user">User</option>
                                <option value="customer">Customer</option>
                            </select>

                        @error('type')
                            <label id="type-error" class="error" for="type">{{ $message }}</label>
                        @enderror
                    </div> --}}
                    {{-- <div class="form-group col-sm-3">
                        <label for="count">Count</label>
                        <div class="permission">
                            <div class="each-input">
                                <input
                                    class="permission Input permissionInput form-control @error('count') is-invalid @enderror"
                                    name="count" type="number"
                                    value="{{ old('count') }}">

                            </div> --}}
                            <div class="append-list"></div>
                        </div>

                        <!-- <input class="form-control @error('count') is-invalid @enderror" count="count" type="text" required="" placeholder="Name"> -->
                        @error('count')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="valid-feedback">Looks good!</div>
                        <div class="invalid-feedback">Please enter count.</div>
                    </div>

                    <input type="hidden" name="guard_name" value="web">

                </div>



                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">Save</button>
                    <a href="{{ route('main_mailbox.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
