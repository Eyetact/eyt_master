<form action="{{ $role->id == null ? route('role.store') : route('role.update', ['role' => $role->id]) }}" method="POST"
    id="role_form" novalidate="" class="needs-validation">
    <div class="card-body">

        @csrf
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="input-box">
                    <label for="name" class="input-label">Role</label>
                    <input class="google-input @error('name') is-invalid @enderror" name="name" type="text"
                        value="{{ old('name', $role->name) }}" required="">
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="valid-feedback">Looks good!</div>
                    <div class="invalid-feedback">Please enter role.</div>
                </div>
                <input type="hidden" value="web" name="guard_name">
            </div>
            <div class="form-group col-sm-12 col-lg-12">
                <div class="permission input-box">
                    <label for="permission">Permission:</label>
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <div class="row">
                            @foreach ($groupPermission as $key => $permissions)
                            @canany([$permissions[0]->name])
                            {{-- @canany([$permissions[0]->name,$permissions[1]->name,$permissions[2]->name]) --}}
                                <div class="col-sm-6 role-group">
                                    <div class="custom-checkbox permission  input-box">
                                        <input id="{{ $key }}" type="checkbox" class=" check-all"
                                            name="checkAll">
                                        <label for="{{ $key }}">
                                            <b>{{ Str::ucfirst(explode('.', $permissions[0]->name)[1]) }}</b></label>
                                    </div>

                                    @foreach ($permissions as $permission)
                                        @can($permission->name)
                                        <div class="custom-control custom-checkbox ms-3 row  input-box">
                                            <div class="col-md-5">
                                                <input id="{{ $permission->id }}" type="checkbox" class="check-one"
                                                    name="permission_data[]" value="{{ $permission->name }}"
                                                    {{ $role->id != null && count($role->permission_data) > 0 && isset($role->permission_data[$permission->id]) ? 'checked' : '' }}>
                                                <input id="{{ $permission->module }}" type="hidden"
                                                    name="permission_module[{{ $permission->id }}]"
                                                    value="{{ $permission->module }}">
                                                <label
                                                    for="{{ $permission->id }}">{{ Str::ucfirst($permission->name) }}</label>
                                            </div>
                                            <?php
                                            $edit_no = 0;
                                            $edit_type = '';
                                            $permission_id = 0;
                                            $scheduler_data = ['scheduler_no' => '', 'type' => ''];
                                            if ($role->scheduler->count() > 0) {
                                                $scheduler = $role->scheduler->toArray();
                                                if (array_search($permission->id, array_column($scheduler, 'permission_id')) !== false) {
                                                    $key = array_search($permission->id, array_column($scheduler, 'permission_id'));
                                                    $scheduler_data = $scheduler[$key];
                                                }

                                                // dump($scheduler[$key]);

                                                if (array_search($permission->id, array_column($scheduler, 'permission_id')) !== false) {
                                                    // $edit_no=$scheduler['scheduler_no'];
                                                    // $edit_type=$scheduler['type'];
                                                    $permission_id = $scheduler;
                                                }
                                                // echo $key;
                                            }
                                            // dump($scheduler_data);
                                            // echo $role->scheduler->count().$edit_no.$edit_type;
                                            ?>
                                            <div class="col-md-7">
                                                <div class="row">


                                                    @if (str_contains($permission->name, 'edit'))

                                                        <div class="col-md-6 select-box">

                                                            <select name="schedule_no_edit[{{ $permission->id }}]"
                                                                class="google-input" title="Number">

                                                                @for ($i = 0; $i <= 10; $i++)
                                                                    <option value="{{ $i }}" @selected( $permission->getCountByrole($role->id) == $i )>
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6  select-box">

                                                            <select name="schedule_time_edit[{{ $permission->id }}]"
                                                                class="google-input schedule_time_edit schedule_time"
                                                                title="Time">
                                                                <option value="day" @selected( $permission->getCountByrole($role->id,1) == 'day' )>
                                                                    Days</option>
                                                                <option value="week" @selected( $permission->getCountByrole($role->id,1) == 'week' )>
                                                                    Weeks</option>
                                                                <option value="month" @selected( $permission->getCountByrole($role->id,1) == 'month' )>
                                                                    Months</option>
                                                                <option value="year" @selected( $permission->getCountByrole($role->id,1) == 'year' )>
                                                                    Years</option>
                                                            </select>
                                                        </div>
                                                    @endif

                                                </div>

                                                @if (str_contains($permission->name, 'delete'))
                                                    <div class="row">
                                                        <div class="col-md-6  select-box">
                                                            <select name="schedule_no_delete[{{ $permission->id }}]"
                                                                class="google-input schedule_no_delete schedule_no"
                                                                title="Number">

                                                                @for ($i = 0; $i <= 10; $i++)
                                                                    <option value="{{ $i }}" @selected( $permission->getCountByrole($role->id) == $i )>
                                                                        {{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6  select-box">
                                                            <select name="schedule_time_delete[{{ $permission->id }}]"
                                                                class="google-input schedule_time_delete schedule_time"
                                                                title="Time">
                                                                <option value="day" @selected( $permission->getCountByrole($role->id,1) == 'day' )>
                                                                    Days</option>
                                                                <option value="week" @selected( $permission->getCountByrole($role->id,1) == 'week' )>
                                                                    Weeks</option>
                                                                <option value="month" @selected( $permission->getCountByrole($role->id,1) == 'month' )>
                                                                    Months</option>
                                                                <option value="year" @selected( $permission->getCountByrole($role->id,1) == 'year' )>
                                                                    Years</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>
                                        @endcan
                                    @endforeach
                                </div>
                                @endcanany
                            @endforeach
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-primary" type="submit">{{ $role->id == null ? 'Save' : 'Update' }}</button>
        <a href="{{ route('role.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>
