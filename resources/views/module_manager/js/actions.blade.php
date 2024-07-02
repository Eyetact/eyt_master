<script>
    let selectMenu = $('#select-menu')
    let colNewMenu = $('#col-new-menu')

    $('#add_new').click(function() {
        let table = $('#tbl-field tbody')

        let list = getColumnTypes()
        let no = table.find('tr').length + 1
        
        let tr = `
            <tr draggable="true" containment="tbody" ondragstart="dragStart()" ondragover="dragOver()" style="cursor: move;">
                <td class="text-center">${no}</td>
                <td>
                    <div class="input-box">
                        <input type="text" name="fields[]" class="google-input" placeholder="Field Name" required>
                    </div>
                </td>

                
                <td>
                    <div class="input-box">
                        <select name="column_types[]" class="form-select google-input form-column-types" required>
                            <option value="" disabled selected>--Select column type--</option>
                            ${list}
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
                                <input type="number" name="min_lengths[]" class="google-input form-min-lengths" min="1"
                                    placeholder="Min Length">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-box">
                                <input type="number" name="max_lengths[]" class="google-input form-max-lengths" min="1"
                                    placeholder="Max Length">
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="input-box">
                        <select name="input_types[]" class="google-input form-input-types" required>
                            <option value="" disabled selected>-- Select input type --</option>
                            <option value="" disabled>Select the column type first</option>
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
                                <input type="checkbox" name="requireds[]" class="custom-switch-input switch-requireds"
                                    id="switch-${no}" checked>
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
                                                <input type="checkbox" name="is_enable" class="custom-switch-input" id="is_enable[]" >
                                                <span class="custom-switch-indicator"></span>
                                                <span class="custom-switch-description">Status</span>
                                            </label>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label class="custom-switch form-label">
                                                <input type="checkbox" name="is_system" class="custom-switch-input" id="is_system[]" >
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
            `

        table.append(tr)
    })

    $(document).on('change', '.form-column-types', function() {
        let index = $(this).parent().parent().parent().index()
        let switchRequired = $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .switch-requireds`)

        switchRequired.prop('checked', true)
        switchRequired.prop('disabled', false)

        $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .form-default-value`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(5)`).append(`
            <div class="form-group form-default-value ">
                <input type="hidden" name="default_values[]">
            </div>
        `)

        if ($(this).val() == 'enum') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
            <div class="input-box form-option mt-2">
                <input type="text" name="select_options[]" class="google-input" placeholder="Seperate with '|', e.g.: water|fire">
            </div>
            `)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="select">Select</option>
                <option value="radio">Radio</option>
                <option value="datalist">Datalist</option>
            `)
        } else if ($(this).val() == 'date') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="date">Date</option>
                <option value="month">Month</option>
            `)
        } else if ($(this).val() == 'time') {
            checkMinAndMaxLength(index)
            removeAllInputHidden(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="time">Time</option>
            `)

            // $(`.form-min-lengths:eq(${index})`).prop('readonly', true)
            // $(`.form-max-lengths:eq(${index})`).prop('readonly', true)
            // $(`.form-min-lengths:eq(${index})`).val('')
            // $(`.form-max-lengths:eq(${index})`).val('')
        } else if ($(this).val() == 'year') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="select">Select</option>
                <option value="datalist">Datalist</option>
            `)
        } else if ($(this).val() == 'dateTime') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="datetime-local">Datetime local</option>
            `)
        } else if ($(this).val() == 'foreignId') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2) .form-option`).remove()

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                <input type="hidden" name="select_options[]" class="form-option">
            `)

            $(`#tbl-field tbody tr:eq(${index}) td:eq(2)`).append(`
                <div class="input-box form-constrain mt-2">
                    <input type="text" name="constrains[]" class="google-input" placeholder="Constrain or related model name" required>
                    <small class="text-secondary">
                        <ul class="my-1 mx-2 p-0">
                            <li>Use '/' if related model at sub folder, e.g.: Main/Product.</li>
                            <li>Field name must be related model + "_id", e.g.: user_id</li>
                        </ul>
                    </small>
                </div>
                <div class="input-box form-foreign-id mt-2">
                    <input type="hidden" name="foreign_ids[]" class="google-input" placeholder="Foreign key (optional)">
                </div>
                <div class="input-box form-on-update mt-2 form-on-update-foreign">
                    <select class="google-input" name="on_update_foreign[]" required>
                        <option value="" disabled selected>-- Select action on update --</option>
                        <option value="0">Nothing</option>
                        <option value="1">Cascade</option>
                        <option value="2">Restrict</option>
                    </select>
                </div>
                <div class="input-box form-on-delete mt-2 form-on-delete-foreign">
                    <select class="google-input" name="on_delete_foreign[]" required>
                        <option value="" disabled selected>-- Select action on delete --</option>
                        <option value="0">Nothing</option>
                        <option value="1">Cascade</option>
                        <option value="2">Restrict</option>
                        <option value="3">Null</option>
                    </select>
                </div>
            `)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="select">Select</option>
                <option value="datalist">Datalist</option>
            `)
        } else if (
            $(this).val() == 'text' ||
            $(this).val() == 'longText' ||
            $(this).val() == 'mediumText' ||
            $(this).val() == 'tinyText' ||
            $(this).val() == 'string'
        ) {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="text">Text</option>
                <option value="textarea">Textarea</option>
                <option value="email">Email</option>
                <option value="tel">Telepon</option>
                <option value="password">Password</option>
                <option value="url">Url</option>
                <option value="search">Search</option>
                <option value="file">File</option>
                <option value="hidden">Hidden</option>
                <option value="no-input">No Input</option>
            `)
        } else if (
            $(this).val() == 'integer' ||
            $(this).val() == 'mediumInteger' ||
            $(this).val() == 'bigInteger' ||
            $(this).val() == 'decimal' ||
            $(this).val() == 'double' ||
            $(this).val() == 'float' ||
            $(this).val() == 'tinyInteger'
        ) {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="number">Number</option>
                <option value="range">Range</option>
                <option value="hidden">Hidden</option>
                <option value="no-input">No Input</option>
            `)
        } else if ($(this).val() == 'boolean') {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="select">Select</option>
                <option value="radio">Radio</option>
                <option value="datalist">Datalist</option>
            `)
        } else {
            removeAllInputHidden(index)
            checkMinAndMaxLength(index)
            addColumTypeHidden(index)

            $(`.form-input-types:eq(${index})`).html(`
                <option value="" disabled selected>-- Select input type --</option>
                <option value="text">Text</option>
                <option value="email">Email</option>
                <option value="tel">Telepon</option>
                <option value="url">Url</option>
                <option value="week">Week</option>
                <option value="color">Color</option>
                <option value="search">Search</option>
                <option value="file">File</option>
                <option value="hidden">Hidden</option>
                <option value="no-input">No Input</option>
            `)
        }
    })

    $(document).on('change', '.switch-requireds', function() {
        let index = $(this).parent().parent().parent().index()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .form-default-value`).remove()

        let inputTypeDefaultValue = setInputTypeDefaultValue(index)

        if ($(this).is(':checked')) {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(5)`).append(`
                <div class="form-group form-default-value ">
                    <input type="hidden" name="default_values[]">
                </div>
            `)
        } else {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(5)`).append(`
                <div class="input-box form-default-value ">
                    <input type="${inputTypeDefaultValue}" name="default_values[]" class="form-control" placeholder="Default Value (optional)">
                </div>
            `)
        }
    })

    $(document).on('change', '.form-input-types', function() {
        let index = $(this).parent().parent().parent().index()
        let minLength = $(`.form-min-lengths:eq(${index})`)
        let maxLength = $(`.form-max-lengths:eq(${index})`)
        let switchRequired = $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .switch-requireds`)

        removeInputTypeHidden(index)
        switchRequired.prop('checked', true)
        switchRequired.prop('disabled', false)

        $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .form-default-value`).remove()
        $(`#tbl-field tbody tr:eq(${index}) td:eq(5)`).append(`
            <div class="form-group form-default-value ">
                <input type="hidden" name="default_values[]">
            </div>
        `)

        if ($(this).val() == 'file') {
            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')

            $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
            <div class="input-box mt-2 form-file-types">
                <select name="file_types[]" class="google-input" required>
                    <option value="" disabled selected>-- Select file type --</option>
                    <option value="image">Image</option>
                </select>
            </div>
            <div class="input-box form-file-sizes">
                <input type="number" name="files_sizes[]" class="google-input" placeholder="Max size(kb), e.g.: 1024" required>
            </div>
            <input type="hidden" name="mimes[]" class="form-mimes">
            <input type="hidden" name="steps[]" class="form-step">
            `)
        } else if (
            $(this).val() == 'email' ||
            $(this).val() == 'select' ||
            $(this).val() == 'datalist' ||
            $(this).val() == 'radio' ||
            $(this).val() == 'date' ||
            $(this).val() == 'month' ||
            $(this).val() == 'password' ||
            $(this).val() == 'number'
        ) {
            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')

            addInputTypeHidden(index)
        } else if ($(this).val() == 'text' || $(this).val() == 'tel') {
            minLength.prop('readonly', false)
            maxLength.prop('readonly', false)

            addInputTypeHidden(index)
        } else if ($(this).val() == 'range') {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
                <div class="form-group form-step mt-4">
                    <input type="number" name="steps[]" class="form-control" placeholder="Step (optional)">
                </div>
                <input type="hidden" name="file_types[]" class="form-file-types">
                <input type="hidden" name="files_sizes[]" class="form-file-sizes">
                <input type="hidden" name="mimes[]" class="form-mimes">
            `)

            minLength.prop('readonly', false)
            maxLength.prop('readonly', false)
            minLength.prop('required', true)
            maxLength.prop('required', true)

            // addInputTypeHidden(index)
        } else if ($(this).val() == 'hidden' || $(this).val() == 'no-input') {
            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')

            let inputTypeDefaultValue = setInputTypeDefaultValue(index)

            $(`#tbl-field tbody tr:eq(${index}) td:eq(5) .form-default-value`).remove()

            $(`#tbl-field tbody tr:eq(${index}) td:eq(5)`).append(`
                <div class="input-box form-default-value ">
                    <input type="${inputTypeDefaultValue}" name="default_values[]" class="form-control" placeholder="Default Value (optional)">
                </div>
            `)

            switchRequired.prop('checked', false)
            switchRequired.prop('disabled', true)
            addInputTypeHidden(index)
        } else if (
            $(this).val() == 'time' ||
            $(this).val() == 'week' ||
            $(this).val() == 'color' ||
            $(this).val() == 'datetime-local'
        ) {
            minLength.prop('readonly', true)
            maxLength.prop('readonly', true)
            minLength.val('')
            maxLength.val('')
            addInputTypeHidden(index)
        } else {
            addInputTypeHidden(index)
            minLength.prop('readonly', false)
            maxLength.prop('readonly', false)
        }
    })

    $(document).on('change', '.file-types', function() {
        let index = $(this).parent().parent().parent().index()

        $(`#tbl-field tbody tr:eq(${index}) td:eq(4) .form-mimes`).remove()

        if ($(this).val() == 'mimes') {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(`
            <div class="form-group mt-2 form-mimes">
                <input type="text" name="mimes[]" class="form-control" placeholder="File type, seperate with ','. eg: pdf,docx" required>
            </div>
            `)
        } else {
            $(`#tbl-field tbody tr:eq(${index}) td:eq(4)`).append(
                `<input type="hidden" name="mimes[]" class="form-mimes">`
            )
        }
    })

    $(document).on('change', 'input[type="checkbox"]', function() {
        let index = $(this).parent().parent().parent().index()

        if ($(this).val() == 'yes') {
            $(`#required-${index + 1}`).prop('checked', true)
            $(`#nullable-${index + 1}`).prop('checked', false)
        } else if ($(this).val() == 'no') {
            $(`#nullable-${index + 1}`).prop('checked', true)
            $(`#required-${index + 1}`).prop('checked', false)
        }
    })

    $(document).on('click', '.btn-delete', function() {
        let table = $('#tbl-field tbody tr')

        $(this).parent().parent().remove()
        generateNo()
    })
</script>
