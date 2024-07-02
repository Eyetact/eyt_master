<?php

namespace App\Generators\Views;
use App\Generators\GeneratorUtils;
use App\Models\Module;


class CreateViewGenerator
{
    /**
     * Generate a create view.
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

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,

                ' enctype="multipart/form-data"',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/create')
        );

        $template_less = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,

                ' enctype="multipart/form-data"',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/create-less')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/create.blade.php"), $template);
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/create-less.blade.php"), $template_less);
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/create.blade.php", $template);
                file_put_contents($fullPath . "/create-less.blade.php", $template_less);
                break;
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

        $template = str_replace(
            [
                '{{modelNamePluralUcWords}}',
                '{{modelNameSingularLowerCase}}',
                '{{modelNamePluralKebabCase}}',
                '{{enctype}}',
                '{{viewPath}}',
            ],
            [
                $modelNamePluralUcWords,
                $modelNameSingularLowerCase,
                $modelNamePluralKebabCase,

                ' enctype="multipart/form-data"',
                $path != '' ? str_replace('\\', '.', $path) . "." : ''
            ],
            GeneratorUtils::getTemplate('views/create')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(resource_path("/views/admin/$modelNamePluralKebabCase"));
                file_put_contents(resource_path("/views/admin/$modelNamePluralKebabCase/create.blade.php"), $template);
                break;
            default:
                $fullPath = resource_path("/views/admin/" . strtolower($path) . "/$modelNamePluralKebabCase");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/create.blade.php", $template);
                break;
        }
    }
}
