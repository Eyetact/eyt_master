<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use App\Models\Attribute;
use App\Models\Module;

class FormViewGenerator
{
    /**
     * Generate a form/input for create and edit.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['name']);
        $path = GeneratorUtils::getModelLocation($request['name']);

        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $code = GeneratorUtils::setModelName($request['code']);



        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);

        $template = "<div class=\"row mb-2\">\n";
        $template .= "@php\n";

        $template .= "\$model = \App\Models\Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase('" . $code . "'))\n";
        $template .= "->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase('" . $code . "'))\n";
        $template .= "->first();\n";
        $template .= "\$constrain_name = App\Generators\GeneratorUtils::singularSnakeCase('" . $code . "');\n";
        $template .= "if (\$model) {\n";
        $template .= "\$for_attr = json_encode(\$model->fields()->select('code','attribute')->where('type', 'foreignId')->orWhere('primary', 'lookup')
        ->orWhere('type', 'fk')->get());\n";
        $template .= "\$for_attr = str_replace('\"', \"'\", \$for_attr);\n";
        $template .= "}\n";
        $template .= "@endphp\n";

        // $template = "<div class=\"row mb-2\">\n";
        $template .= "@if(\$model->is_system && auth()->user()->hasAnyRole(['vendor', 'admin'])  && !isset($$modelNameSingularCamelCase) )\n";
        $template .= "<div class=\"form-group col-sm-8\">\n";
        $template .= "<label class=\"custom-switch form-label\">\n";
        $template .= "<input type=\"hidden\" name=\"global\" value=\"0\"> <!-- Hidden input as default value -->\n";
        $template .= "<input type=\"checkbox\" name=\"global\" value=\"1\" class=\"custom-switch-input\" id=\"global-1\"\n";
        $template .= "{{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->global == '1' ? 'checked' : '' }}";
        $template .= ">\n";
        $template .= "<span class=\"custom-switch-indicator\"></span>\n";
        $template .= "<span class=\"custom-switch-description\">Add to global data</span>\n";
        $template .= "</label>\n";
        $template .= "</div>\n";
        $template .= "@endif\n";


        $template .= "@if(\$model->is_system && auth()->user()->hasRole('super') && isset($$modelNameSingularCamelCase) )\n";
        $template .= "<div class=\"col-md-12\">\n";
        $template .= "<div class=\"input-box\">\n";
        $template .= "<label for=\"status\">{{ __('Status') }}</label>\n";

        $template .= "<select data-constrain=\"{{ \$constrain_name }}\" data-source=\"Disable\" data-attrs={!! isset(\$for_attr) ? \$for_attr : '' !!} class=\"google-input @error('status') is-invalid @enderror\" name=\"status\" id=\"status\" class=\"form-control\">\n";
        $template .= "<option value=\"\" selected disabled>-- {{ __('Select status') }} --</option>\n";
        $template .= "<option value=\"active\" {{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->status == 'active' ? 'selected' : ('inactive' == 'active' ? 'selected' : '') }}>active</option>\n";
        $template .= "<option value=\"inactive\" {{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->status == 'inactive' ? 'selected' : ('inactive' == 'inactive' ? 'selected' : '') }}>inactive</option>\n";
        $template .= "<option value=\"pending\" {{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->status == 'pending' ? 'selected' : ('inactive' == 'pending' ? 'selected' : '') }}>pending</option>\n";
        $template .= "</select>\n";
        $template .= "@error('status')\n";
        $template .= "<span class=\"text-danger\">\n";
        $template .= "{{ \$message }}\n";
        $template .= "</span>\n";
        $template .= "@enderror\n";
        $template .= "</div>\n";
        $template .= "</div>\n";
        $template .= "@endif\n";



        if (!empty($request['fields'][0])) {
            foreach ($request['fields'] as $i => $field) {

                if ($request['input_types'][$i] !== 'no-input') {
                    $fieldSnakeCase = str($field)->snake();

                    $fieldUcWords = GeneratorUtils::cleanUcWords($field);

                    switch ($request['column_types'][$i]) {
                        case 'enum':
                            $options = "";

                            $arrOption = explode('|', $request['select_options'][$i]);

                            $totalOptions = count($arrOption);

                            switch ($request['input_types'][$i]) {
                                case 'select':
                                    // select
                                    foreach ($arrOption as $arrOptionIndex => $value) {
                                        $options .= <<<BLADE
                                    <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : (old('$fieldSnakeCase') == '$value' ? 'selected' : '') }}>$value</option>
                                    BLADE;

                                        if ($arrOptionIndex + 1 != $totalOptions) {
                                            $options .= "\n\t\t";
                                        } else {
                                            $options .= "\t\t\t";
                                        }
                                    }

                                    $template .= str_replace(
                                        [
                                            '{{fieldUcWords}}',
                                            '{{fieldKebabCase}}',
                                            '{{fieldSnakeCase}}',
                                            '{{fieldSpaceLowercase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                            '{{source}}'
                                        ],
                                        [
                                            $fieldUcWords,
                                            GeneratorUtils::kebabCase($field),
                                            $fieldSnakeCase,
                                            GeneratorUtils::cleanLowerCase($field),
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            $request['source']
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/select')
                                    );
                                    break;
                                case 'datalist':
                                    foreach ($arrOption as $arrOptionIndex => $value) {
                                        $options .= "<option value=\"" . $value . "\">$value</option>";

                                        if ($arrOptionIndex + 1 != $totalOptions) {
                                            $options .= "\n\t\t";
                                        } else {
                                            $options .= "\t\t\t";
                                        }
                                    }

                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldCamelCase}}',
                                            '{{fieldUcWords}}',
                                            '{{fieldSnakeCase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                            '{{value}}',
                                        ],
                                        [
                                            GeneratorUtils::kebabCase($field),
                                            GeneratorUtils::singularCamelCase($field),
                                            $fieldUcWords,
                                            $fieldSnakeCase,
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : old('" . $fieldSnakeCase . "') }}",
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/datalist')
                                    );
                                    break;
                                default:
                                    // radio
                                    $options .= "\t<div class=\"col-md-6\">\n\t<p>$fieldUcWords</p>\n";

                                    foreach ($arrOption as $value) {
                                        $options .= str_replace(
                                            [
                                                '{{fieldSnakeCase}}',
                                                '{{optionKebabCase}}',
                                                '{{value}}',
                                                '{{optionLowerCase}}',
                                                '{{checked}}',
                                                '{{nullable}}',
                                            ],
                                            [
                                                $fieldSnakeCase,
                                                GeneratorUtils::singularKebabCase($value),
                                                $value,
                                                GeneratorUtils::cleanSingularLowerCase($value),
                                                "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field == '$value' ? 'checked' : (old('$field') == '$value' ? 'checked' : '') }}",
                                                $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            ],
                                            GeneratorUtils::getTemplate('views/forms/radio')
                                        );
                                    }

                                    $options .= "\t</div>\n";

                                    $template .= $options;
                                    break;
                            }
                            break;
                        case 'foreignId':
                            // remove '/' or sub folders
                            $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i], 'default');

                            $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                            $columnAfterId = GeneratorUtils::getColumnAfterId($constrainModel);



                            $options = "
                        @foreach ($" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                            <option value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                            </option>
                        @endforeach";

                            switch ($request['input_types'][$i]) {
                                case 'datalist':
                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldSnakeCase}}',
                                            '{{fieldUcWords}}',
                                            '{{fieldCamelCase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                            '{{value}}',
                                        ],
                                        [
                                            GeneratorUtils::KebabCase($field),
                                            $fieldSnakeCase,
                                            GeneratorUtils::cleanSingularUcWords($constrainModel),
                                            GeneratorUtils::singularCamelCase($field),
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : old('" . $fieldSnakeCase . "') }}",
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/datalist')
                                    );
                                    break;
                                default:
                                    // select
                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldUcWords}}',
                                            '{{fieldSpaceLowercase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                            '{{fieldSnakeCase}}',
                                            '{{source}}'
                                        ],
                                        [
                                            str_replace('-', '_', GeneratorUtils::singularKebabCase($field)),
                                            GeneratorUtils::cleanSingularUcWords($constrainModel),
                                            GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            $fieldSnakeCase,
                                            $request['source']
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/select')
                                    );
                                    break;
                            }
                            break;
                        case 'year':
                            $firstYear = is_int(config('generator.format.first_year')) ? config('generator.format.first_year') : 1900;

                            /**
                             * Will generate something like:
                             *
                             * <select class="form-select" name="year" id="year" class="form-control" required>
                             * <option value="" selected disabled>-- {{ __('Select year') }} --</option>
                             *  @foreach (range(1900, strftime('%Y', time())) as $year)
                             *     <option value="{{ $year }}"
                             *        {{ isset($book) && $book->year == $year ? 'selected' : (old('year') == $year ? 'selected' : '') }}>
                             *      {{ $year }}
                             * </option>
                             *  @endforeach
                             * </select>
                             */
                            $options = "
                        @foreach (range($firstYear, strftime(\"%Y\", time())) as \$year)
                            <option value=\"{{ \$year }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == \$year ? 'selected' : (old('$fieldSnakeCase') == \$year ? 'selected' : '') }}>
                                {{ \$year }}
                            </option>
                        @endforeach";

                            switch ($request['input_types'][$i]) {
                                case 'datalist':
                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldCamelCase}}',
                                            '{{fieldUcWords}}',
                                            '{{fieldSnakeCase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                            '{{value}}',
                                        ],
                                        [
                                            GeneratorUtils::singularKebabCase($field),
                                            GeneratorUtils::singularCamelCase($field),
                                            $fieldUcWords,
                                            $fieldSnakeCase,
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : old('" . $fieldSnakeCase . "') }}",
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/datalist')
                                    );
                                    break;
                                default:
                                    $template .= str_replace(
                                        [
                                            '{{fieldUcWords}}',
                                            '{{fieldKebabCase}}',
                                            '{{fieldSnakeCase}}',
                                            '{{fieldSpaceLowercase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                        ],
                                        [
                                            GeneratorUtils::cleanUcWords($field),
                                            GeneratorUtils::kebabCase($field),
                                            $fieldSnakeCase,
                                            GeneratorUtils::cleanLowerCase($field),
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/select')
                                    );
                                    break;
                            }
                            break;
                        case 'boolean':
                            switch ($request['input_types'][$i]) {
                                case 'select':
                                    // select
                                    $options = "<option value=\"0\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'selected' : (old('$fieldSnakeCase') == '0' ? 'selected' : '') }}>{{ __('True') }}</option>\n\t\t\t\t<option value=\"1\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'selected' : (old('$fieldSnakeCase') == '1' ? 'selected' : '') }}>{{ __('False') }}</option>";

                                    $template .= str_replace(
                                        [
                                            '{{fieldUcWords}}',
                                            '{{fieldSnakeCase}}',
                                            '{{fieldKebabCase}}',
                                            '{{fieldSpaceLowercase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                        ],
                                        [
                                            GeneratorUtils::cleanUcWords($field),
                                            $fieldSnakeCase,
                                            GeneratorUtils::kebabCase($field),
                                            GeneratorUtils::cleanLowerCase($field),
                                            $options,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/select')
                                    );
                                    break;

                                default:
                                    // radio
                                    $options = "\t<div class=\"col-md-6\">\n\t<p>$fieldUcWords</p>";

                                    /**
                                     * will generate something like:
                                     *
                                     * <div class="form-check mb-2">
                                     *  <input class="form-check-input" type="radio" name="is_active" id="is_active-1" value="1" {{ isset($product) && $product->is_active == '1' ? 'checked' : (old('is_active') == '1' ? 'checked' : '') }}>
                                     *     <label class="form-check-label" for="is_active-1">True</label>
                                     * </div>
                                     *  <div class="form-check mb-2">
                                     *    <input class="form-check-input" type="radio" name="is_active" id="is_active-0" value="0" {{ isset($product) && $product->is_active == '0' ? 'checked' : (old('is_active') == '0' ? 'checked' : '') }}>
                                     *      <label class="form-check-label" for="is_active-0">False</label>
                                     * </div>
                                     */
                                    $options .= "
                                    <div class=\"form-check mb-2\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"$fieldSnakeCase\" id=\"$fieldSnakeCase-1\" value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : (old('$fieldSnakeCase') == '1' ? 'checked' : '') }}>
                                        <label class=\"form-check-label\" for=\"$fieldSnakeCase-1\">True</label>
                                    </div>
                                    <div class=\"form-check mb-2\">
                                        <input class=\"form-check-input\" type=\"radio\" name=\"$fieldSnakeCase\" id=\"$fieldSnakeCase-0\" value=\"0\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'checked' : (old('$fieldSnakeCase') == '0' ? 'checked' : '') }}>
                                        <label class=\"form-check-label\" for=\"$fieldSnakeCase-0\">False</label>
                                    </div>\n";

                                    $options .= "\t</div>\n";

                                    $template .= $options;
                                    break;
                            }
                            break;

                        default:
                            // input form
                            if ($request['default_values'][$i]) {
                                $formatValue = "{{ (isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase')) ? old('$fieldSnakeCase') : '" . $request['default_values'][$i] . "' }}";
                            } else {
                                $formatValue = "{{ isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase') }}";
                            }

                            switch ($request['input_types'][$i]) {
                                case 'datetime-local':
                                    /**
                                     * Will generate something like:
                                     *
                                     * {{ isset($book) && $book->datetime ? $book->datetime->format('Y-m-d\TH:i') : old('datetime') }}
                                     */
                                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m-d\TH:i') : old('$fieldSnakeCase') }}";

                                    $template .= $this->setInputTypeTemplate(
                                        request: [
                                            'input_types' => $request['input_types'][$i],
                                            'requireds' => $request['requireds'][$i],
                                        ],
                                        model: $model,
                                        field: $field->code,
                                        formatValue: $formatValue,
                                        date: 1
                                    );
                                    break;
                                case 'date':
                                    /**
                                     * Will generate something like:
                                     *
                                     * {{ isset($book) && $book->date ? $book->date->format('Y-m-d') : old('date') }}
                                     */
                                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m-d') : old('$fieldSnakeCase') }}";

                                    $template .= $this->setInputTypeTemplate(
                                        request: [
                                            'input_types' => $request['input_types'][$i],
                                            'requireds' => $request['requireds'][$i],
                                        ],
                                        model: $model,
                                        field: $field->code,
                                        formatValue: $formatValue,
                                        date: 1
                                    );
                                    break;
                                case 'time':
                                    /**
                                     * Will generate something like:
                                     *
                                     * {{ isset($book) ? $book->time->format('H:i') : old('time') }}
                                     */
                                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('H:i') : old('$fieldSnakeCase') }}";

                                    $template .= $this->setInputTypeTemplate(
                                        request: [
                                            'input_types' => $request['input_types'][$i],
                                            'requireds' => $request['requireds'][$i],
                                        ],
                                        model: $model,
                                        field: $field->code,
                                        formatValue: $formatValue,
                                        date: 1
                                    );
                                    break;
                                case 'week':
                                    /**
                                     * Will generate something like:
                                     *
                                     * {{ isset($book) ? $book->week->format('Y-\WW') : old('week') }}
                                     */
                                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-\WW') : old('$fieldSnakeCase') }}";

                                    $template .= $this->setInputTypeTemplate(
                                        request: [
                                            'input_types' => $request['input_types'][$i],
                                            'requireds' => $request['requireds'][$i],
                                        ],
                                        model: $model,
                                        field: $field->code,
                                        formatValue: $formatValue,
                                        date: 1
                                    );
                                    break;
                                case 'month':
                                    /**
                                     * Will generate something like:
                                     *
                                     * {{ isset($book) ? $book->month->format('Y-\WW') : old('month') }}
                                     */
                                    $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m') : old('$fieldSnakeCase') }}";

                                    $template .= $this->setInputTypeTemplate(
                                        request: [
                                            'input_types' => $request['input_types'][$i],
                                            'requireds' => $request['requireds'][$i],
                                        ],
                                        model: $model,
                                        field: $field->code,
                                        formatValue: $formatValue,
                                        date: 1
                                    );
                                    break;
                                case 'textarea':
                                    // textarea
                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldUppercase}}',
                                            '{{modelName}}',
                                            '{{nullable}}',
                                            '{{fieldSnakeCase}}',

                                        ],
                                        [
                                            GeneratorUtils::kebabCase($field),
                                            $fieldUcWords,
                                            $modelNameSingularCamelCase,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            $fieldSnakeCase,
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/textarea')
                                    );
                                    break;
                                case 'texteditor':
                                    // texteditor
                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldUppercase}}',
                                            '{{modelName}}',
                                            '{{nullable}}',
                                            '{{fieldSnakeCase}}',

                                        ],
                                        [
                                            GeneratorUtils::kebabCase($field),
                                            $fieldUcWords,
                                            $modelNameSingularCamelCase,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            $fieldSnakeCase,
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/texteditor')
                                    );
                                    break;
                                case 'file':

                                    $template .= str_replace(
                                        [
                                            '{{modelCamelCase}}',
                                            '{{fieldPluralSnakeCase}}',
                                            '{{fieldSnakeCase}}',
                                            '{{fieldLowercase}}',
                                            '{{fieldUcWords}}',
                                            '{{nullable}}',
                                            '{{uploadPathPublic}}',
                                            '{{fieldKebabCase}}',
                                            '{{defaultImage}}',
                                            '{{defaultImageCodeForm}}',
                                        ],
                                        [
                                            $modelNameSingularCamelCase,
                                            GeneratorUtils::pluralSnakeCase($field),
                                            str()->snake($field),
                                            GeneratorUtils::cleanSingularLowerCase($field),
                                            $fieldUcWords,
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            config('generator.image.path') == 'storage' ? "storage/uploads" : "uploads",
                                            str()->kebab($field),
                                            "",
                                            "",
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/image')
                                    );
                                    break;
                                case 'range':
                                    $template .= str_replace(
                                        [
                                            '{{fieldSnakeCase}}',
                                            '{{fieldUcWords}}',
                                            '{{fieldKebabCase}}',
                                            '{{nullable}}',
                                            '{{min}}',
                                            '{{max}}',
                                            '{{step}}',
                                        ],
                                        [
                                            GeneratorUtils::singularSnakeCase($field),
                                            $fieldUcWords,
                                            GeneratorUtils::singularKebabCase($field),
                                            $request['requireds'][$i] == 'yes' ? ' required' : '',
                                            $request['min_lengths'][$i],
                                            $request['max_lengths'][$i],
                                            $request['steps'][$i] ? 'step="' . $request['steps'][$i] . '"' : '',
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/range')
                                    );
                                    break;
                                case 'hidden':
                                    $template .= '<input type="hidden" name="' . $fieldSnakeCase . '" value="' . $request['default_values'][$i] . '">';
                                    break;
                                case 'password':
                                    $template .= str_replace(
                                        [
                                            '{{fieldUcWords}}',
                                            '{{fieldSnakeCase}}',
                                            '{{fieldKebabCase}}',
                                            '{{model}}',
                                        ],
                                        [
                                            $fieldUcWords,
                                            $fieldSnakeCase,
                                            GeneratorUtils::singularKebabCase($field),
                                            $modelNameSingularCamelCase,
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/input-password')
                                    );
                                    break;
                                default:
                                    $template .= $this->setInputTypeTemplate(
                                        request: [
                                            'input_types' => $request['input_types'][$i],
                                            'requireds' => $request['requireds'][$i],
                                        ],
                                        model: $model,
                                        field: $field->code,
                                        formatValue: $formatValue

                                    );
                                    break;
                            }
                            break;
                    }
                }
            }
        }

        $template .= "</div>";

        // create a blade file
        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase/include"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/form.blade.php"), $template);
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/dropdown.blade.php"), '');
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/multi.blade.php"), '');
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase/include");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/form.blade.php", $template);
                file_put_contents($fullPath . "/dropdown.blade.php", '');
                file_put_contents($fullPath . "/multi.blade.php", '');
                break;
        }
    }

    public function reGenerate($id)
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->name);
        $path = GeneratorUtils::getModelLocation($module->name);
        $code = GeneratorUtils::setModelName($module->code);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);

        $template = "<div class=\"row mb-2\">\n";

        // $template = "<div class=\"row mb-2\">\n";
        $template .= "@php\n";

        $template .= "\$model = \App\Models\Module::where('code', App\Generators\GeneratorUtils::singularSnakeCase('" . $code . "'))\n";
        $template .= "->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase('" . $code . "'))\n";
        $template .= "->first();\n";
        $template .= "\$constrain_name = App\Generators\GeneratorUtils::singularSnakeCase('" . $code . "');\n";
        $template .= "if (\$model) {\n";
        $template .= "\$for_attr = json_encode(\$model->fields()->select('code','attribute')->where('type', 'foreignId')->orWhere('primary', 'lookup')
        ->orWhere('type', 'fk')->get());\n";
        $template .= "\$for_attr = str_replace('\"', \"'\", \$for_attr);\n";
        $template .= "}\n";
        $template .= "@endphp\n";


        $template .= "@if(\$model->is_system && auth()->user()->hasAnyRole(['vendor', 'admin']) && !isset($$modelNameSingularCamelCase) )\n";
        $template .= "<div class=\"form-group col-sm-8\">\n";
        $template .= "<label class=\"custom-switch form-label\">\n";
        $template .= "<input type=\"hidden\" name=\"global\" value=\"0\"> <!-- Hidden input as default value -->\n";
        $template .= "<input type=\"checkbox\" name=\"global\" value=\"1\" class=\"custom-switch-input\" id=\"global-1\"\n";
        $template .= "{{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->global == '1' ? 'checked' : '' }}";
        $template .= ">\n";
        $template .= "<span class=\"custom-switch-indicator\"></span>\n";
        $template .= "<span class=\"custom-switch-description\">Add to global data</span>\n";
        $template .= "</label>\n";
        $template .= "</div>\n";
        $template .= "@endif\n";


        $template .= "@if(\$model->is_system && auth()->user()->hasRole('super') && isset($$modelNameSingularCamelCase) )\n";
        $template .= "<div class=\"col-md-12\">\n";
        $template .= "<div class=\"input-box\">\n";
        $template .= "<label for=\"status\">{{ __('Status') }}</label>\n";




        $template .= "<select data-constrain=\"{{ \$constrain_name }}\" data-source=\"Disable\" data-attrs={!! isset(\$for_attr) ? \$for_attr : '' !!} class=\"google-input @error('status') is-invalid @enderror\" name=\"status\" id=\"status\" class=\"form-control\">\n";
        $template .= "<option value=\"\" selected disabled>-- {{ __('Select status') }} --</option>\n";
        $template .= "<option value=\"active\" {{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->status == 'active' ? 'selected' :  '' }}>active</option>\n";
        $template .= "<option value=\"inactive\" {{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->status == 'inactive' ? 'selected'  : '' }}>inactive</option>\n";
        $template .= "<option value=\"pending\" {{ isset($$modelNameSingularCamelCase) && $$modelNameSingularCamelCase" . "->status == 'pending' ? 'selected'  : '' }}>pending</option>\n";
        $template .= "</select>\n";
        $template .= "@error('status')\n";
        $template .= "<span class=\"text-danger\">\n";
        $template .= "{{ \$message }}\n";
        $template .= "</span>\n";
        $template .= "@enderror\n";
        $template .= "</div>\n";
        $template .= "</div>\n";
        $template .= "@endif\n";


        foreach ($module->fields()->where('is_enable', 1)->get() as $i => $field) {
            $field->name = GeneratorUtils::singularSnakeCase($field->name);
            $field->code = !empty($field->code) ? GeneratorUtils::singularSnakeCase($field->code) : GeneratorUtils::singularSnakeCase($field->name);

            if ($field->input !== 'no-input') {
                $fieldSnakeCase = str($field->code)->snake();
                $fieldUcWords = GeneratorUtils::cleanUcWords($field->name);

                switch ($field->type) {
                    case 'assign':
                        $template .= '
                        <div class="col-sm-12 col-md-12">
                            <div class="input-box">
                                <label class="form-label">Customer Group<span class="text-danger">*</span></label>
                                <select class="google-input" name="customer_group_id" tabindex="null">
                                    <option selected disabled>Select Group</option>
                                    @foreach ($customer_groups as $group)
                                        <option {{ isset($' . $modelNameSingularCamelCase . ') && $' . $modelNameSingularCamelCase . '->customer_group_id == $group->id ? \'selected\' : \'\' }} value="{{ $group->id }}">{{$group->id}} - {{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <span>OR</span>
                            <div class="input-box">

                                <label class="form-label">Customer<span class="text-danger">*</span></label>
                                <select class="google-input" name="customer_id" tabindex="null">
                                    <option selected disabled>Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option {{ isset($' . $modelNameSingularCamelCase . ') && $' . $modelNameSingularCamelCase . '->customer_id == $customer->id ? \'selected\' : \'\' }} value="{{ $customer->id }}">{{$customer->id}} - {{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        ';
                        break;
                    case 'enum':
                        $options = "";

                        $arrOption = explode('|', $field->select_option);

                        $totalOptions = count($arrOption);

                        switch ($field->input) {
                            case 'select':
                                // select
                                foreach ($arrOption as $arrOptionIndex => $value) {


                                    $multiple = '';
                                    if ($field->is_multi) {
                                        $multiple = "multiple";
                                        if ($field->default_value) {
                                            $options .= <<<BLADE
                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && (is_array( json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) ?in_array('$value', json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) : \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value') ? 'selected' :'' }}>$value</option>
                                        BLADE;
                                        } else {
                                            $options .= <<<BLADE
                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && (is_array( json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) ?in_array('$value', json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) : \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value')  ? 'selected' :'' }}>$value</option>
                                        BLADE;
                                        }
                                    } else {
                                        if ($field->default_value) {
                                            $options .= <<<BLADE
                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : ('$field->default_value' == '$value' ? 'selected' : '') }}>$value</option>
                                        BLADE;
                                        } else {
                                            $options .= <<<BLADE
                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : ('$field->default_value' == '$value' ? 'selected' : '') }}>$value</option>
                                        BLADE;
                                        }
                                    }


                                    if ($arrOptionIndex + 1 != $totalOptions) {
                                        $options .= "\n\t\t";
                                    } else {
                                        $options .= "\t\t\t";
                                    }
                                }

                                $template .= str_replace(
                                    [
                                        '{{fieldUcWords}}',
                                        '{{fieldKebabCase}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}'
                                    ],
                                    [
                                        $fieldUcWords,
                                        GeneratorUtils::kebabCase($field->name),
                                        $field->is_multi ? $fieldSnakeCase . "[]" : $fieldSnakeCase,
                                        GeneratorUtils::cleanLowerCase($field->name),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $multiple,
                                        $field->source,
                                        ''
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select')
                                );
                                break;
                            case 'datalist':
                                foreach ($arrOption as $arrOptionIndex => $value) {
                                    $options .= "<option value=\"" . $value . "\">$value</option>";

                                    if ($arrOptionIndex + 1 != $totalOptions) {
                                        $options .= "\n\t\t";
                                    } else {
                                        $options .= "\t\t\t";
                                    }
                                }

                                $d = '';
                                if (isset($field->default_value)) {
                                    $d = $field->default_value;
                                }

                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldCamelCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSnakeCase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{value}}',
                                    ],
                                    [
                                        GeneratorUtils::kebabCase($field->name),
                                        GeneratorUtils::singularCamelCase($field->name),
                                        $fieldUcWords,
                                        $fieldSnakeCase,
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : $d }}",
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/datalist')
                                );
                                break;
                            default:
                                // radio

                                $d = '';
                                if (isset($field->default_value)) {
                                    $d = $field->default_value;
                                }

                                $options .= "\t<div class=\"col-md-12\">\n\t<p>$fieldUcWords</p>\n";


                                // foreach ($arrOption as $value) {
                                //     $options .= str_replace(
                                //         [
                                //             '{{fieldSnakeCase}}',
                                //             '{{optionKebabCase}}',
                                //             '{{value}}',
                                //             '{{optionLowerCase}}',
                                //             '{{checked}}',
                                //             '{{nullable}}',
                                //         ],
                                //         [
                                //             $fieldSnakeCase,
                                //             GeneratorUtils::singularKebabCase($value),
                                //             $value,
                                //             GeneratorUtils::cleanSingularLowerCase($value),

                                //             "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field->name == '$value' ? 'checked' : ('$d' == '$value' ? 'checked' : '') }}",
                                //             $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                //         ],
                                //         GeneratorUtils::getTemplate('views/forms/radio')
                                //     );
                                // }

                                foreach ($arrOption as $value) {
                                    $options .= str_replace(
                                        [
                                            '{{fieldSnakeCase}}',
                                            '{{optionKebabCase}}',
                                            '{{value}}',
                                            '{{optionLowerCase}}',
                                            '{{checked}}',
                                            '{{checked2}}',
                                            '{{nullable}}',
                                            '{{mnscc}}'
                                        ],
                                        [
                                            $fieldSnakeCase,
                                            GeneratorUtils::singularKebabCase($value),
                                            $value,
                                            GeneratorUtils::cleanSingularLowerCase($value),

                                            "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field->name == '$value' ? 'checked' : ('$d' == '$value' ? 'checked' : '') }}",

                                            "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$field->name == '$value' ? 'checked' : ' ' }}",

                                            $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                            $modelNameSingularCamelCase,
                                        ],
                                        GeneratorUtils::getTemplate('views/forms/radio2')
                                    );
                                }

                                $options .= "\t</div>\n";

                                $template .= $options;
                                break;
                        }
                        break;
                    case 'foreignId':

                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                        $columnAfterId = $field->attribute;
                        $dataIds = '';

                        if ($field->source != null) {

                            $current_model = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                ->orWhere('code', $field->constrain)->first();

                            $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                            foreach ($lookatrrs as $sa) {
                                $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }
                        }
                        if ($field->multiple > 0) {
                            $options = "
                                        @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                            <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                            </option>
                                        @endforeach";
                        } else {

                            $options = "
                                                @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                    <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                        {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                    </option>
                                                @endforeach";

                        }

                        switch ($field->input) {
                            case 'datalist':
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldCamelCase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{value}}',
                                    ],
                                    [
                                        GeneratorUtils::KebabCase($field->name),
                                        $fieldSnakeCase,
                                        GeneratorUtils::cleanSingularUcWords($constrainModel),
                                        GeneratorUtils::singularCamelCase($field->name),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : old('" . $fieldSnakeCase . "') }}",
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/datalist')
                                );
                                break;
                            default:
                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}'
                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->source)[0],
                                        ($field->multiple > 0) ? '[]' : '',


                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select')
                                );
                                break;
                        }
                        break;


                        case 'fk':


                            if($field->fk_type == 'basic')
                            {

                            // remove '/' or sub folders
                            $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                            $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                            $columnAfterId = $field->attribute;
                            $dataIds = '';

                            if ($field->source != null) {

                                $current_model = Module::where(
                                    'code',
                                    GeneratorUtils::singularSnakeCase($field->constrain)
                                )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                    ->orWhere('code', $field->constrain)->first();

                                $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                                foreach ($lookatrrs as $sa) {
                                    $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                    // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                                }
                            }
                            if ($field->multiple > 0) {
                                $options = "
                                            @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                    {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                </option>
                                            @endforeach";
                            } else {

                                $options = "
                                                    @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                        <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                            {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                        </option>
                                                    @endforeach";

                            }


                                    // select
                                    $template .= str_replace(
                                        [
                                            '{{fieldKebabCase}}',
                                            '{{fieldUcWords}}',
                                            '{{fieldSpaceLowercase}}',
                                            '{{options}}',
                                            '{{nullable}}',
                                            '{{fieldSnakeCase}}',
                                            '{{multiple}}',
                                            '{{source}}',
                                            '{{multiple2}}'
                                        ],
                                        [

                                            explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                            GeneratorUtils::cleanSingularUcWords($field->name),
                                            GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                            $options,
                                            $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                            $fieldSnakeCase,
                                            ($field->multiple > 0) ? 'multiple' : '',
                                            explode('_', $field->source)[0],
                                            ($field->multiple > 0) ? '[]' : '',


                                        ],
                                        GeneratorUtils::getTemplate('views/forms/select')
                                    );
                                }


                                if($field->fk_type == 'condition')
                                {

                                         // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                        $columnAfterId = $field->attribute;
                        $dataIds = '';

                        $values = explode("|", $field->condition_value);
                        $values_count = count($values);
                        $i = 0;
                        $q = GeneratorUtils::setModelName($constrainModel);
                        foreach ($values as $value) {
                            if ($i < $values_count) {
                                if ($i == 0) {
                                    $q .= "::where('$field->condition_attr', '$value')";
                                } else {
                                    $q .= "->orWhere('$field->condition_attr', '$value')";

                                }
                            }
                            $i++;
                        }

                        if ($field->source != null) {

                            $current_model = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                ->orWhere('code', $field->constrain)->first();

                            $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                            foreach ($lookatrrs as $sa) {
                                $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }
                        }
                        if ($field->multiple > 0) {
                            $options = "
                                    @foreach (\App\Models\Admin\\" . $q . "->get() as $$constrainSingularCamelCase)
                                        <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                            {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                        </option>
                                    @endforeach";
                        } else {

                            $options = "
                                    @foreach (\App\Models\Admin\\" . $q . "->get() as $$constrainSingularCamelCase)
                                                <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                    {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                </option>
                                            @endforeach";

                        }


                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}'
                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->source)[0],
                                        ($field->multiple > 0) ? '[]' : '',


                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select')
                                );


                                }

                                if($field->fk_type == 'based'){



                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');
                        $constrainModel2 = GeneratorUtils::setModelName($field->constrain2, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);
                        $constrainSingularCamelCase2 = GeneratorUtils::singularCamelCase($constrainModel2);

                        $columnAfterId = $field->attribute;
                        $columnAfterId2 = $field->attribute2;

                        $dataIds = '';
                        $dataIds2 = '';

                        $options2 = '';

                        $fieldSnakeCase2 = str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id');
                        $code2 = $field->constrain2 . '_' . $field->attribute2 . '_id';




                       $values = explode("|", $field->condition_value);
                        $values_count = count($values);
                        $i = 0;
                        $q = GeneratorUtils::setModelName($constrainModel);
                        foreach ($values as $value) {
                            if ($i < $values_count) {
                                if ($i == 0) {
                                    $q .= "::where('$field->condition_attr', '$value')";
                                } else {
                                    $q .= "->orWhere('$field->condition_attr', '$value')";

                                }
                            }
                            $i++;
                        }





                        if (true) {

                            $current_model = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                ->orWhere('code', $field->constrain)->first();

                            $current_model2 = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain2)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain2))
                                ->orWhere('code', $field->constrain2)->first();

                            $lookatrrs = Attribute::where("module", $current_model->id)
                            ->where(function ($query) {
                                $query->where('type', 'foreignId')
                                      ->orWhere('type', 'fk')
                                      ->orWhere('primary', 'lookup');
                            })->get();
                            $lookatrrs2 = Attribute::where("module", $current_model2->id)
                            ->where(function ($query) {
                                $query->where('type', 'foreignId')
                                      ->orWhere('type', 'fk')
                                      ->orWhere('primary', 'lookup');
                            })->get();

                            $conditionId = Attribute::where("module", $current_model2->id)->where('constrain',$field->constrain)
                                                     ->first()->code;

                            $idsData='App\Models\Admin\\' . $q . '->pluck("id")';



                            $q2 = GeneratorUtils::setModelName($constrainModel2);
                            $q2 .= "::whereIn('$conditionId', $idsData)";


                            foreach ($lookatrrs as $sa) {
                                $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }

                            foreach ($lookatrrs2 as $sa) {
                                $dataIds2 .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase2 . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }
                        }
                        if ($field->multiple > 0) {
                            $options = "
                                            @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                    {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                </option>
                                            @endforeach";
                        } else {

                            $options = "
                                                    @foreach (\App\Models\Admin\\" . $q . "->get()  as $$constrainSingularCamelCase)
                                                        <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                            {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                        </option>
                                                    @endforeach";

                            $options2 = "
                                                    @foreach (\App\Models\Admin\\" . $q2 . "->get()    as $$constrainSingularCamelCase2)
                                                        <option    $dataIds2  value=\"{{ $" . $constrainSingularCamelCase2 . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase2 == $" . $constrainSingularCamelCase2 . "->id ? 'selected' : (old('$fieldSnakeCase2') == $" . $constrainSingularCamelCase2 . "->id ? 'selected' : '') }}>
                                                            {{ $" . $constrainSingularCamelCase2 . "->$columnAfterId2 }}
                                                        </option>
                                                    @endforeach";

                        }


                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}',


                                        '{{fieldKebabCase2}}',
                                        '{{fieldUcWords2}}',
                                        '{{fieldSpaceLowercase2}}',
                                        '{{options2}}',
                                        '{{nullable2}}',
                                        '{{fieldSnakeCase2}}',
                                        '{{multiple2}}',
                                        '{{source2}}',
                                        '{{multiple2}}'
                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        '',
                                        ($field->multiple > 0) ? '[]' : '',



                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($code2)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel2),
                                        $options2,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase2,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->constrain)[0],
                                        ($field->multiple > 0) ? '[]' : '',


                                    ],
                                    GeneratorUtils::getTemplate('views/forms/based')
                                );

                                }

                            break;


                    case 'doublefk':

                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');
                        $constrainModel2 = GeneratorUtils::setModelName($field->constrain2, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);
                        $constrainSingularCamelCase2 = GeneratorUtils::singularCamelCase($constrainModel2);

                        $columnAfterId = $field->attribute;
                        $columnAfterId2 = $field->attribute2;

                        $dataIds = '';
                        $dataIds2 = '';

                        $options2 = '';

                        $fieldSnakeCase2 = str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id');
                        $code2 = $field->constrain2 . '_' . $field->attribute2 . '_id';

                        if (true) {

                            $current_model = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                ->orWhere('code', $field->constrain)->first();

                            $current_model2 = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain2)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain2))
                                ->orWhere('code', $field->constrain2)->first();

                            $lookatrrs = Attribute::where("module", $current_model->id)
                            ->where(function ($query) {
                                $query->where('type', 'foreignId')
                                      ->orWhere('type', 'fk')
                                      ->orWhere('primary', 'lookup');
                            })->get();
                            $lookatrrs2 = Attribute::where("module", $current_model2->id)
                            ->where(function ($query) {
                                $query->where('type', 'foreignId')
                                      ->orWhere('type', 'fk')
                                      ->orWhere('primary', 'lookup');
                            })->get();


                            foreach ($lookatrrs as $sa) {
                                $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }

                            foreach ($lookatrrs2 as $sa) {
                                $dataIds2 .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase2 . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }
                        }
                        if ($field->multiple > 0) {
                            $options = "
                                            @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                    {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                </option>
                                            @endforeach";
                        } else {

                            $options = "
                                                    @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                        <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                            {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                        </option>
                                                    @endforeach";

                            $options2 = "
                                                    @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel2) . " as $$constrainSingularCamelCase2)
                                                        <option    $dataIds2  value=\"{{ $" . $constrainSingularCamelCase2 . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase2 == $" . $constrainSingularCamelCase2 . "->id ? 'selected' : (old('$fieldSnakeCase2') == $" . $constrainSingularCamelCase2 . "->id ? 'selected' : '') }}>
                                                            {{ $" . $constrainSingularCamelCase2 . "->$columnAfterId2 }}
                                                        </option>
                                                    @endforeach";

                        }

                        switch ($field->input) {

                            default:
                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}',


                                        '{{fieldKebabCase2}}',
                                        '{{fieldUcWords2}}',
                                        '{{fieldSpaceLowercase2}}',
                                        '{{options2}}',
                                        '{{nullable2}}',
                                        '{{fieldSnakeCase2}}',
                                        '{{multiple2}}',
                                        '{{source2}}',
                                        '{{multiple2}}'
                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        '',
                                        ($field->multiple > 0) ? '[]' : '',



                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($code2)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel2),
                                        $options2,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase2,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->constrain)[0],
                                        ($field->multiple > 0) ? '[]' : '',


                                    ],
                                    GeneratorUtils::getTemplate('views/forms/doublefk')
                                );
                                break;
                        }
                        break;



                    case 'informatic':

                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                        $columnAfterId = $field->attribute;
                        $dataIds = '';

                        // if ($field->source != null) {

                        //     $current_model = Module::where(
                        //         'code',
                        //         GeneratorUtils::singularSnakeCase($field->constrain)
                        //     )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                        //         ->orWhere('code', $field->constrain)->first();

                        //     $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                        //     foreach ($lookatrrs as $sa) {
                        //         $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                        //         // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                        //     }
                        // }
                        if ($field->multiple > 0) {
                            $options = "
                                        @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                            <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                            </option>
                                        @endforeach";
                        } else {

                            $options = "
                                                @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                    <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                        {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                    </option>
                                                @endforeach";

                        }

                        switch ($field->input) {

                            default:
                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}',

                                        '{{source2}}',
                                        '{{fieldKebabCase2}}',
                                        '{{fieldUcWords2}}',
                                        '{{fieldSnakeCase2}}'

                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->source)[0],
                                        ($field->multiple > 0) ? '[]' : '',

                                        $constrainModel,
                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->constrain2)))[0],
                                        GeneratorUtils::cleanSingularUcWords($field->constrain2),
                                        GeneratorUtils::cleanSingularLowerCase($field->constrain2),



                                    ],
                                    GeneratorUtils::getTemplate('views/forms/inform')
                                );
                                break;
                        }
                        break;


                    case 'condition':

                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                        $columnAfterId = $field->attribute;
                        $dataIds = '';

                        $values = explode("|", $field->condition_value);
                        $values_count = count($values);
                        $i = 0;
                        $q = GeneratorUtils::setModelName($constrainModel);
                        foreach ($values as $value) {
                            if ($i < $values_count) {
                                if ($i == 0) {
                                    $q .= "::where('$field->condition_attr', '$value')";
                                } else {
                                    $q .= "->orWhere('$field->condition_attr', '$value')";

                                }
                            }
                            $i++;
                        }

                        if ($field->source != null) {

                            $current_model = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                ->orWhere('code', $field->constrain)->first();

                            $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                            foreach ($lookatrrs as $sa) {
                                $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }
                        }
                        if ($field->multiple > 0) {
                            $options = "
                                    @foreach (\App\Models\Admin\\" . $q . "->get() as $$constrainSingularCamelCase)
                                        <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                            {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                        </option>
                                    @endforeach";
                        } else {

                            $options = "
                                    @foreach (\App\Models\Admin\\" . $q . "->get() as $$constrainSingularCamelCase)
                                                <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                    {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                </option>
                                            @endforeach";

                        }

                        switch ($field->input) {
                            case 'datalist':
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldCamelCase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{value}}',
                                    ],
                                    [
                                        GeneratorUtils::KebabCase($field->name),
                                        $fieldSnakeCase,
                                        GeneratorUtils::cleanSingularUcWords($constrainModel),
                                        GeneratorUtils::singularCamelCase($field->name),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : old('" . $fieldSnakeCase . "') }}",
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/datalist')
                                );
                                break;
                            default:
                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}'
                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->source)[0],
                                        ($field->multiple > 0) ? '[]' : '',


                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select')
                                );
                                break;
                        }
                        break;
                    case 'year':
                        $firstYear = is_int(config('generator.format.first_year')) ? config('generator.format.first_year') : 1900;

                        /**
                         * Will generate something like:
                         *
                         * <select class="form-select" name="year" id="year" class="form-control" required>
                         * <option value="" selected disabled>-- {{ __('Select year') }} --</option>
                         *  @foreach (range(1900, strftime('%Y', time())) as $year)
                         *     <option value="{{ $year }}"
                         *        {{ isset($book) && $book->year == $year ? 'selected' : (old('year') == $year ? 'selected' : '') }}>
                         *      {{ $year }}
                         * </option>
                         *  @endforeach
                         * </select>
                         */
                        $options = "
                        @foreach (range($firstYear, strftime(\"%Y\", time())) as \$year)
                            <option value=\"{{ \$year }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == \$year ? 'selected' : (old('$fieldSnakeCase') == \$year ? 'selected' : '') }}>
                                {{ \$year }}
                            </option>
                        @endforeach";

                        switch ($field->input) {
                            case 'datalist':
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldCamelCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSnakeCase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{value}}',
                                    ],
                                    [
                                        str_replace('-', '_', GeneratorUtils::singularKebabCase($field->name)),
                                        GeneratorUtils::singularCamelCase($field->name),
                                        $fieldUcWords,
                                        $fieldSnakeCase,
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        "{{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " : old('" . $fieldSnakeCase . "') }}",
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/datalist')
                                );
                                break;
                            default:
                                $template .= str_replace(
                                    [
                                        '{{fieldUcWords}}',
                                        '{{fieldKebabCase}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                    ],
                                    [
                                        GeneratorUtils::cleanUcWords($field->name),
                                        str_replace('-', '_', GeneratorUtils::kebabCase($field->name)),

                                        $fieldSnakeCase,
                                        GeneratorUtils::cleanLowerCase($field->name),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select')
                                );
                                break;
                        }
                        break;
                    case 'boolean':
                        switch ($field->input) {
                            case 'select':
                                // select
                                if (isset($field->default_value)) {
                                    $options = "<option value=\"0\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'selected' : ($field->default_value == 0 ? 'selected' : '') }}>{{ __('False') }}</option>\n\t\t\t\t
                                                    <option value=\"1\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'selected' : ($field->default_value == 1 ? 'selected' : '') }}>{{ __('True') }}</option>";
                                } else {
                                    $options = "<option value=\"0\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'selected' : (old('$fieldSnakeCase') == '0' ? 'selected' : '') }}>{{ __('False') }}</option>\n\t\t\t\t
                                                    <option value=\"1\" {{ isset($" . $modelNameSingularCamelCase . ") && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'selected' : (old('$fieldSnakeCase') == '1' ? 'selected' : '') }}>{{ __('True') }}</option>";
                                }

                                $template .= str_replace(
                                    [
                                        '{{fieldUcWords}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldKebabCase}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                    ],
                                    [
                                        GeneratorUtils::cleanUcWords($field->name),
                                        $fieldSnakeCase,
                                        GeneratorUtils::kebabCase($field->name),
                                        GeneratorUtils::cleanLowerCase($field->name),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select')
                                );
                                break;

                            case 'switch':
                                $options = "\t<div class=\"col-md-6\">\n\t<p>$fieldUcWords</p>";

                                if (isset($field->default_value)) {
                                    $options .= "
                                            <div class=\"form-group col-sm-4\">
                                                <label class=\"custom-switch form-label\">
                                                @if(!isset($$modelNameSingularCamelCase))
                                                <input type=\"hidden\" name=\"$fieldSnakeCase\" value=\"0\">
                                                    <input type=\"checkbox\" name=\"$fieldSnakeCase\" class=\"custom-switch-input\" id=\"$fieldSnakeCase\"
                                                        value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : ($field->default_value == 1 ? 'checked' : '') }}>
                                                 @else
                                                 <input type=\"hidden\" name=\"$fieldSnakeCase\" value=\"0\">
                                                 <input type=\"checkbox\" name=\"$fieldSnakeCase\" class=\"custom-switch-input\" id=\"$fieldSnakeCase\"
                                                     value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : ' ' }}>
                                                  @endif

                                                    <span class=\"custom-switch-indicator\"></span>
                                                    <span class=\"custom-switch-description\">$fieldUcWords</span>
                                                </label>
                                            </div>\n";
                                }

                                // if (isset($field->default_value)) {
                                //     $options .= "
                                //         <div class=\"form-group col-sm-4\">
                                //             <label class=\"custom-switch form-label\">
                                //                 <input type=\"checkbox\" name=\"$fieldSnakeCase\" class=\"custom-switch-input\" id=\"$fieldSnakeCase\"
                                //                     value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : ($field->default_value == 1 ? 'checked' : '') }}>
                                //                     <input type=\"hidden\" name=\"$fieldSnakeCase\" value=\"0\">
                                //                 <span class=\"custom-switch-indicator\"></span>
                                //                 <span class=\"custom-switch-description\">$fieldUcWords</span>
                                //             </label>
                                //         </div>\n";
                                // } else {
                                //     $options .= "
                                //         <div class=\"form-group col-sm-4\">
                                //             <label class=\"custom-switch form-label\">
                                //                 <input type=\"checkbox\" name=\"$fieldSnakeCase\" class=\"custom-switch-input\" id=\"$fieldSnakeCase\"
                                //                     value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : '' }}>
                                //                 <span class=\"custom-switch-indicator\"></span>
                                //                 <span class=\"custom-switch-description\">$fieldUcWords</span>
                                //             </label>
                                //         </div>\n";
                                // }

                                $options .= "\t</div>\n";

                                $template .= $options;
                                break;

                            default:
                                // radio
                                $options = "\t<div class=\"col-md-6\">\n\t<p>$fieldUcWords</p>";

                                /**
                                 * will generate something like:
                                 *
                                 * <div class="form-check mb-2">
                                 *  <input class="form-check-input" type="radio" name="is_active" id="is_active-1" value="1" {{ isset($product) && $product->is_active == '1' ? 'checked' : (old('is_active') == '1' ? 'checked' : '') }}>
                                 *     <label class="form-check-label" for="is_active-1">True</label>
                                 * </div>
                                 *  <div class="form-check mb-2">
                                 *    <input class="form-check-input" type="radio" name="is_active" id="is_active-0" value="0" {{ isset($product) && $product->is_active == '0' ? 'checked' : (old('is_active') == '0' ? 'checked' : '') }}>
                                 *      <label class="form-check-label" for="is_active-0">False</label>
                                 * </div>
                                 */

                                if (isset($field->default_value)) {
                                    $options .= "
                                        <div class=\"custom-controls-stacked\">
                                            <label class=\"custom-control custom-radio\" for=\"$fieldSnakeCase-1\">
                                            <input class=\"custom-control-input\" type=\"radio\" name=\"$fieldSnakeCase\" id=\"$fieldSnakeCase-1\" value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : ($field->default_value == 1 ? 'checked' : '') }}>
                                            <span class=\"custom-control-label\">True</span></label>

                                        <label class=\"custom-control custom-radio\" for=\"$fieldSnakeCase-0\">
                                            <input class=\"custom-control-input\" type=\"radio\" name=\"$fieldSnakeCase\" id=\"$fieldSnakeCase-0\" value=\"0\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'checked' : ($field->default_value == 0 ? 'checked' : '') }}>
                                            <span class=\"custom-control-label\">False</span></label>
                                        </div>\n";
                                } else {
                                    $options .= "
                                        <div class=\"custom-controls-stacked\">
                                            <label class=\"custom-control custom-radio\" for=\"$fieldSnakeCase-1\">
                                            <input class=\"custom-control-input\" type=\"radio\" name=\"$fieldSnakeCase\" id=\"$fieldSnakeCase-1\" value=\"1\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '1' ? 'checked' : '' }}>
                                            <span class=\"custom-control-label\">True</span></label>

                                        <label class=\"custom-control custom-radio\" for=\"$fieldSnakeCase-0\">
                                            <input class=\"custom-control-input\" type=\"radio\" name=\"$fieldSnakeCase\" id=\"$fieldSnakeCase-0\" value=\"0\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == '0' ? 'checked' : '' }}>
                                            <span class=\"custom-control-label\">False</span></label>
                                        </div>\n";

                                }


                                $options .= "\t</div>\n";

                                $template .= $options;
                                break;
                        }
                        break;

                    case 'multi':



                        $template .= '<div class="multi-options col-12">
                        <div class="attr_header row flex justify-content-end my-5 align-items-end">
                            <input title="Reset form" class="btn btn-success" id="add_new_tr_' . $field->id . '" type="button" value="+ Add">
                        </div>';

                        $template .= '
                        @if(isset($' . $modelNameSingularCamelCase . ')  && $' . $modelNameSingularCamelCase . '->' . $fieldSnakeCase . '!= null )
                        @php

                        $ar = json_decode($' . $modelNameSingularCamelCase . '->' . $fieldSnakeCase . ');
                        $index = 0;
                        @endphp
                        @endif

                        <input type="hidden"  name="' . $field->name . '" />

                        <table class="table table-bordered align-items-center mb-0" id="tbl-field-' . $field->id . '">
                        <thead>';

                        foreach ($field->multis as $key => $value) {
                            $template .= '<th>' . $value->name . '</th>';
                        }

                        $template .= '
                        <th></th>
                        </thead>
                        <tbody>
                        @if(isset($' . $modelNameSingularCamelCase . ')  && $' . $modelNameSingularCamelCase . '->' . $fieldSnakeCase . '!= null )

                        @foreach( $ar as $item )
                        @php
                            $index++;
                        @endphp
                        ';
                        // foreach ($field->multi as $key => $value) {
                        $template .= '<tr draggable="true" containment="tbody" ondragstart="dragStart()" ondragover="dragOver()" style="cursor: move;">';
                        foreach ($field->multis as $key => $value) {
                            switch ($value->type) {

                                case 'text':
                                case 'email':
                                case 'tel':
                                case 'url':
                                case 'search':
                                case 'file':
                                case 'number':
                                case 'date':
                                case 'time':
                                    $template .= ' <td>
                                        <div class="input-box">
                                            <input type="' . $value->type . '" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                class="form-control google-input"
                                                placeholder="' . $value->code . '" value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>
                                        </div>
                                    </td>
                                    ';
                                    break;
                                    case 'doubleMulti':



                                        if($value->primary == 'text')
                                        {
                                            if($value->secondary == 'prefix'){


                                                $template .= ' <td>
                                                <label class="input-label" > ' . $value->code . ' </label>
                                                <div class="input-group mb-3">
                                                     <span class="input-group-text"
                                                        id="inputGroup-sizing-default">
                                                  ' .  $value->fixed_value . '
                                                    </span>
                                                    <input type="text" class="form-control" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"  value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>

                                                         </div>
                                            </td>
                                            ';

                                            }

                                            else{

                                                $template .= ' <td>
                                                <label class="input-label" > ' . $value->code . ' </label>
                                                <div class="input-group mb-3">

                                                    <input type="text" class="form-control" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"  value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>

                                                        <span class="input-group-text"
                                                        id="inputGroup-sizing-default">
                                                  ' .  $value->fixed_value . '
                                                    </span>

                                                         </div>
                                            </td>
                                            ';



                                            }
                                        }

                                        if($value->primary == 'integer')
                                        {
                                            if($value->secondary == 'prefix'){


                                                $template .= ' <td>
                                                <label class="input-label" > ' . $value->code . ' </label>
                                                <div class="input-group mb-3">
                                                     <span class="input-group-text"
                                                        id="inputGroup-sizing-default">
                                                  ' .  $value->fixed_value . '
                                                    </span>
                                                    <input type="number" class="form-control" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"  value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>

                                                         </div>
                                            </td>
                                            ';

                                            }

                                            else{

                                                $template .= ' <td>
                                                <label class="input-label" > ' . $value->code . ' </label>
                                                <div class="input-group mb-3">

                                                    <input type="number" class="form-control" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"  value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>

                                                        <span class="input-group-text"
                                                        id="inputGroup-sizing-default">
                                                  ' .  $value->fixed_value . '
                                                    </span>

                                                         </div>
                                            </td>
                                            ';



                                            }
                                        }

                                        if($value->primary == 'decimal')
                                        {
                                            if($value->secondary == 'prefix'){


                                                $template .= ' <td>
                                                <label class="input-label" > ' . $value->code . ' </label>
                                                <div class="input-group mb-3">
                                                     <span class="input-group-text"
                                                        id="inputGroup-sizing-default">
                                                  ' .  $value->fixed_value . '
                                                    </span>
                                                    <input type="number" step="0.000000000000000001" class="form-control" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"  value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>

                                                         </div>
                                            </td>
                                            ';

                                            }

                                            else{

                                                $template .= ' <td>
                                                <label class="input-label" > ' . $value->code . ' </label>
                                                <div class="input-group mb-3">

                                                    <input type="number" step="0.000000000000000001" class="form-control" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                        aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default"  value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>

                                                        <span class="input-group-text"
                                                        id="inputGroup-sizing-default">
                                                  ' .  $value->fixed_value . '
                                                    </span>

                                                         </div>
                                            </td>
                                            ';



                                            }
                                        }


                                        if($value->primary == 'select')
                                        {
                                            if($value->secondary == 'prefix'){


                                            $arrOption = explode('|', $value->select_options);

                                            $totalOptions = count($arrOption);
                                            $template .= '<td><div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <label class="input-group-text" for="inputGroupSelect01"> ' .  $value->fixed_value . '</label>
                                            </div>';
                                            $template .= ' <select name="' . $field->code . '[{{ $index }}][' . $value->code . ']" class="custom-select multi-type" required="">';

                                            foreach ($arrOption as $arrOptionIndex => $value2) {
                                                $template .= '<option @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "' . $value2 . '" : 0 ) value="' . $value2 . '" >' . $value2 . '</option>';

                                            }
                                            $template .= '</select>';
                                            $template .= '</div></td>';

                                            }

                                            else{


                                                $arrOption = explode('|', $value->select_options);

                                                $totalOptions = count($arrOption);
                                                $template .= '<td><div class="input-group mb-3">';
                                                $template .= ' <select name="' . $field->code . '[{{ $index }}][' . $value->code . ']" class="custom-select multi-type" required="">';

                                                foreach ($arrOption as $arrOptionIndex => $value2) {
                                                    $template .= '<option @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "' . $value2 . '" : 0 ) value="' . $value2 . '" >' . $value2 . '</option>';

                                                }
                                                $template .= '</select><div class="input-group-prepend">
                                                <label class="input-group-text" for="inputGroupSelect01"> ' .  $value->fixed_value . '</label>
                                              </div>';
                                                $template .= '</div></td>';



                                            }
                                        }


                                        if ($value->primary == 'lookup')
                                        {

                                            $constrainModel = GeneratorUtils::setModelName($value->constrain, 'default');


                                            if ($value->secondary == 'prefix') {



                                                $class = "select-base";
                                                if ($value->condition != 'based') {
                                                    $class = 'select-cond';
                                                }

                                                $dataIds = '';
                                                if ($value->condition == "based") {

                                                    $current_model = Module::where(
                                                        'code',
                                                        GeneratorUtils::singularSnakeCase($value->constrain)
                                                    )->orWhere('code', GeneratorUtils::pluralSnakeCase($value->constrain))
                                                        ->orWhere('code', $value->constrain)->first();
                                                    $lookatrrs = Attribute::where("module", $current_model->id)
                                                    ->where(function ($query) {
                                                        $query->where('type', 'foreignId')
                                                              ->orWhere('type', 'fk')
                                                              ->orWhere('primary', 'lookup');
                                                    })->get();

                                                    foreach ($lookatrrs as $sa) {
                                                        $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "={{ \$item2->" . $sa->code . "}}";

                                                    }
                                                }

                                                $arrOption = explode('|', $value->select_options);

                                                $totalOptions = count($arrOption);
                                                $template .= '<td><div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                  <label class="input-group-text" for="inputGroupSelect01"> ' .  $value->fixed_value . '</label>
                                                </div>';
                                                $template .= '<input type="hidden" value="{{ isset($item->id) ? $item->id : \'\' }}"  name="' . $field->code . '[{{ $index }}][id]" />';
                                                $template .= ' <select data-constrain="' . GeneratorUtils::singularSnakeCase($value->constrain) . '" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" class="custom-select ' . $class . '   multi-type" required="">';

                                                $template .= '@foreach( \\App\\Models\\Admin\\' . GeneratorUtils::singularPascalCase($value->constrain) . '::all() as $item2 )';
                                                $template .= '<option ' . $dataIds . ' data-id="{{$item2->id}}" @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "$item2->' . $value->attribute . '" : 0 )  value="{{ $item2->' . $value->attribute . '}}" >{{ $item2->' . $value->attribute . '}}</option>';

                                                $template .= '@endforeach';
                                                $template .= '</select>';
                                                $template .= '</div></td>';

                                            }

                                            if ($value->secondary == 'suffix') {


                                                $class = "select-base";
                                                if ($value->condition != 'based') {
                                                    $class = 'select-cond';
                                                }

                                                $dataIds = '';
                                                if ($value->condition == "based") {

                                                    $current_model = Module::where(
                                                        'code',
                                                        GeneratorUtils::singularSnakeCase($value->constrain)
                                                    )->orWhere('code', GeneratorUtils::pluralSnakeCase($value->constrain))
                                                        ->orWhere('code', $value->constrain)->first();
                                                    $lookatrrs = Attribute::where("module", $current_model->id)
                                                    ->where(function ($query) {
                                                        $query->where('type', 'foreignId')
                                                              ->orWhere('type', 'fk')
                                                              ->orWhere('primary', 'lookup');
                                                    })->get();

                                                    foreach ($lookatrrs as $sa) {
                                                        $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "={{ \$item2->" . $sa->code . "}}";

                                                    }
                                                }

                                                $arrOption = explode('|', $value->select_options);

                                                $totalOptions = count($arrOption);
                                                $template .= '<td><div class="input-group mb-3">';
                                                $template .= '<input type="hidden" value="{{ isset($item->id) ? $item->id : \'\' }}"  name="' . $field->code . '[{{ $index }}][id]" />';
                                                $template .= ' <select data-constrain="' . GeneratorUtils::singularSnakeCase($value->constrain) . '" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" class="custom-select ' . $class . '   multi-type" required="">';

                                                $template .= '@foreach( \\App\\Models\\Admin\\' . GeneratorUtils::singularPascalCase($value->constrain) . '::all() as $item2 )';
                                                $template .= '<option ' . $dataIds . ' data-id="{{$item2->id}}" @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "$item2->' . $value->attribute . '" : 0 )  value="{{ $item2->' . $value->attribute . '}}" >{{ $item2->' . $value->attribute . '}}</option>';

                                                $template .= '@endforeach';
                                                $template .= '</select><div class="input-group-prepend">
                                                <label class="input-group-text" for="inputGroupSelect01"> ' .  $value->fixed_value . '</label>
                                              </div>';
                                                $template .= '</div></td>';

                                            }

                                            if ($value->secondary == 'lookprefix') {





                                                    $current_model = Module::where(
                                                        'code',
                                                        GeneratorUtils::singularSnakeCase($value->constrain)
                                                    )->orWhere('code', GeneratorUtils::pluralSnakeCase($value->constrain))
                                                        ->orWhere('code', $value->constrain)->first();
                                                    $lookatrrs = Attribute::where("module", $current_model->id)
                                                    ->where(function ($query) {
                                                        $query->where('type', 'foreignId')
                                                              ->orWhere('type', 'fk')
                                                              ->orWhere('primary', 'lookup');
                                                    })->get();

                                                    $template .="
                                                    @php
                                                    \$current_model = \App\Models\Module::where(
                                                        'code',
                                                        App\Generators\GeneratorUtils::singularSnakeCase('" . $value->constrain . "')
                                                    )->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase('" . $value->constrain . "')
                                                    )->orWhere('code','" . $value->constrain . "')->first();
                                                    \$for_attr = json_encode( \$current_model->fields()->select('code', 'attribute')->where(function (\$query) {
                                                        \$query->where('type', 'foreignId')
                                                            ->orWhere('type', 'fk')
                                                            ->orWhere('primary', 'lookup');
                                                    })->get());";

                                                    $template .= '$for_attr = str_replace(\'"\', \'\\\'\', $for_attr);
                                                    @endphp
                                                    ';



                                                $arrOption = explode('|', $value->select_options);

                                                $totalOptions = count($arrOption);
                                                $template .= '<td><div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                  <label class="input-group-text"  data-attr2="' . $value->attribute2  .  '" data-source="' . $constrainModel .  '" for="inputGroupSelect01"></label>
                                                </div>';
                                                $template .= '<input type="hidden" value="{{ isset($item->id) ? $item->id : \'\' }}"  name="' . $field->code . '[{{ $index }}][id]" />';
                                                $template .= ' <select data-fixmulti="true" data-constrain="' . GeneratorUtils::singularSnakeCase($value->constrain) . '" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" data-source="' . $value->source .  '" data-attr="{{$for_attr}}" class="custom-select select-cond multi-type fix-multi" required="">';

                                                $template .= '@foreach( \\App\\Models\\Admin\\' . GeneratorUtils::singularPascalCase($value->constrain) . '::all() as $item2 )';
                                                $template .= '<option  data-id="{{$item2->id}}" @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "$item2->' . $value->attribute . '" : 0 )  value="{{ $item2->' . $value->attribute . '}}" >{{ $item2->' . $value->attribute . '}}</option>';

                                                $template .= '@endforeach';
                                                $template .= '</select>';
                                                $template .= '</div></td>';


                                            }

                                            if ($value->secondary == 'looksuffix') {



                                                $current_model = Module::where(
                                                    'code',
                                                    GeneratorUtils::singularSnakeCase($value->constrain)
                                                )->orWhere('code', GeneratorUtils::pluralSnakeCase($value->constrain))
                                                    ->orWhere('code', $value->constrain)->first();
                                                $lookatrrs = Attribute::where("module", $current_model->id)
                                                ->where(function ($query) {
                                                    $query->where('type', 'foreignId')
                                                          ->orWhere('type', 'fk')
                                                          ->orWhere('primary', 'lookup');
                                                })->get();

                                                $template .="
                                                @php
                                                \$current_model = \App\Models\Module::where(
                                                    'code',
                                                    App\Generators\GeneratorUtils::singularSnakeCase('" . $value->constrain . "')
                                                )->orWhere('code', App\Generators\GeneratorUtils::pluralSnakeCase('" . $value->constrain . "')
                                                )->orWhere('code','" . $value->constrain . "')->first();
                                                \$for_attr = json_encode( \$current_model->fields()->select('code', 'attribute')->where(function (\$query) {
                                                    \$query->where('type', 'foreignId')
                                                        ->orWhere('type', 'fk')
                                                        ->orWhere('primary', 'lookup');
                                                })->get());";

                                                $template .= '$for_attr = str_replace(\'"\', \'\\\'\', $for_attr);
                                                @endphp
                                                ';



                                            $arrOption = explode('|', $value->select_options);

                                            $totalOptions = count($arrOption);
                                            $template .= '<td><div class="input-group mb-3">';
                                            $template .= '<input type="hidden" value="{{ isset($item->id) ? $item->id : \'\' }}"  name="' . $field->code . '[{{ $index }}][id]" />';
                                            $template .= ' <select data-fixmulti="true" data-constrain="' . GeneratorUtils::singularSnakeCase($value->constrain) . '" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" data-source="' . $value->source .  '" data-attr="{{$for_attr}}" class="custom-select select-cond multi-type fix-multi" required="">';

                                            $template .= '@foreach( \\App\\Models\\Admin\\' . GeneratorUtils::singularPascalCase($value->constrain) . '::all() as $item2 )';
                                            $template .= '<option  data-id="{{$item2->id}}" @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "$item2->' . $value->attribute . '" : 0 )  value="{{ $item2->' . $value->attribute . '}}" >{{ $item2->' . $value->attribute . '}}</option>';

                                            $template .= '@endforeach';
                                            $template .= '</select>  <div class="input-group-prepend">
                                            <label class="input-group-text"  data-attr2="' . $value->attribute2  .  '" data-source="' . $constrainModel .  '" for="inputGroupSelect01"></label>
                                          </div>';
                                            $template .= '</div></td>';


                                            }

                                        }

                                        break;
                                case 'decimal':
                                    $template .= ' <td>
                                        <div class="input-box">
                                            <input type="number" step="0.000000000000000001"  name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                class="form-control google-input"
                                                placeholder="' . $value->code . '" value="{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}" required>
                                        </div>
                                    </td>
                                    ';
                                    break;
                                case 'image':
                                    $template .= ' <td>
                                            <div class="input-box">
                                                <input type="file" name="' . $field->code . '[{{ $index }}][' . $value->code . ']"
                                                    class="form-control google-input"
                                                    placeholder="' . $value->name . '" required>
                                            </div>
                                        </td>
                                        ';
                                    break;

                                case 'textarea':
                                    $template .= ' <td>
                                            <div class="input-box">

                                            <textarea name="' . $field->code . '[{{ $index }}][' . $value->code . ']"  class="google-input"  placeholder="' . $value->name . '">{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}</textarea>

                                            </div>
                                        </td>
                                        ';
                                    break;
                                case 'texteditor':
                                    $template .= ' <td>
                                                <div class="input-box">

                                                <textarea name="' . $field->code . '[{{ $index }}][' . $value->code . ']"  class="content"  placeholder="' . $value->name . '">{{ isset($item->' . $value->code . ') ? $item->' . $value->code . ' : \'\' }}</textarea>

                                                </div>
                                            </td>
                                            ';
                                    break;




                                case 'range':
                                    $template .= '<td>
                                                    <div class="row">
                                                        <div class="col-md-11">
                                                            <div class="input-box">
                                                                <input onmousemove="' . $value->name . '1.value=value" type="range" name="' . $field->code . '[' . $value->code . ']" class="range " min="1" max="1000" >

                                                            </div>
                                                        </div>
                                                        <div class="col-md-1">  <output id="' . $value->name . '1"></output></div>
                                                    </div>
                                                </td>
                                                    ';
                                    break;
                                case 'radio':
                                    $template .= '<td>
                                    <div class="custom-controls-stacked">
                                    <label class="custom-control custom-radio" for="' . $value->name . '-1">
                                        <input @checked( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == 1 : 0 ) class="custom-control-input" type="radio" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" id="' . $value->name . '-1" value="1">
                                        <span class="custom-control-label">True</span>
                                    </label>

                                    <label class="custom-control custom-radio" for="' . $value->name . '-0">
                                        <input @checked( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == 0 : 0)  class="custom-control-input" type="radio" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" id="' . $value->name . '-0" value="0">
                                        <span class="custom-control-label">False</span>
                                    </label>
                                </div>
                                                </td>
                                                            ';
                                    break;
                                case 'select':

                                    $arrOption = explode('|', $value->select_options);

                                    $totalOptions = count($arrOption);
                                    $template .= '<td><div class="input-box">';
                                    $template .= ' <select name="' . $field->code . '[{{ $index }}][' . $value->code . ']" class="form-select  google-input multi-type" required="">';

                                    foreach ($arrOption as $arrOptionIndex => $value2) {
                                        $template .= '<option @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "' . $value2 . '" : 0 ) value="' . $value2 . '" >' . $value2 . '</option>';

                                    }
                                    $template .= '</select>';
                                    $template .= '</div></td>';
                                    break;
                                case 'foreignId':

                                    $class = "select-base";
                                    if ($value->condition != 'based') {
                                        $class = 'select-cond';
                                    }

                                    $dataIds = '';
                                    if ($value->condition == "based") {

                                        $current_model = Module::where(
                                            'code',
                                            GeneratorUtils::singularSnakeCase($value->constrain)
                                        )->orWhere('code', GeneratorUtils::pluralSnakeCase($value->constrain))
                                            ->orWhere('code', $value->constrain)->first();
                                        $lookatrrs = Attribute::where("module", $current_model->id)
                                        ->where(function ($query) {
                                            $query->where('type', 'foreignId')
                                                  ->orWhere('type', 'fk')
                                                  ->orWhere('primary', 'lookup');
                                        })->get();

                                        foreach ($lookatrrs as $sa) {
                                            $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "={{ \$item2->" . $sa->code . "}}";

                                        }
                                    }

                                    $arrOption = explode('|', $value->select_options);

                                    $totalOptions = count($arrOption);
                                    $template .= '<td><div class="input-box">';
                                    $template .= '<input type="hidden" value="{{ isset($item->id) ? $item->id : \'\' }}"  name="' . $field->code . '[{{ $index }}][id]" />';
                                    $template .= ' <select data-constrain="' . GeneratorUtils::singularSnakeCase($value->constrain) . '" name="' . $field->code . '[{{ $index }}][' . $value->code . ']" class="form-select ' . $class . '  google-input multi-type" required="">';

                                    $template .= '@foreach( \\App\\Models\\Admin\\' . GeneratorUtils::singularPascalCase($value->constrain) . '::all() as $item2 )';
                                    $template .= '<option ' . $dataIds . ' data-id="{{$item2->id}}" @selected( isset($item->' . $value->code . ') ? $item->' . $value->code . ' == "$item2->' . $value->attribute . '" : 0 )  value="{{ $item2->' . $value->attribute . '}}" >{{ $item2->' . $value->attribute . '}}</option>';

                                    $template .= '@endforeach';
                                    $template .= '</select>';
                                    $template .= '</div></td>';
                                    break;

                                default:
                                    # code...
                                    break;
                            }

                        }
                        $template .= '
                            <td>
                                <div class="input-box">

                                    <button type="button"
                                        class="btn btn-outline-danger btn-xs btn-delete">
                                        x
                                    </button>
                                </div>
                            </td>
                            </tr>';
                        // }
                        $template .= '
                        @endforeach
                        @endif
                        </tbody>
                        </table>
                        </div>
                        ';


                        break;

                    default:
                        // input form
                        if ($field->default_value) {
                            $formatValue = "{{ (isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase')) ? old('$fieldSnakeCase') : '" . $field->default_value . "' }}";
                        } else {
                            $formatValue = "{{ isset($$modelNameSingularCamelCase) ? $$modelNameSingularCamelCase->$fieldSnakeCase : old('$fieldSnakeCase') }}";
                        }

                        switch ($field->input) {
                            case 'datetime-local':
                                /**
                                 * Will generate something like:
                                 *
                                 * {{ isset($book) && $book->datetime ? $book->datetime->format('Y-m-d\TH:i') : old('datetime') }}
                                 */
                                $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m-d\TH:i') : old('$fieldSnakeCase') }}";

                                $template .= $this->setInputTypeTemplate(
                                    request: [
                                        'input_types' => $field->input,
                                        'requireds' => $field->required,
                                    ],
                                    model: $model,
                                    field: $field->code,
                                    formatValue: $formatValue,
                                    date: 1,
                                    label: $field->name
                                );
                                break;
                            case 'date':
                                /**
                                 * Will generate something like:
                                 *
                                 * {{ isset($book) && $book->date ? $book->date->format('Y-m-d') : old('date') }}
                                 */
                                $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m-d') : old('$fieldSnakeCase') }}";

                                $template .= $this->setInputTypeTemplate(
                                    request: [
                                        'input_types' => $field->input,
                                        'requireds' => $field->required,
                                    ],
                                    model: $model,
                                    field: $field->code,
                                    formatValue: $formatValue,
                                    date: 1,
                                    label: $field->name
                                );
                                break;
                            case 'time':
                                /**
                                 * Will generate something like:
                                 *
                                 * {{ isset($book) ? $book->time->format('H:i') : old('time') }}
                                 */
                                $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('H:i') : old('$fieldSnakeCase') }}";

                                $template .= $this->setInputTypeTemplate(
                                    request: [
                                        'input_types' => $field->input,
                                        'requireds' => $field->required,
                                    ],
                                    model: $model,
                                    field: $field->code,
                                    formatValue: $formatValue,
                                    date: 1,
                                    label: $field->name
                                );
                                break;
                            case 'week':
                                /**
                                 * Will generate something like:
                                 *
                                 * {{ isset($book) ? $book->week->format('Y-\WW') : old('week') }}
                                 */
                                $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-\WW') : old('$fieldSnakeCase') }}";

                                $template .= $this->setInputTypeTemplate(
                                    request: [
                                        'input_types' => $field->input,
                                        'requireds' => $field->required,
                                    ],
                                    model: $model,
                                    field: $field->code,
                                    formatValue: $formatValue,
                                    date: 1,
                                    label: $field->name
                                );
                                break;
                            case 'month':
                                /**
                                 * Will generate something like:
                                 *
                                 * {{ isset($book) ? $book->month->format('Y-\WW') : old('month') }}
                                 */
                                $formatValue = "{{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('Y-m') : old('$fieldSnakeCase') }}";

                                $template .= $this->setInputTypeTemplate(
                                    request: [
                                        'input_types' => $field->input,
                                        'requireds' => $field->required,
                                    ],
                                    model: $model,
                                    field: $field->code,
                                    formatValue: $formatValue,
                                    date: 1,
                                    label: $field->name
                                );
                                break;
                            case 'textarea':
                                // textarea
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUppercase}}',
                                        '{{modelName}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',

                                    ],
                                    [
                                        GeneratorUtils::kebabCase($field->name),
                                        $fieldUcWords,
                                        $modelNameSingularCamelCase,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/textarea')
                                );
                                break;
                            case 'texteditor':
                                // texteditor
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUppercase}}',
                                        '{{modelName}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',

                                    ],
                                    [
                                        GeneratorUtils::kebabCase($field->name),
                                        $fieldUcWords,
                                        $modelNameSingularCamelCase,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/texteditor')
                                );
                                break;
                            case 'doubleattr':
                                // doubleattr


                                if ($field->primary == 'decimal') {

                                    if ($field->secondary == 'prefix') {


                                        $template .= str_replace(
                                            [
                                                '{{fieldKebabCase}}',
                                                '{{fieldUppercase}}',
                                                '{{modelName}}',
                                                '{{nullable}}',
                                                '{{fieldSnakeCase}}',
                                                '{{value}}',
                                                '{{type}}',
                                                '{{fixedVal}}',
                                            ],
                                            [
                                                GeneratorUtils::kebabCase($field->name),
                                                $fieldUcWords,
                                                $modelNameSingularCamelCase,
                                                $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                $fieldSnakeCase,
                                                $formatValue,
                                                'number',
                                                $field->fixed_value,
                                            ],

                                            GeneratorUtils::getTemplate('views/forms/fixed-input-decimal')
                                        );
                                    } else {


                                        $template .= str_replace(
                                            [
                                                '{{fieldKebabCase}}',
                                                '{{fieldUppercase}}',
                                                '{{modelName}}',
                                                '{{nullable}}',
                                                '{{fieldSnakeCase}}',
                                                '{{value}}',
                                                '{{type}}',
                                                '{{fixedVal}}',
                                            ],
                                            [
                                                GeneratorUtils::kebabCase($field->name),
                                                $fieldUcWords,
                                                $modelNameSingularCamelCase,
                                                $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                $fieldSnakeCase,
                                                $formatValue,
                                                'number',
                                                $field->fixed_value,
                                            ],

                                            GeneratorUtils::getTemplate('views/forms/fixed-input-decimal-suffix')
                                        );
                                    }


                                }

                                if ($field->primary == 'text' || $field->primary == 'integer') {


                                    if ($field->secondary == 'prefix') {
                                        $template .= str_replace(
                                            [
                                                '{{fieldKebabCase}}',
                                                '{{fieldUppercase}}',
                                                '{{modelName}}',
                                                '{{nullable}}',
                                                '{{fieldSnakeCase}}',
                                                '{{value}}',
                                                '{{type}}',

                                                '{{fixedVal}}',
                                            ],
                                            [
                                                GeneratorUtils::kebabCase($field->name),
                                                $fieldUcWords,
                                                $modelNameSingularCamelCase,
                                                $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                $fieldSnakeCase,
                                                $formatValue,
                                                $field->primary == 'integer' ? 'number' : 'text',
                                                $field->fixed_value,
                                            ],
                                            GeneratorUtils::getTemplate('views/forms/fixed-input-text')
                                        );
                                    } else {

                                        $template .= str_replace(
                                            [
                                                '{{fieldKebabCase}}',
                                                '{{fieldUppercase}}',
                                                '{{modelName}}',
                                                '{{nullable}}',
                                                '{{fieldSnakeCase}}',
                                                '{{value}}',
                                                '{{type}}',

                                                '{{fixedVal}}',
                                            ],
                                            [
                                                GeneratorUtils::kebabCase($field->name),
                                                $fieldUcWords,
                                                $modelNameSingularCamelCase,
                                                $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                $fieldSnakeCase,
                                                $formatValue,
                                                $field->primary == 'integer' ? 'number' : 'text',
                                                $field->fixed_value,
                                            ],
                                            GeneratorUtils::getTemplate('views/forms/fixed-input-text-suffix')
                                        );

                                    }
                                }

                                if ($field->primary == 'select') {

                                    if ($field->secondary == 'prefix') {
                                        $options = "";

                                        $arrOption = explode('|', $field->select_option);

                                        $totalOptions = count($arrOption);


                                        // select
                                        foreach ($arrOption as $arrOptionIndex => $value) {


                                            $multiple = '';
                                            if ($field->is_multi) {
                                                $multiple = "multiple";
                                                if ($field->default_value) {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && (is_array( json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) ?in_array('$value', json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) : \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value') ? 'selected' :'' }}>$value</option>
                                                        BLADE;
                                                } else {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && (is_array( json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) ?in_array('$value', json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) : \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value')  ? 'selected' :'' }}>$value</option>
                                                        BLADE;
                                                }
                                            } else {
                                                if ($field->default_value) {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : ( !isset(\$$modelNameSingularCamelCase) && '$field->default_value' == '$value' ? 'selected' : '') }}>$value</option>
                                                        BLADE;
                                                } else {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : ' ' }}>$value</option>
                                                        BLADE;
                                                }
                                            }


                                            if ($arrOptionIndex + 1 != $totalOptions) {
                                                $options .= "\n\t\t";
                                            } else {
                                                $options .= "\t\t\t";
                                            }
                                        }

                                        $template .= str_replace(
                                            [
                                                '{{fieldUcWords}}',
                                                '{{fieldKebabCase}}',
                                                '{{fieldSnakeCase}}',
                                                '{{fieldSpaceLowercase}}',
                                                '{{options}}',
                                                '{{nullable}}',
                                                '{{multiple}}',
                                                '{{source}}',
                                                '{{multiple2}}',
                                                '{{fixedVal}}'
                                            ],
                                            [
                                                $fieldUcWords,
                                                GeneratorUtils::kebabCase($field->name),
                                                $field->is_multi ? $fieldSnakeCase . "[]" : $fieldSnakeCase,
                                                GeneratorUtils::cleanLowerCase($field->name),
                                                $options,
                                                $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                $multiple,
                                                $field->source,
                                                '',
                                                $field->fixed_value,
                                            ],
                                            GeneratorUtils::getTemplate('views/forms/select-prefix')
                                        );

                                    } else {

                                        $options = "";

                                        $arrOption = explode('|', $field->select_option);

                                        $totalOptions = count($arrOption);


                                        // select
                                        foreach ($arrOption as $arrOptionIndex => $value) {


                                            $multiple = '';
                                            if ($field->is_multi) {
                                                $multiple = "multiple";
                                                if ($field->default_value) {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && (is_array( json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) ?in_array('$value', json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) : \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value') ? 'selected' :'' }}>$value</option>
                                                        BLADE;
                                                } else {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && (is_array( json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) ?in_array('$value', json_decode(\$$modelNameSingularCamelCase->$fieldSnakeCase)) : \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value')  ? 'selected' :'' }}>$value</option>
                                                        BLADE;
                                                }
                                            } else {
                                                if ($field->default_value) {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : (!isset(\$$modelNameSingularCamelCase) && '$field->default_value' == '$value' ? 'selected' : '') }}>$value</option>
                                                        BLADE;
                                                } else {
                                                    $options .= <<<BLADE
                                                        <option value="$value" {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase->$fieldSnakeCase == '$value' ? 'selected' : ' ' }}>$value</option>
                                                        BLADE;
                                                }
                                            }


                                            if ($arrOptionIndex + 1 != $totalOptions) {
                                                $options .= "\n\t\t";
                                            } else {
                                                $options .= "\t\t\t";
                                            }
                                        }

                                        $template .= str_replace(
                                            [
                                                '{{fieldUcWords}}',
                                                '{{fieldKebabCase}}',
                                                '{{fieldSnakeCase}}',
                                                '{{fieldSpaceLowercase}}',
                                                '{{options}}',
                                                '{{nullable}}',
                                                '{{multiple}}',
                                                '{{source}}',
                                                '{{multiple2}}',
                                                '{{fixedVal}}'
                                            ],
                                            [
                                                $fieldUcWords,
                                                GeneratorUtils::kebabCase($field->name),
                                                $field->is_multi ? $fieldSnakeCase . "[]" : $fieldSnakeCase,
                                                GeneratorUtils::cleanLowerCase($field->name),
                                                $options,
                                                $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                $multiple,
                                                $field->source,
                                                '',
                                                $field->fixed_value,
                                            ],
                                            GeneratorUtils::getTemplate('views/forms/select-suffix')
                                        );




                                    }
                                }

                                if ($field->primary == 'lookup')
                                {


                                    if ($field->secondary == 'prefix') {

                                      // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                        $columnAfterId = $field->attribute;
                        $dataIds = '';

                        if ($field->source != null) {

                            $current_model = Module::where(
                                'code',
                                GeneratorUtils::singularSnakeCase($field->constrain)
                            )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                ->orWhere('code', $field->constrain)->first();

                            $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                            foreach ($lookatrrs as $sa) {
                                $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                            }
                        }
                        if ($field->multiple > 0) {
                            $options = "
                                        @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                            <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                            </option>
                                        @endforeach";
                        } else {

                            $options = "
                                                @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                    <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                        {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                    </option>
                                                @endforeach";

                        }


                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}',
                                        '{{fixedVal}}'
                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->source)[0],
                                        ($field->multiple > 0) ? '[]' : '',
                                        $field->fixed_value,


                                    ],
                                    GeneratorUtils::getTemplate('views/forms/select-prefix')
                                );

                        }


                        if($field->secondary == 'suffix'){


                                      // remove '/' or sub folders
                                      $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                                      $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                                      $columnAfterId = $field->attribute;
                                      $dataIds = '';

                                      if ($field->source != null) {

                                          $current_model = Module::where(
                                              'code',
                                              GeneratorUtils::singularSnakeCase($field->constrain)
                                          )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                                              ->orWhere('code', $field->constrain)->first();

                                          $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                                          foreach ($lookatrrs as $sa) {
                                              $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                                              // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                                          }
                                      }
                                      if ($field->multiple > 0) {
                                          $options = "
                                                      @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                          <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                              {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                          </option>
                                                      @endforeach";
                                      } else {

                                          $options = "
                                                              @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                                  <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                                      {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                                  </option>
                                                              @endforeach";

                                      }


                                              // select
                                              $template .= str_replace(
                                                  [
                                                      '{{fieldKebabCase}}',
                                                      '{{fieldUcWords}}',
                                                      '{{fieldSpaceLowercase}}',
                                                      '{{options}}',
                                                      '{{nullable}}',
                                                      '{{fieldSnakeCase}}',
                                                      '{{multiple}}',
                                                      '{{source}}',
                                                      '{{multiple2}}',
                                                      '{{fixedVal}}'
                                                  ],
                                                  [

                                                      explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                                      GeneratorUtils::cleanSingularUcWords($field->name),
                                                      GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                                      $options,
                                                      $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                                      $fieldSnakeCase,
                                                      ($field->multiple > 0) ? 'multiple' : '',
                                                      explode('_', $field->source)[0],
                                                      ($field->multiple > 0) ? '[]' : '',
                                                      $field->fixed_value,


                                                  ],
                                                  GeneratorUtils::getTemplate('views/forms/select-suffix')
                                              );

                        }


                        if($field->secondary == 'lookprefix'){

                               // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');


                        $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                        $columnAfterId = $field->attribute;
                        $dataIds = '';

                        // if ($field->source != null) {

                        //     $current_model = Module::where(
                        //         'code',
                        //         GeneratorUtils::singularSnakeCase($field->constrain)
                        //     )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                        //         ->orWhere('code', $field->constrain)->first();

                        //     $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                        //     foreach ($lookatrrs as $sa) {
                        //         $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                        //         // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                        //     }
                        // }
                        if ($field->multiple > 0) {
                            $options = "
                                        @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                            <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                            </option>
                                        @endforeach";
                        } else {

                            $options = "
                                                @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                    <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                        {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                    </option>
                                                @endforeach";

                        }


                                // select
                                $template .= str_replace(
                                    [
                                        '{{fieldKebabCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldSpaceLowercase}}',
                                        '{{options}}',
                                        '{{nullable}}',
                                        '{{fieldSnakeCase}}',
                                        '{{multiple}}',
                                        '{{source}}',
                                        '{{multiple2}}',

                                        '{{source2}}',
                                        '{{fieldKebabCase2}}',
                                        '{{fieldUcWords2}}',
                                        '{{fieldSnakeCase2}}'

                                    ],
                                    [

                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                        GeneratorUtils::cleanSingularUcWords($field->name),
                                        GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                        $options,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $fieldSnakeCase,
                                        ($field->multiple > 0) ? 'multiple' : '',
                                        explode('_', $field->source)[0],
                                        ($field->multiple > 0) ? '[]' : '',

                                        $constrainModel,
                                        explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->constrain2)))[0],
                                        GeneratorUtils::cleanSingularUcWords($field->constrain2),
                                        GeneratorUtils::cleanSingularLowerCase($field->constrain2),



                                    ],
                                    GeneratorUtils::getTemplate('views/forms/informatic-prefix')
                                );



                        }

                        if($field->secondary == 'looksuffix'){

                            // remove '/' or sub folders
                     $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');

                     $constrainSingularCamelCase = GeneratorUtils::singularCamelCase($constrainModel);

                     $columnAfterId = $field->attribute;
                     $dataIds = '';

                     // if ($field->source != null) {

                     //     $current_model = Module::where(
                     //         'code',
                     //         GeneratorUtils::singularSnakeCase($field->constrain)
                     //     )->orWhere('code', GeneratorUtils::pluralSnakeCase($field->constrain))
                     //         ->orWhere('code', $field->constrain)->first();

                     //     $lookatrrs = Attribute::where("module", $current_model->id)->where('type', 'foreignId')->get();


                     //     foreach ($lookatrrs as $sa) {
                     //         $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . $sa->code . "}}\"";
                     //         // $dataIds .= "data-" . GeneratorUtils::singularSnakeCase($sa->constrain) . "=\"{{ \$" . $constrainSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($sa->constrain). "_" .str()->snake($sa->attribute)  . "_id" . "}}\"";
                     //     }
                     // }
                     if ($field->multiple > 0) {
                         $options = "
                                     @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                         <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && in_array($" . $constrainSingularCamelCase . "->id, $" . $modelNameSingularCamelCase . "->" . str_replace('_id', '', $fieldSnakeCase) . "()->pluck('id')->toArray())  ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                             {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                         </option>
                                     @endforeach";
                     } else {

                         $options = "
                                             @foreach (\$look_" . GeneratorUtils::pluralCamelCase($constrainModel) . " as $$constrainSingularCamelCase)
                                                 <option    $dataIds  value=\"{{ $" . $constrainSingularCamelCase . "->id }}\" {{ isset($$modelNameSingularCamelCase) && $" . $modelNameSingularCamelCase . "->$fieldSnakeCase == $" . $constrainSingularCamelCase . "->id ? 'selected' : (old('$fieldSnakeCase') == $" . $constrainSingularCamelCase . "->id ? 'selected' : '') }}>
                                                     {{ $" . $constrainSingularCamelCase . "->$columnAfterId }}
                                                 </option>
                                             @endforeach";

                     }


                             // select
                             $template .= str_replace(
                                 [
                                     '{{fieldKebabCase}}',
                                     '{{fieldUcWords}}',
                                     '{{fieldSpaceLowercase}}',
                                     '{{options}}',
                                     '{{nullable}}',
                                     '{{fieldSnakeCase}}',
                                     '{{multiple}}',
                                     '{{source}}',
                                     '{{multiple2}}',

                                     '{{source2}}',
                                     '{{fieldKebabCase2}}',
                                     '{{fieldUcWords2}}',
                                     '{{fieldSnakeCase2}}'

                                 ],
                                 [

                                     explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->code)))[0],

                                     GeneratorUtils::cleanSingularUcWords($field->name),
                                     GeneratorUtils::cleanSingularLowerCase($constrainModel),
                                     $options,
                                     $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                     $fieldSnakeCase,
                                     ($field->multiple > 0) ? 'multiple' : '',
                                     explode('_', $field->source)[0],
                                     ($field->multiple > 0) ? '[]' : '',

                                     $constrainModel,
                                     explode('_', str_replace('-', '_', GeneratorUtils::singularKebabCase($field->constrain2)))[0],
                                     GeneratorUtils::cleanSingularUcWords($field->constrain2),
                                     GeneratorUtils::cleanSingularLowerCase($field->constrain2),



                                 ],
                                 GeneratorUtils::getTemplate('views/forms/informatic-suffix')
                             );



                     }


                    }


                                break;

                            case 'file':
                            case 'image':

                                $template .= str_replace(
                                    [
                                        '{{modelCamelCase}}',
                                        '{{fieldPluralSnakeCase}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldLowercase}}',
                                        '{{fieldUcWords}}',
                                        '{{nullable}}',
                                        '{{uploadPathPublic}}',
                                        '{{fieldKebabCase}}',
                                        '{{defaultImage}}',
                                        '{{defaultImageCodeForm}}',
                                    ],
                                    [
                                        $modelNameSingularCamelCase,
                                        GeneratorUtils::pluralSnakeCase($field->name),
                                        str()->snake($field->code),
                                        GeneratorUtils::cleanSingularLowerCase($field->name),
                                        $fieldUcWords,
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        config('generator.image.path') == 'storage' ? "storage/uploads" : "uploads",
                                        str()->kebab($field->name),
                                        "",
                                        "",
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/image')
                                );
                                break;
                            case 'range':
                                $template .= str_replace(
                                    [
                                        '{{fieldSnakeCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldKebabCase}}',
                                        '{{nullable}}',
                                        '{{min}}',
                                        '{{max}}',
                                        '{{step}}',
                                        '{{value}}'
                                    ],
                                    [
                                        GeneratorUtils::singularSnakeCase($field->code),
                                        $fieldUcWords,
                                        GeneratorUtils::singularKebabCase($field->name),
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $field->min_length,
                                        $field->max_length,
                                        $field->steps ? 'step="' . $field->steps . '"' : '',
                                        $formatValue
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/range')
                                );
                                break;
                            case 'decimal':
                                $template .= str_replace(
                                    [
                                        '{{fieldSnakeCase}}',
                                        '{{fieldUcWords}}',
                                        '{{fieldKebabCase}}',
                                        '{{nullable}}',
                                        '{{value}}'

                                    ],
                                    [
                                        GeneratorUtils::singularSnakeCase($field->code),
                                        $fieldUcWords,
                                        GeneratorUtils::singularKebabCase($field->name),
                                        $field->required == 'yes' || $field->required == 'on' ? ' required' : '',
                                        $formatValue

                                    ],
                                    GeneratorUtils::getTemplate('views/forms/input-decimal')
                                );
                                break;
                            case 'hidden':
                                $template .= '<input type="hidden" name="' . $fieldSnakeCase . '" value="' . $field->default_value . '">';
                                break;
                            case 'password':
                                $template .= str_replace(
                                    [
                                        '{{fieldUcWords}}',
                                        '{{fieldSnakeCase}}',
                                        '{{fieldKebabCase}}',
                                        '{{model}}',
                                    ],
                                    [
                                        $fieldUcWords,
                                        $fieldSnakeCase,
                                        GeneratorUtils::singularKebabCase($field->name),
                                        $modelNameSingularCamelCase,
                                    ],
                                    GeneratorUtils::getTemplate('views/forms/input-password')
                                );
                                break;
                            default:
                                $template .= $this->setInputTypeTemplate(
                                    request: [
                                        'input_types' => $field->input,
                                        'requireds' => $field->required,
                                    ],
                                    model: $model,
                                    field: $field->code,
                                    formatValue: $formatValue,
                                    label: $field->name,
                                    source: $field->source
                                );
                                break;
                        }
                        break;
                }
            }
        }


        $template .= "</div>";

        // create a blade file
        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase/include"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/form.blade.php"), $template);
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase/include");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/form.blade.php", $template);
                break;
        }
    }

    public function reGenerateWithSub($id)
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->name);
        $path = GeneratorUtils::getModelLocation($module->name);
        $code = GeneratorUtils::setModelName($module->code);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);

        $template = "";
        $options = "<option>-- Select --</option>";

        foreach ($module->childs()->where('shared', 1)->get() as $model) {
            $options .= "<option data-id=\"{{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase" . "->data_id  ? \$$modelNameSingularCamelCase" . "->data_id : '' }}\"  {{ isset(\$$modelNameSingularCamelCase) && \$$modelNameSingularCamelCase" . "->sub_id == '$model->id' ? 'selected' : '' }} data-value=\"" . GeneratorUtils::pluralKebabCase($model->code) . "\" value=\"" . $model->id . "\" >" . $model->name . "</option>";
        }

        $template = str_replace(
            [
                '{{options}}',
            ],
            [
                $options,

            ],
            GeneratorUtils::getTemplate('views/create-sub')
        );


        // create a blade file
        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase/include"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/dropdown.blade.php"), $template);
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase/include");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/dropdown.blade.php", $template);
                break;
        }
    }

    /**
     * Set input type from .stub file.
     *
     * @param string $field
     * @param array $request
     * @param string $model
     * @param string $formatValue
     * @return string
     */
    public function setInputTypeTemplate(string $field, array $request, string $model, string $formatValue, $date = 0, string $label = '', $source = null): string
    {
        if ($date == 1) {
            return str_replace(
                [
                    '{{fieldKebabCase}}',
                    '{{fieldUcWords}}',
                    '{{fieldSnakeCase}}',
                    '{{type}}',
                    '{{value}}',
                    '{{nullable}}',
                ],
                [
                    GeneratorUtils::singularKebabCase($label),
                    GeneratorUtils::cleanUcWords($label),
                    str($field)->snake(),
                    $request['input_types'],
                    $formatValue,
                    $request['requireds'] == 'yes' ? ' required' : '',
                ],
                GeneratorUtils::getTemplate('views/forms/input-date')
            );
        }
        return str_replace(
            [
                '{{fieldKebabCase}}',
                '{{fieldUcWords}}',
                '{{fieldSnakeCase}}',
                '{{type}}',
                '{{value}}',
                '{{nullable}}',
                '{{source}}'
            ],
            [
                GeneratorUtils::singularKebabCase($label),
                GeneratorUtils::cleanUcWords($label),
                str($field)->snake(),
                $request['input_types'],
                $formatValue,
                $request['requireds'] == 'yes' ? ' required' : '',
                $source,

            ],
            GeneratorUtils::getTemplate('views/forms/input')
        );
    }
}
