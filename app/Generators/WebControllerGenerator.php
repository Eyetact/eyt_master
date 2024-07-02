<?php

namespace App\Generators;

use App\Models\Module;
use Illuminate\Support\Facades\File;


class WebControllerGenerator
{
    /**
     * Generate a controller file.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $model = GeneratorUtils::setModelName($request['code'], 'default');
        $path = GeneratorUtils::getModelLocation($request['code']);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);
        $modelNamePluralCamelCase = GeneratorUtils::pluralCamelCase($model);

        $code = GeneratorUtils::setModelName($request['code'], 'default');

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSpaceLowercase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);

        $query = "$modelNameSingularPascalCase::query()";

        switch ($path) {
            case '':
                $namespace = "namespace App\Http\Controllers\Admin;\nuse App\Http\Controllers\Controller;";
                $requestPath = "App\Http\Requests\Admin\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
                break;
            default:

                $namespace = "namespace App\Http\Controllers\Admin\\$path;\n\nuse App\Http\Controllers\Controller;";
                $requestPath = "App\Http\Requests\Admin\\" . $path . "\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
                break;
        }

        $relations = "";
        $addColumns = "";
        if (!empty($request['fields'][0])) {
            if (
                in_array('text', $request['column_types']) ||
                in_array('longText', $request['column_types'])
            ) {
                $limitText = config('generator.format.limit_text') ? config('generator.format.limit_text') : 200;

                foreach ($request['column_types'] as $i => $type) {
                    if ($type == 'text' || $type == 'longText') {
                        $addColumns .= "->addColumn('" . str($request['fields'][$i])->snake() . "', function(\$row){
                    return str(\$row->" . str($request['fields'][$i])->snake() . ")->limit($limitText);
                })\n\t\t\t\t";
                    }
                }
            }

            // load the relations for create, show, and edit
            if (in_array('foreignId', $request['column_types'])) {

                $relations .= "$" . $modelNameSingularCamelCase . "->load(";

                $countForeidnId = count(array_keys($request['column_types'], 'foreignId'));

                $query = "$modelNameSingularPascalCase::with(";

                foreach ($request['constrains'] as $i => $constrain) {
                    if ($constrain != null) {
                        $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);

                        $constrainSnakeCase = GeneratorUtils::singularSnakeCase($constrainName);
                        $selectedColumns = GeneratorUtils::selectColumnAfterIdAndIdItself($constrainName);
                        $columnAfterId = GeneratorUtils::getColumnAfterId($constrainName);

                        if ($countForeidnId + 1 < $i) {
                            $relations .= "'$constrainSnakeCase:$selectedColumns', ";
                            $query .= "'$constrainSnakeCase:$selectedColumns', ";
                        } else {
                            $relations .= "'$constrainSnakeCase:$selectedColumns'";
                            $query .= "'$constrainSnakeCase:$selectedColumns'";
                        }

                        $addColumns .= "->addColumn('$constrainSnakeCase', function (\$row) {
                    return \$row->" . $constrainSnakeCase . " ? \$row->" . $constrainSnakeCase . "->$columnAfterId : '';
                })";
                    }
                }

                $query .= ")";
                $relations .= ");\n\n\t\t";

                $query = str_replace("''", "', '", $query);
                $relations = str_replace("''", "', '", $relations);
            }
        }
        $insertDataAction = $modelNameSingularPascalCase . "::create(\$request->validated());";
        $updateDataAction = "\$" . $modelNameSingularCamelCase . "->update(\$request->validated());";

        /**
         * default controller
         */
        $template = str_replace(
            [
                '{{modelNameSingularPascalCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{modelNamePluralCamelCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSpaceLowercase}}',
                '{{loadRelation}}',
                '{{addColumns}}',
                '{{query}}',
                '{{namespace}}',
                '{{requestPath}}',
                '{{modelPath}}',
                '{{viewPath}}',
                '{{insertDataAction}}',
                '{{updateDataAction}}',
            ],
            [
                $modelNameSingularPascalCase,
                $modelNameSingularCamelCase,
                $modelNamePluralCamelCase,
                $modelNamePluralKebabCase,
                $modelNameSpaceLowercase,
                $relations,
                $addColumns,
                $query,
                $namespace,
                $requestPath,
                $path != '' ? "App\Models\Admin\\$path\\$modelNameSingularPascalCase" : "App\Models\Admin\\$modelNameSingularPascalCase",
                $path != '' ? str_replace('\\', '.', strtolower($path)) . "." : '',
                $insertDataAction,
                $updateDataAction,
            ],
            GeneratorUtils::getTemplate('controllers/controller')
        );
        $templatetrait = str_replace(
            [
                '{{modelNameSingularPascalCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{modelNamePluralCamelCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSpaceLowercase}}',
                '{{loadRelation}}',
                '{{addColumns}}',
                '{{query}}',
                '{{namespace}}',
                '{{requestPath}}',
                '{{modelPath}}',
                '{{viewPath}}',
                '{{insertDataAction}}',
                '{{updateDataAction}}',
            ],
            [
                $modelNameSingularPascalCase,
                $modelNameSingularCamelCase,
                $modelNamePluralCamelCase,
                $modelNamePluralKebabCase,
                $modelNameSpaceLowercase,
                $relations,
                $addColumns,
                $query,
                $namespace,
                $requestPath,
                $path != '' ? "App\Models\Admin\\$path\\$modelNameSingularPascalCase" : "App\Models\Admin\\$modelNameSingularPascalCase",
                $path != '' ? str_replace('\\', '.', strtolower($path)) . "." : '',
                $insertDataAction,
                $updateDataAction,
            ],
            GeneratorUtils::getTemplate('controllers/trait')
        );

        /**
         * Create a controller file.
         */
        switch ($path) {
            case '':
                file_put_contents(app_path("/Http/Controllers/Admin/{$modelNameSingularPascalCase}Controller.php"), $template);
                file_put_contents(app_path("/Http/Controllers/Admin/{$modelNameSingularPascalCase}Trait.php"), $templatetrait);
                break;
            default:
                $fullPath = app_path("/Http/Controllers/Admin/$path/");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents("$fullPath" . $modelNameSingularPascalCase . "Controller.php", $template);
                file_put_contents("$fullPath" . $modelNameSingularPascalCase . "Trait.php", $templatetrait);
                break;
        }
    }


    public function reGenerate($id)
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->code, 'default');
        $path = GeneratorUtils::getModelLocation($module->code);

        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($model);
        $modelNamePluralCamelCase = GeneratorUtils::pluralCamelCase($model);
        $code = GeneratorUtils::setModelName($module->code, 'default');

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSpaceLowercase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);

        $query = "$modelNameSingularPascalCase::query()";

        switch ($path) {
            case '':
                $namespace = "namespace App\Http\Controllers\Admin;\nuse App\Http\Controllers\Controller;";
                $requestPath = "App\Http\Requests\Admin\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
                break;
            default:

                $namespace = "namespace App\Http\Controllers\Admin\\$path;\n\nuse App\Http\Controllers\Controller;";
                $requestPath = "App\Http\Requests\Admin\\" . $path . "\{Store" . $modelNameSingularPascalCase . "Request, Update" . $modelNameSingularPascalCase . "Request}";
                break;
        }

        $relations = "";
        $addColumns = "";

        if (
            count($module->fields()->where('type', 'text')->get()) > 0 ||
            count($module->fields()->where('type', 'longtext')->get()) > 0
        ) {
            $limitText = config('generator.format.limit_text') ? config('generator.format.limit_text') : 200;

            foreach ($module->fields()->where('is_enable', 1)->get() as $i => $field) {
                if ($field->type == 'text' || $field->type == 'longText') {
                    $addColumns .= "->addColumn('" . str($field->code)->snake() . "', function(\$row){
                    return str(\$row->" . str($field->code)->snake() . ")->limit($limitText);
                })\n\t\t\t\t";
                }
            }
        }

        // dd($module);

        // dd();

        // load the relations for create, show, and edit
        // if ( count($module->fields()->where('is_enable',1)->where('type','foreignId')->orWhere('type','condition')->get()) > 0) {
        if (count($module->fields()->where('is_enable', 1)->where('type', 'foreignId')->get()) + count($module->fields()->where('is_enable', 1)->where('type', 'condition')->get()) + count($module->fields()->where('is_enable', 1)->where('type', 'fk')->get()) + count($module->fields()->where('is_enable', 1)->where('primary', 'lookup')->get()) + count($module->fields()->where('is_enable', 1)->where('type', 'informatic')->get())+ count($module->fields()->where('is_enable', 1)->where('type', 'doublefk')->get()) > 0) {
            $relations .= "$" . $modelNameSingularCamelCase . "->load(";

            $countForeidnId = count($module->fields()->where('type', 'foreignId')->orWhere('primary', 'lookup')->orWhere('type', 'condition')->orWhere('type', 'informatic')->orWhere('type','fk')->get());

            $query = "$modelNameSingularPascalCase::with(";

            foreach ($module->fields()->where('is_enable', 1)->get() as $i => $field) {
                $field->code = !empty($field->code) ? GeneratorUtils::singularSnakeCase($field->code) : GeneratorUtils::singularSnakeCase($field->name);
                if ($field->constrain != null && $field->type != 'doublefk' && $field->fk_type != 'based') {
                    $constrainName = GeneratorUtils::setModelName($field->constrain);

                    $columnsk = GeneratorUtils::singularSnakeCase($constrainName);
                    $constrainSnakeCase = str()->snake($constrainName) . "_" . str()->snake($field->attribute);

                    $selectedColumns = 'id,' . $field->attribute;
                    $columnAfterId = $field->attribute;

                    if ($countForeidnId + 1 < $i) {
                        $relations .= "'$constrainSnakeCase:$selectedColumns', ";
                        $query .= "'$constrainSnakeCase:$selectedColumns', ";
                    } else {
                        $relations .= "'$constrainSnakeCase:$selectedColumns'";
                        $query .= "'$constrainSnakeCase:$selectedColumns'";
                    }

                    // id name created_at
                    //$row->category->name



                    $addColumns .= "->addColumn('$constrainSnakeCase', function (\$row) {

                            if (!is_a(\$row->" . $constrainSnakeCase . ", 'Illuminate\Database\Eloquent\Collection')) {


                                return \$row->" . $constrainSnakeCase . " ? \$row->" . $constrainSnakeCase . "->$columnAfterId : '';
                            } else {
                                \$text = '';
                                foreach (\$row->" . $constrainSnakeCase . " as \$value) {
                                    \$text .= \$value->$columnAfterId . ', ';
                                }
                                return \$text;
                            }



                })";
                }

                if ($field->constrain != null && $field->type  == 'doublefk') {
                    $constrainName = GeneratorUtils::setModelName($field->constrain);
                    $constrainName2 = GeneratorUtils::setModelName($field->constrain2);

                    $columnsk = GeneratorUtils::singularSnakeCase($constrainName);
                    $columnsk2 = GeneratorUtils::singularSnakeCase($constrainName2);

                    $constrainSnakeCase = str()->snake($constrainName) . "_" . str()->snake($field->attribute);
                    $constrainSnakeCase2 = str()->snake($constrainName2) . "_" . str()->snake($field->attribute2);

                    $selectedColumns = 'id,' . $field->attribute ;
                    $selectedColumns2 = 'id,' . $field->attribute2 ;

                    $columnAfterId = $field->attribute;
                    $columnAfterId2 = $field->attribute2;

                    if ($countForeidnId + 1 < $i) {
                        $relations .= "'$constrainSnakeCase:$selectedColumns', ";
                        $relations .= "'$constrainSnakeCase2:$selectedColumns2', ";
                        $query .= "'$constrainSnakeCase:$selectedColumns', ";
                        $query .= "'$constrainSnakeCase2:$selectedColumns2', ";
                    } else {
                        $relations .= "'$constrainSnakeCase:$selectedColumns'";
                        $relations .= "'$constrainSnakeCase2:$selectedColumns2'";

                        $query .= "'$constrainSnakeCase:$selectedColumns'";
                        $query .= "'$constrainSnakeCase2:$selectedColumns2'";
                    }

                    // id name created_at
                    //$row->category->name



                    $addColumns .= "->addColumn('$constrainSnakeCase', function (\$row) {

                            if (!is_a(\$row->" . $constrainSnakeCase . ", 'Illuminate\Database\Eloquent\Collection')) {


                                return \$row->" . $constrainSnakeCase . " ? \$row->" . $constrainSnakeCase . "->$columnAfterId . ', ' . \$row->" . $constrainSnakeCase2 . "->$columnAfterId2  : '';
                            } else {
                                \$text = '';
                                foreach (\$row->" . $constrainSnakeCase . " as \$value) {
                                    \$text .= \$value->$columnAfterId . ', ';
                                }
                                return \$text;
                            }



                })";
                }

                if ($field->constrain != null && $field->fk_type  == 'based') {
                    $constrainName = GeneratorUtils::setModelName($field->constrain);
                    $constrainName2 = GeneratorUtils::setModelName($field->constrain2);

                    $columnsk = GeneratorUtils::singularSnakeCase($constrainName);
                    $columnsk2 = GeneratorUtils::singularSnakeCase($constrainName2);

                    $constrainSnakeCase = str()->snake($constrainName) . "_" . str()->snake($field->attribute);
                    $constrainSnakeCase2 = str()->snake($constrainName2) . "_" . str()->snake($field->attribute2);

                    $selectedColumns = 'id,' . $field->attribute ;
                    $selectedColumns2 = 'id,' . $field->attribute2 ;

                    $columnAfterId = $field->attribute;
                    $columnAfterId2 = $field->attribute2;

                    if ($countForeidnId + 1 < $i) {
                        $relations .= "'$constrainSnakeCase:$selectedColumns', ";
                        $relations .= "'$constrainSnakeCase2:$selectedColumns2', ";
                        $query .= "'$constrainSnakeCase:$selectedColumns', ";
                        $query .= "'$constrainSnakeCase2:$selectedColumns2', ";
                    } else {
                        $relations .= "'$constrainSnakeCase:$selectedColumns'";
                        $relations .= "'$constrainSnakeCase2:$selectedColumns2'";

                        $query .= "'$constrainSnakeCase:$selectedColumns'";
                        $query .= "'$constrainSnakeCase2:$selectedColumns2'";
                    }

                    // id name created_at
                    //$row->category->name



                    $addColumns .= "->addColumn('$constrainSnakeCase', function (\$row) {

                            if (!is_a(\$row->" . $constrainSnakeCase . ", 'Illuminate\Database\Eloquent\Collection')) {


                                return \$row->" . $constrainSnakeCase . " ? \$row->" . $constrainSnakeCase . "->$columnAfterId . ', ' . \$row->" . $constrainSnakeCase2 . "->$columnAfterId2  : '';
                            } else {
                                \$text = '';
                                foreach (\$row->" . $constrainSnakeCase . " as \$value) {
                                    \$text .= \$value->$columnAfterId . ', ';
                                }
                                return \$text;
                            }



                })";
                }

            }

            $query .= ")";
            $relations .= ");\n\n\t\t";

            $query = str_replace("''", "', '", $query);
            $relations = str_replace("''", "', '", $relations);
        }

        $insertDataAction = $modelNameSingularPascalCase . "::create(\$request->validated());";
        $updateDataAction = "\$" . $modelNameSingularCamelCase . "->update(\$request->validated());";

        /**
         * default controller
         */
        $template = str_replace(
            [
                '{{modelNameSingularPascalCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{modelNamePluralCamelCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSpaceLowercase}}',
                '{{loadRelation}}',
                '{{addColumns}}',
                '{{query}}',
                '{{namespace}}',
                '{{requestPath}}',
                '{{modelPath}}',
                '{{viewPath}}',
                '{{insertDataAction}}',
                '{{updateDataAction}}',
                '{{ID}}'
            ],
            [
                $modelNameSingularPascalCase,
                $modelNameSingularCamelCase,
                $modelNamePluralCamelCase,
                $modelNamePluralKebabCase,
                $modelNameSpaceLowercase,
                $relations,
                $addColumns,
                $query,
                $namespace,
                $requestPath,
                $path != '' ? "App\Models\Admin\\$path\\$modelNameSingularPascalCase" : "App\Models\Admin\\$modelNameSingularPascalCase",
                $path != '' ? str_replace('\\', '.', strtolower($path)) . "." : '',
                $insertDataAction,
                $updateDataAction,
                $id
            ],
            GeneratorUtils::getTemplate('controllers/controller')
        );
        $templatetrait = str_replace(
            [
                '{{modelNameSingularPascalCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{modelNamePluralCamelCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSpaceLowercase}}',
                '{{loadRelation}}',
                '{{addColumns}}',
                '{{query}}',
                '{{namespace}}',
                '{{requestPath}}',
                '{{modelPath}}',
                '{{viewPath}}',
                '{{insertDataAction}}',
                '{{updateDataAction}}',
                '{{ID}}'
            ],
            [
                $modelNameSingularPascalCase,
                $modelNameSingularCamelCase,
                $modelNamePluralCamelCase,
                $modelNamePluralKebabCase,
                $modelNameSpaceLowercase,
                $relations,
                $addColumns,
                $query,
                $namespace,
                $requestPath,
                $path != '' ? "App\Models\Admin\\$path\\$modelNameSingularPascalCase" : "App\Models\Admin\\$modelNameSingularPascalCase",
                $path != '' ? str_replace('\\', '.', strtolower($path)) . "." : '',
                $insertDataAction,
                $updateDataAction,
                $id
            ],
            GeneratorUtils::getTemplate('controllers/trait')
        );

        /**
         * Create a controller file.
         */
        switch ($path) {
            case '':
                file_put_contents(app_path("/Http/Controllers/Admin/{$modelNameSingularPascalCase}Controller.php"), $template);
                if (!File::exists(app_path("/Http/Controllers/Admin/{$modelNameSingularPascalCase}Trait.php"))) {
                    file_put_contents(app_path("/Http/Controllers/Admin/{$modelNameSingularPascalCase}Trait.php"), $templatetrait);
                }
                break;
            default:
                $fullPath = app_path("/Http/Controllers/Admin/$path/");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents("$fullPath" . $modelNameSingularPascalCase . "Controller.php", $template);
                if (!File::exists("$fullPath" . $modelNameSingularPascalCase . "Trait.php")) {
                    file_put_contents("$fullPath" . $modelNameSingularPascalCase . "Trait.php", $templatetrait);

                }
                break;
        }
    }

}
