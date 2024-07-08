<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use App\Models\Module;

class ShowViewGenerator
{
    /**
     * Generate a show view.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $model = GeneratorUtils::setModelName($request['name'], 'default');
        $path = GeneratorUtils::getModelLocation($request['name']);

        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));
        $code = GeneratorUtils::setModelName($request['code'], 'default');

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);

        $trs = "";
        $totalFields = !empty($request['fields']) ? count($request['fields']) : 0;
        $dateTimeFormat = config('generator.format.datetime') ? config('generator.format.datetime') : 'd/m/Y H:i';

        if (!empty($request['fields'][0])) {
            foreach ($request['fields'] as $i => $field) {
                if ($request['input_types'][$i] != 'password') {
                    if ($i >= 1) {
                        $trs .= "\t\t\t\t\t\t\t\t\t";
                    }

                    $fieldUcWords = GeneratorUtils::cleanUcWords($field);
                    $fieldSnakeCase = str($field)->snake();

                    if (isset($request['file_types'][$i]) && $request['file_types'][$i] == 'image') {

                        $uploadPath = config('generator.image.path') == 'storage' ? "storage/uploads/" : "uploads/";

                        $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>

                                                <img src=\"{{ asset( $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") }}\" alt=\"$fieldUcWords\" class=\"rounded\" width=\"200\" height=\"150\" style=\"object-fit: cover\">
                                        </td>
                                    </tr>";
                    }

                    switch ($request['column_types'][$i]) {
                        case 'boolean':
                            $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " == 1 ? 'True' : 'False' }}</td>
                                    </tr>";
                            break;
                        case 'foreignId':

                            // remove '/' or sub folders
                            $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i], 'default');

                            $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . " ? $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "->" . GeneratorUtils::getColumnAfterId($constrainModel) . " : '' }}</td>
                                    </tr>";
                            break;
                        case 'date':
                            $dateFormat = config('generator.format.date') ? config('generator.format.date') : 'd/m/Y';

                            if ($request['input_types'][$i] == 'month') {
                                $dateFormat = config('generator.format.month') ? config('generator.format.month') : 'm/Y';
                            }

                            $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$dateFormat') : ''  }}</td>
                                        </tr>";
                            break;
                        case 'dateTime':
                            $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$dateTimeFormat') : ''  }}</td>
                                        </tr>";
                            break;
                        case 'time':
                            $timeFormat = config('generator.format.time') ? config('generator.format.time') : 'H:i';

                            $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$timeFormat') : ''  }}</td>
                                        </tr>";
                            break;
                        default:
                            if ($request['file_types'][$i] != 'image') {
                                $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " }}</td>
                                        </tr>";
                            }
                            break;
                    }

                    if ($i + 1 != $totalFields) {
                        $trs .= "\n";
                    }
                }
            }
        }

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{trs}}',
                '{{dateTimeFormat}}'
            ],
            [
                GeneratorUtils::cleanPluralUcWords($model),
                GeneratorUtils::cleanSingularLowerCase($model),
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                $trs,
                $dateTimeFormat
            ],
            GeneratorUtils::getTemplate('views/show')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/show.blade.php"), $template);
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/show.blade.php", $template);
                break;
        }
    }

    public function reGenerate($id)
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->name, 'default');
        $path = GeneratorUtils::getModelLocation($module->name);

        $code = GeneratorUtils::setModelName($module->code, 'default');

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);

        $trs = "";
        $totalFields = count($module->fields()->where('is_enable', 1)->get());
        $dateTimeFormat = config('generator.format.datetime') ? config('generator.format.datetime') : 'd/m/Y H:i';

        foreach ($module->fields()->where('is_enable', 1)->get() as $i => $field) {
            $field->name = GeneratorUtils::singularSnakeCase($field->name);
            $field->code = !empty($field->code) ?  GeneratorUtils::singularSnakeCase($field->code) : GeneratorUtils::singularSnakeCase($field->name);
            if ($field->input != 'password') {
                if ($i >= 1) {
                    $trs .= "\t\t\t\t\t\t\t\t\t";
                }

                $fieldUcWords = GeneratorUtils::cleanUcWords($field->name);
                $fieldSnakeCase = str($field->code)->snake();

                if (isset($field->file_type) && $field->file_type == 'image') {

                    $uploadPath = config('generator.image.path') == 'storage' ? "storage/uploads/" : "uploads/";

                    $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>

                                                <img src=\"{{ asset( $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") }}\" alt=\"$fieldUcWords\" class=\"rounded\" width=\"200\" height=\"150\" style=\"object-fit: cover\">
                                        </td>
                                    </tr>";
                }

                switch ($field->type) {

                    case 'boolean':
                        $trs .= "<tr>
                                        <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                        <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " == 1 ? 'True' : 'False' }}</td>
                                    </tr>";
                        break;
                    case 'foreignId':
                        case 'informatic':

                            // remove '/' or sub folders
                            $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');


                        // Define the variable $value




                        $text = '';
                        $value = '';

                        $trs .= "<tr>
                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                        @php
                        \$text = '';
                        \$value = '';
                        if (!is_a(\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . ", 'Illuminate\Database\Eloquent\Collection')) {
                            \$text= \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " ? \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . "->" . $field->attribute . " : '';
                        } else {
                            foreach (\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " as \$value) {

                                \$text .= \$value->" . $field->attribute . ".',';
                            }
                        }
                        @endphp";

                    $trs .= "<td>{{ \$text }}</td>
                    </tr>";
                    break;


                    case 'fk' :
                        if($field->fk_type == 'basic' || $field->fk_type == 'condition')
                        {

                            // remove '/' or sub folders
                            $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');


                        // Define the variable $value




                        $text = '';
                        $value = '';

                        $trs .= "<tr>
                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                        @php
                        \$text = '';
                        \$value = '';
                        if (!is_a(\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . ", 'Illuminate\Database\Eloquent\Collection')) {
                            \$text= \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " ? \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . "->" . $field->attribute . " : '';
                        } else {
                            foreach (\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " as \$value) {

                                \$text .= \$value->" . $field->attribute . ".',';
                            }
                        }
                        @endphp";

                    $trs .= "<td>{{ \$text }}</td>
                    </tr>";

                        }


                        if($field->fk_type == 'based')

                        {

                                  // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');
                        $constrainModel2 = GeneratorUtils::setModelName($field->constrain2, 'default');


                    // Define the variable $value




                    $text = '';
                    $value = '';

                    $trs .= "<tr>
                    <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                    @php
                    \$text = '';
                    \$value = '';
                    if (!is_a(\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . ", 'Illuminate\Database\Eloquent\Collection')) {
                        \$text= \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " ? \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . "->" . $field->attribute . " .  ', ' . \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel2) . "_" . str()->snake($field->attribute2) . "->" . $field->attribute2 . " : '';
                    } else {
                        foreach (\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " as \$value) {

                            \$text .= \$value->" . $field->attribute . ".',';
                        }
                    }
                    @endphp";

                $trs .= "<td>{{ \$text }}</td>
                </tr>";
                        }


                        break;

                    case 'doubleattr':
                        if($field->primary == 'lookup'){


                            // remove '/' or sub folders
                            $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');


                        // Define the variable $value




                        $text = '';
                        $value = '';

                        $trs .= "<tr>
                        <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                        @php
                        \$text = '';
                        \$value = '';
                        if (!is_a(\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . ", 'Illuminate\Database\Eloquent\Collection')) {
                            \$text= \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " ? \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . "->" . $field->attribute . " : '';
                        } else {
                            foreach (\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " as \$value) {

                                \$text .= \$value->" . $field->attribute . ".',';
                            }
                        }
                        @endphp";

                    $trs .= "<td>{{ \$text }}</td>
                    </tr>";

                        }
                        break;

                    case 'doublefk':

                        // remove '/' or sub folders
                        $constrainModel = GeneratorUtils::setModelName($field->constrain, 'default');
                        $constrainModel2 = GeneratorUtils::setModelName($field->constrain2, 'default');


                    // Define the variable $value




                    $text = '';
                    $value = '';

                    $trs .= "<tr>
                    <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                    @php
                    \$text = '';
                    \$value = '';
                    if (!is_a(\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . ", 'Illuminate\Database\Eloquent\Collection')) {
                        \$text= \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " ? \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . "->" . $field->attribute . " .  ', ' . \$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel2) . "_" . str()->snake($field->attribute2) . "->" . $field->attribute2 . " : '';
                    } else {
                        foreach (\$" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel) . "_" . str()->snake($field->attribute) . " as \$value) {

                            \$text .= \$value->" . $field->attribute . ".',';
                        }
                    }
                    @endphp";

                $trs .= "<td>{{ \$text }}</td>
                </tr>";
                break;







                        //                 <td class=\"fw-bold\">{{ __('" . GeneratorUtils::cleanSingularUcWords($constrainModel) . "') }}</td>
                        //                 <td>{{ $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel). "_" .str()->snake($field->attribute)  . " ? $" . $modelNameSingularCamelCase . "->" . GeneratorUtils::singularSnakeCase($constrainModel). "_" .str()->snake($field->attribute) . "->" . $field->attribute . " : '' }}</td>
                        //             </tr>";
                        // break;
                    case 'date':
                        $dateFormat = config('generator.format.date') ? config('generator.format.date') : 'd/m/Y';

                        if ($module->input == 'month') {
                            $dateFormat = config('generator.format.month') ? config('generator.format.month') : 'm/Y';
                        }

                        $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$dateFormat') : ''  }}</td>
                                        </tr>";
                        break;
                    case 'dateTime':
                        $trs .= "<tr>
                                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                            <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$dateTimeFormat') : ''  }}</td>
                                        </tr>";
                        break;
                    case 'time':
                        $timeFormat = config('generator.format.time') ? config('generator.format.time') : 'H:i';

                        $trs .= "<tr>
                                                <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                                <td>{{ isset($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . ") ? $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . "->format('$timeFormat') : ''  }}</td>
                                            </tr>";
                        break;

                    case 'multi':

                        $trs .= "<tr>
                                                    <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                                                    <td>

                                                    @php

                                                    \$ar = json_decode($" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase  . ")

                                                    @endphp

                                                    <table>
                                                        <thead>
                                                        ";

                        foreach ($field->multis as $key => $value) {
                            $trs .= "<th>" . $value->name . "</th>";
                        }


                        $trs .= "</thead>

                                                        <tbody>
                                                        @if(!empty(\$ar))
                                                        @foreach( \$ar as \$item )
                                                        <tr>";
                        foreach ($field->multis as $key => $value) {

                            if($value->type=="texteditor"){
                                $trs .= "<td>{!! \$item->" . $value->code . " !!}</td>";

                            }
                            else{

                            $trs .= "<td>{{ \$item->" . $value->code . " }}</td>";
                            }


                        }

                        $trs .= "</tr>
                                                         @endforeach
                                                         @endif
                                                        </tbody>
                                                    </table>


                                                    </td>
                                                </tr>";
                        break;



                    default:
                        if ($field->file_type != 'image') {
                           if($field->input == 'texteditor'){

                            $trs .= "<tr>
                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                            <td>{!! $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " !!}</td>
                        </tr>";


                           }else{
                            $trs .= "<tr>
                            <td class=\"fw-bold\">{{ __('$fieldUcWords') }}</td>
                            <td>{{ $" . $modelNameSingularCamelCase . "->" . $fieldSnakeCase . " }}</td>
                        </tr>";
                           }
                        }
                        break;
                }

                if ($i + 1 != $totalFields) {
                    $trs .= "\n";
                }
            }
        }





        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{trs}}',
                '{{dateTimeFormat}}'
            ],
            [
                GeneratorUtils::cleanPluralUcWords($model),
                GeneratorUtils::cleanSingularLowerCase($model),
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                $trs,
                $dateTimeFormat
            ],
            GeneratorUtils::getTemplate('views/show')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/show.blade.php"), $template);
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/show.blade.php", $template);
                break;
        }
    }
}
