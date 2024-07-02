<?php

namespace App\Generators\Views;

use App\Generators\GeneratorUtils;
use App\Models\Module;

class EditViewGenerator
{
    /**
     * Generate an edit view.
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

        

        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($model);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                ' enctype="multipart/form-data"',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/edit')
        );

        $template_less = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                ' enctype="multipart/form-data"',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/edit-less')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/edit.blade.php", $template);
            file_put_contents($fullPath . "/edit-less.blade.php", $template_less);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase"));

            file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/edit.blade.php"), $template);
            file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/edit-less.blade.php"), $template_less);
        }
    }

    public function reGenerate($id)
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->name, 'default');
        $path = GeneratorUtils::getModelLocation($module->name);
        $code = GeneratorUtils::setModelName($module->code, 'default');

        $modelNamePluralUcWords = GeneratorUtils::cleanPluralUcWords($model);
        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSingularLowerCase = GeneratorUtils::cleanSingularLowerCase($model);
        $modelNameSingularCamelCase = GeneratorUtils::singularCamelCase($code);

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{modelNameSingularCamelCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,
                $modelNameSingularCamelCase,
                ' enctype="multipart/form-data"',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/edit')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/edit.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase"));

            file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/edit.blade.php"), $template);
        }
    }
}
