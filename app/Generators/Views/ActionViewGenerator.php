<?php

namespace App\Generators\Views;
use App\Generators\GeneratorUtils;
use App\Models\Module;


class ActionViewGenerator
{
    /**
     * Generate a action(table) view.
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
        $modelNameSingularLowercase = GeneratorUtils::cleanSingularLowerCase($model);

        $template = str_replace(
            [
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralKebabCase}}'
            ],
            [
                $modelNameSingularLowercase,
                $modelNamePluralKebabCase
            ],
            GeneratorUtils::getTemplate('views/action')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase/include");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/action.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase/include"));

            file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/action.blade.php"), $template);
        };
    }

    public function reGenerate($id)
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->code, 'default');
        $path = GeneratorUtils::getModelLocation($module->name);
        $code = GeneratorUtils::setModelName($module->code, 'default');

        $modelNamePluralKebabCase = GeneratorUtils::pluralKebabCase($code);
        $modelNameSingularLowercase = GeneratorUtils::cleanSingularLowerCase($model);

        $template = str_replace(
            [
                '{{modelNameSingularLowercase}}',
                '{{modelNamePluralKebabCase}}'
            ],
            [
                $modelNameSingularLowercase,
                $modelNamePluralKebabCase
            ],
            GeneratorUtils::getTemplate('views/action')
        );

        if ($path != '') {
            $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase/include");

            GeneratorUtils::checkFolder($fullPath);

            file_put_contents($fullPath . "/action.blade.php", $template);
        } else {
            GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase/include"));

            file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/include/action.blade.php"), $template);
        };
    }
}
