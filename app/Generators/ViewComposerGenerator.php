<?php

namespace App\Generators;

use App\Models\Crud;
use App\Models\Module;
use File;
use Illuminate\Support\Facades\Schema;

class ViewComposerGenerator
{
    /**
     * Generate view composer on viewServiceProvider, if any belongsTo relation.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $template = "";

        $model = GeneratorUtils::setModelName($request['name']);
        $viewPath = GeneratorUtils::getModelLocation($request['name']);


        if (!empty($request['fields'][0])) {
            foreach ($request['column_types'] as $i => $dataType) {
                if ($dataType == 'foreignId') {
                    // remove '/' or sub folders
                    $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i]);

                    $relatedModelPath = GeneratorUtils::getModelLocation($request['constrains'][$i]);
                    $table = GeneratorUtils::pluralSnakeCase($constrainModel);

                    if ($relatedModelPath != '') {
                        $relatedModelPath = "\App\Models\\$relatedModelPath\\$constrainModel";
                    } else {
                        $relatedModelPath = "\App\Models\\" . GeneratorUtils::singularPascalCase($constrainModel);
                    }

                    $allColums = Schema::getColumnListing($table);

                    if (sizeof($allColums) > 0) {
                        $fieldsSelect = "'id', '$allColums[1]'";
                    } else {
                        $fieldsSelect = "'id'";
                    }

                    if ($i > 1) {
                        $template .= "\t\t";
                    }

                    $template = str_replace(
                        [
                            '{{modelNamePluralKebabCase}}',
                            '{{constrainsPluralCamelCase}}',
                            '{{constrainsSingularPascalCase}}',
                            '{{fieldsSelect}}',
                            '{{relatedModelPath}}',
                            '{{viewPath}}',
                        ],
                        [
                            GeneratorUtils::pluralKebabCase($model),
                            GeneratorUtils::pluralCamelCase($constrainModel),
                            GeneratorUtils::singularPascalCase($constrainModel),
                            $fieldsSelect,
                            $relatedModelPath,
                            $viewPath != '' ? str_replace('\\', '.', strtolower($viewPath)) . "." : '',
                        ],
                        GeneratorUtils::getTemplate('view-composer')
                    );
                    $path = app_path('Providers/Generator/ViewServiceProvider.php');

                    $viewProviderTemplate = substr(file_get_contents($path), 0, -6) . "\n\n\t\t" . $template . "\n\t}\n}";

                    file_put_contents($path, $viewProviderTemplate);
                }
            }
        }

    }

    public function reGenerate($id)
    {
        $module = Module::find($id);
        $template = "";

        $model = GeneratorUtils::setModelName($module->name);
        $viewPath = GeneratorUtils::getModelLocation($module->name);


        foreach ($module->fields as $i => $field) {
            $field->name = GeneratorUtils::singularSnakeCase($field->name);
            if ($field->type == 'foreignId' || $field->type == 'informatic' || $field->type == 'condition' || $field->primary == 'lookup' || $field->fk_type == 'basic' || $field->fk_type == 'condition') {
                // remove '/' or sub folders
                $constrainModel = GeneratorUtils::setModelName($field->constrain);

                $relatedModelPath = GeneratorUtils::getModelLocation($field->constrain);
                $table = GeneratorUtils::pluralSnakeCase($constrainModel);

                if ($relatedModelPath != '') {
                    $relatedModelPath = "\App\Models\Admin\\$relatedModelPath\\$constrainModel";
                } else {
                    $relatedModelPath = "\App\Models\Admin\\" . GeneratorUtils::singularPascalCase($constrainModel);
                }

                $allColums = Schema::getColumnListing($table);

                if (sizeof($allColums) > 0) {
                    $fieldsSelect = "'id', '$field->attribute'";
                } else {
                    $fieldsSelect = "'id'";
                }

                if ($i > 1) {
                    $template .= "\t\t";
                }

                $template = str_replace(
                    [
                        '{{modelNamePluralKebabCase}}',
                        '{{constrainsPluralCamelCase}}',
                        '{{constrainsSingularPascalCase}}',
                        '{{constrainsPluralLowecase}}',
                        '{{fieldsSelect}}',
                        '{{fieldfirst}}',
                        '{{relatedModelPath}}',
                        '{{viewPath}}',
                    ],
                    [
                        GeneratorUtils::pluralKebabCase($model),
                        GeneratorUtils::pluralCamelCase($constrainModel),
                        GeneratorUtils::singularPascalCase($constrainModel),
                        GeneratorUtils::pluralSnakeCase($constrainModel),
                        $fieldsSelect,
                        $field->attribute,
                        $relatedModelPath,
                        $viewPath != '' ? str_replace('\\', '.', strtolower($viewPath)) . "." : '',
                    ],
                    GeneratorUtils::getTemplate('view-composer')
                );
                $path = app_path('Providers/Generator/ViewServiceProvider.php');

                $viewProviderTemplate = substr(file_get_contents($path), 0, -6) . "\n\n\t\t" . $template . "\n\t}\n}";

                file_put_contents($path, $viewProviderTemplate);
            }

            if($field->type == 'doublefk' || $field->fk_type == 'based'){

                // the first one
                $constrainModel = GeneratorUtils::setModelName($field->constrain);

                $relatedModelPath = GeneratorUtils::getModelLocation($field->constrain);
                $table = GeneratorUtils::pluralSnakeCase($constrainModel);

                if ($relatedModelPath != '') {
                    $relatedModelPath = "\App\Models\Admin\\$relatedModelPath\\$constrainModel";
                } else {
                    $relatedModelPath = "\App\Models\Admin\\" . GeneratorUtils::singularPascalCase($constrainModel);
                }

                $allColums = Schema::getColumnListing($table);

                if (sizeof($allColums) > 0) {
                    $fieldsSelect = "'id', '$field->attribute'";
                } else {
                    $fieldsSelect = "'id'";
                }

                if ($i > 1) {
                    $template .= "\t\t";
                }

                $template = str_replace(
                    [
                        '{{modelNamePluralKebabCase}}',
                        '{{constrainsPluralCamelCase}}',
                        '{{constrainsSingularPascalCase}}',
                        '{{constrainsPluralLowecase}}',
                        '{{fieldsSelect}}',
                        '{{fieldfirst}}',
                        '{{relatedModelPath}}',
                        '{{viewPath}}',
                    ],
                    [
                        GeneratorUtils::pluralKebabCase($model),
                        GeneratorUtils::pluralCamelCase($constrainModel),
                        GeneratorUtils::singularPascalCase($constrainModel),
                        GeneratorUtils::pluralSnakeCase($constrainModel),
                        $fieldsSelect,
                        $field->attribute,
                        $relatedModelPath,
                        $viewPath != '' ? str_replace('\\', '.', strtolower($viewPath)) . "." : '',
                    ],
                    GeneratorUtils::getTemplate('view-composer')
                );
                $path = app_path('Providers/Generator/ViewServiceProvider.php');

                $viewProviderTemplate = substr(file_get_contents($path), 0, -6) . "\n\n\t\t" . $template . "\n\t}\n}";

                file_put_contents($path, $viewProviderTemplate);




                //the secound one
                $constrainModel = GeneratorUtils::setModelName($field->constrain2);

                $relatedModelPath = GeneratorUtils::getModelLocation($field->constrain2);
                $table = GeneratorUtils::pluralSnakeCase($constrainModel);

                if ($relatedModelPath != '') {
                    $relatedModelPath = "\App\Models\Admin\\$relatedModelPath\\$constrainModel";
                } else {
                    $relatedModelPath = "\App\Models\Admin\\" . GeneratorUtils::singularPascalCase($constrainModel);
                }

                $allColums = Schema::getColumnListing($table);

                if (sizeof($allColums) > 0) {
                    $fieldsSelect = "'id', '$field->attribute2'";
                } else {
                    $fieldsSelect = "'id'";
                }

                if ($i > 1) {
                    $template .= "\t\t";
                }

                $template = str_replace(
                    [
                        '{{modelNamePluralKebabCase}}',
                        '{{constrainsPluralCamelCase}}',
                        '{{constrainsSingularPascalCase}}',
                        '{{constrainsPluralLowecase}}',
                        '{{fieldsSelect}}',
                        '{{fieldfirst}}',
                        '{{relatedModelPath}}',
                        '{{viewPath}}',
                    ],
                    [
                        GeneratorUtils::pluralKebabCase($model),
                        GeneratorUtils::pluralCamelCase($constrainModel),
                        GeneratorUtils::singularPascalCase($constrainModel),
                        GeneratorUtils::pluralSnakeCase($constrainModel),
                        $fieldsSelect,
                        $field->attribute2,
                        $relatedModelPath,
                        $viewPath != '' ? str_replace('\\', '.', strtolower($viewPath)) . "." : '',
                    ],
                    GeneratorUtils::getTemplate('view-composer')
                );
                $path = app_path('Providers/Generator/ViewServiceProvider.php');

                $viewProviderTemplate = substr(file_get_contents($path), 0, -6) . "\n\n\t\t" . $template . "\n\t}\n}";

                file_put_contents($path, $viewProviderTemplate);

            }
        }


    }

    /**
     * remove view composer from viewServiceProvider, if any belongsTo relation.
     *
     * @param $id $id
     *
     * @return void
     */
    public function remove($id)
    {
        $template = "";

        $crud = Crud::find($id);
        $model = GeneratorUtils::setModelName($crud->name, 'default');
        $viewPath = GeneratorUtils::getModelLocation($crud->name);

        foreach ($crud->fields as $i => $feild) {
            if ($feild->type == 'foreignId' || $feild->type == 'fk') {
                // remove '/' or sub folders
                $constrainModel = GeneratorUtils::setModelName($feild->constrain);

                $relatedModelPath = GeneratorUtils::getModelLocation($feild->constrain);
                $table = GeneratorUtils::pluralSnakeCase($constrainModel);

                if ($relatedModelPath != '') {
                    $relatedModelPath = "\App\Models\\$relatedModelPath\\$constrainModel";
                } else {
                    $relatedModelPath = "\App\Models\\" . GeneratorUtils::singularPascalCase($constrainModel);
                }

                $allColums = Schema::getColumnListing($table);

                if (sizeof($allColums) > 0) {
                    $fieldsSelect = "'id', '$allColums[1]'";
                } else {
                    $fieldsSelect = "'id'";
                }

                if ($i > 1) {
                    $template .= "\t\t";
                }

                $template = str_replace(
                    [
                        '{{modelNamePluralKebabCase}}',
                        '{{constrainsPluralCamelCase}}',
                        '{{constrainsSingularPascalCase}}',
                        '{{fieldsSelect}}',
                        '{{relatedModelPath}}',
                        '{{viewPath}}',
                    ],
                    [
                        GeneratorUtils::pluralKebabCase($model),
                        GeneratorUtils::pluralCamelCase($constrainModel),
                        GeneratorUtils::singularPascalCase($constrainModel),
                        $fieldsSelect,
                        $relatedModelPath,
                        $viewPath != '' ? str_replace('\\', '.', strtolower($viewPath)) . "." : '',
                    ],
                    GeneratorUtils::getTemplate('view-composer')
                );
                File::replaceInFile($template, '', app_path('Providers/ViewServiceProvider.php'));
            }
        }
    }
}
