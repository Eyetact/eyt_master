@php
    $data = json_decode($attribute->fields_info, true);
    // dump($data);
@endphp


<form id="attribute-create" autocomplete="off" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Basics</h3>
                </div>
                <div class="card-body pb-2">
                    <div class="row">
                        <div class="col-sm-12 input-box">
                            <label class="form-label" for="module">Add Attribute To<span
                                    class="text-red">*</span></label>
                            <select name="module" class="google-input module" id="module">
                                <option value="" selected>Select Module</option>
                                @foreach ($moduleData as $module)
                                    <option value="{{ $module->id }}"
                                        @if (in_array($module->id, [1, 2, 3])) disabled @endif
                                        {{ $module->id == $attribute->module ? 'selected' : '' }}>{{ $module->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="module-attribute-create-error"
                                class="error text-danger d-none error-message"></span>
                        </div>

                        <div class="col-sm-12 input-box">
                            <label class="form-label" for="name">Name<span class="text-red">*</span></label>
                            <input type="text" name="name" id="name"
                                class="google-input @error('name') is-invalid @enderror"
                                value="{{ old('name', $attribute->name) }}">
                            <span id="name-attribute-create-error"
                                class="error text-danger d-none error-message"></span>
                        </div>

                        <div class="col-sm-12 input-box">
                            <label class="form-label" for="_code">Code<span class="text-red">*</span></label>
                            <input type="text" name="_code"
                                class="google-input input-code @error('_code') is-invalid @enderror"
                                value="{{ old('_code', $attribute->code) }}">
                            {{-- <small class="text-secondary">
                                <ul class="my-1 mx-2 p-0">
                                    <li>is not allowed to use numbers or ( ID word ) or symbols</li>
                                </ul>
                            </small> --}}
                            <span id="_code-attribute-create-error"
                                class="error text-danger d-none error-message"></span>
                        </div>
                    </div>

                    <div class="row">

                        <div class="input-box col-sm-12">
                            {{-- <label class="form-label">Select Colmun type<span class="text-red">*</span></label>

                            <select name="column_types" class="form-select  google-input form-column-types" required>
                                    <option value="" disabled selected>--{{ __('Select column type') }}--
                                    </option>
                                    @foreach (['string', 'integer', 'text', 'bigInteger', 'boolean', 'char', 'date', 'time', 'year', 'dateTime', 'decimal', 'double', 'enum', 'float', 'foreignId', 'tinyInteger', 'mediumInteger', 'tinyText', 'mediumText', 'longText'] as $type)
                                        <option value="{{ $type }}">{{ ucwords($type) }}</option>
                                    @endforeach
                                </select> --}}

                            <input type="hidden" name="column_types" id="type" class="form-column-types" />


                            <div class="options">
                                <input type="hidden" name="select_options" class="form-option">
                                <input type="hidden" name="constrains" class="form-constrain">
                                <input type="hidden" name="foreign_ids" class="form-foreign-id">

                            </div>
                        </div>

                        <div class="input-box col-sm-12">
                            <label class="form-label">Select Attribute type<span class="text-red">*</span></label>
                            <select name="input_types" class="form-select form-input-types  google-input">
                                <option value="" disabled selected>-- {{ __('Select input type') }}--</option>
                                <option value="multi">Multi Attribute</option>
                                <option value="text">Text</option>
                                <option value="textarea">Text Area</option>
                                <option value="texteditor">Text Editor</option>
                                <option value="text">Letters (a-z, A-Z) or Numbers (0-9)</option>
                                <option value="email">Email</option>
                                <option value="tel">Telepon</option>
                                <option value="password">Password</option>
                                <option value="url">Url</option>
                                {{-- <option value="search">Search</option> --}}
                                <option value="image">Image (all format: png,jpg,,,etc)</option>
                                <option value="file">File</option>
                                {{-- <option value="number">Number</option> --}}
                                <option value="number">Integer Number(1,,2,3...etc)</option>
                                <option value="decimal">Decimal Number(1,2,3.123....etc)</option>
                                <option value="range">Range</option>
                                {{-- <option value="radio">Radio ( True, False )</option> --}}
                                <option value="switch">Yes Or No</option>
                                <option value="date">Date</option>
                                <option value="month">Months Of The Year</option>
                                <option value="time">Time</option>
                                <option value="datalist">Datalist ( Year List )</option>
                                <option value="datetime-local">Date And Time</option>
                                <option value="select">Select</option>
                                <option value="radioselect">Radio</option>
                                {{-- <option value="foreignId">Normal Lookup</option> --}}
                                <option value="fk">Lookup</option>
                                {{-- <option value="informatic">Informatic Attribute</option> --}}
                                {{-- <option value="doublefk">Double Lookup Attribute</option> --}}
                                <option value="doubleattr">Double Attribute</option>
                                {{-- <option value="condition">Condition Lookup</option> --}}

                                {{-- <option value="assign">Assign</option> --}}
                            </select>
                            <span id="input_types-attribute-create-error"
                                class="error text-danger d-none error-message"></span>
                            <div class="input-options"></div>

                            {{-- <div class="multi">
                                <div class="attr_header row flex justify-content-end my-5 align-items-end pr-5">
                                    <input title="Reset form" class="btn btn-success" id="add_new" type="button"
                                        value="+ Add Another">
                                </div>

                                <div class="multi-item">
                                    <input type="text" name="name" value="Size" class="google-input" />
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="attr_header row flex justify-content-end my-5 align-items-end pr-5">
                                                <input title="Reset form" class="btn btn-success" id="add_new" type="button"
                                                    value="+ Add Value">
                                            </div>
                                            <ul>
                                                <li><input type="text" name="name" value="S" class="google-input" />
                                                </li>
                                                <li><input type="text" name="name" value="M" class="google-input" />
                                                </li>
                                                <li><input type="text" name="name" value="L" class="google-input" />
                                                </li>
                                                <li><input type="text" name="name" value="XL" class="google-input" />
                                                </li>
                                                <li><input type="text" name="name" value="XXL" class="google-input" />
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-4">
                                            <div class="attr_header row flex justify-content-end my-5 align-items-end pr-5">
                                                <input title="Reset form" class="btn btn-success" id="add_new" type="button"
                                                    value="+ Add Extra">
                                            </div>
                                            <ul>
                                                <li><input type="text" name="name" value="QTY" class="google-input" /></li>
                                                <li><input type="text" name="name" value="Price" class="google-input" /></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </div> --}}

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="card source-card">
        <div class="card-header">
            <h3 class="card-title">Source</h3>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="col-sm-12 input-box">
                    <label class="form-label" for="source">source<span class="text-red">*</span></label>
                    <select class="google-input " name="source" id="source">
                    </select>
                    <span id="source-attribute-create-error" class="error text-danger d-none error-message"></span>
                </div>

                {{-- <div class="col-sm-6 input-box">
                    <label class="form-label" for="target">Target<span class="text-red">*</span></label>
                    <select class="google-input " name="target"  id="target">

                    </select>
                </div> --}}

            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Admin</h3>
        </div>
        <div class="card-body pb-2">
            <div class="row">
                <div class="form-group col-sm-4">
                    <label class="custom-switch form-label">
                        <input type="checkbox" name="is_enable" class="custom-switch-input" id="is_enable"
                            {{ $attribute->is_enable == 1 ? 'checked' : 'checked' }}>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Status</span>
                    </label>
                </div>
                <div class="form-group col-sm-8">
                    <label class="custom-switch form-label">
                        <input type="checkbox" name="is_system" class="custom-switch-input" id="is_system"
                            {{ $attribute->is_system == 1 ? 'checked' : '' }}>
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description">Global</span>
                    </label>
                </div>
                <div class="col-md-12">
                    <div class="row align-items-center justify-content-center">
                        <div class="form-group col-md-4">
                            <label class="custom-switch form-label">
                                <input type="checkbox" name="requireds" class="custom-switch-input switch-requireds"
                                    id="requireds" checked>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">Required</span>
                            </label>
                        </div>
                        <div class="custom-values col-md-8"></div>

                    </div>
                </div>
                <input type="hidden" name="default_values" class="form-default-value"
                    placeholder="{{ __('Default Value (optional)') }}">

                <div class="col-md-6">
                    <div class="input-box">
                        <input type="number" name="min_lengths" class=" google-input form-control form-min-lengths"
                            min="1" placeholder="Min">
                        <span id="min_lengths-attribute-create-error"
                            class="error text-danger d-none error-message"></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="input-box">
                        <input type="number" name="max_lengths" class="  google-input form-control form-max-lengths"
                            min="1" placeholder="Max">
                        <span id="max_lengths-attribute-create-error"
                            class="error text-danger d-none error-message"></span>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- <div class="card-footer text-right">
        <input title="Save attribute" class="btn btn-primary" type="submit"
            value="{{ $attribute->id == null ? 'Create' : 'Update' }}">
        <input title="Reset form" class="btn btn-warning" type="reset" value="Reset">
        <a title="Cancel form" href="{{ route('attribute.index') }}" class="btn btn-danger">Cancel</a>
    </div> --}}

    <div class="card-footer text-right">
        <input title="Save attribute" class="btn btn-primary" type="submit"
            value="{{ $attribute->id == null ? 'Create' : 'Update' }}">
        <input title="Reset form" class="btn btn-warning" type="reset" value="Reset">
        <a title="Cancel form" href="{{ route('attribute.index') }}" class="btn btn-danger">Cancel</a>
    </div>

</form>

<!-- End Row -->
