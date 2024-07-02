<?php

namespace App\Generators;

use App\Models\Crud;
use Illuminate\Support\Facades\File;

class WebRouteGenerator
{
    /**
     * Generate a route on web.php.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $model = GeneratorUtils::setModelName($request['code']);
        
        $path = isset($request['path']) ? $request['path'] : $request['code'];

        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);
        $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($model);


        $controllerClass = "\n" . "Route::get('" . $path . "', [ App\Http\Controllers\Admin\\" . $modelNameSingularPascalCase . "Controller::class, 'index' ])->middleware('auth');\n";
        $controllerClass .= "\n" . "Route::get('create-less/" . $modelNamePluralLowercase . "', [ App\Http\Controllers\Admin\\" . $modelNameSingularPascalCase . "Controller::class, 'createLess' ])->middleware('auth');\n";
        $controllerClass .= "\n" . "Route::get('edit-less/" . $modelNamePluralLowercase . "/{id}', [ App\Http\Controllers\Admin\\" . $modelNameSingularPascalCase . "Controller::class, 'editLess' ])->middleware('auth');\n";
        $controllerClass .= "\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\Admin\\" . $modelNameSingularPascalCase . "Controller::class)->middleware('auth');";
        $controllerClass .= "\n" . "Route::post('assign/" . $modelNamePluralLowercase . "', [ App\Http\Controllers\Admin\\" . $modelNameSingularPascalCase . "Controller::class, 'assign' ])->middleware('auth');\n";



        File::append(base_path('routes/generator/generator.php'), $controllerClass);
    }

    /**
     * Method remove
     *
     * @param $id $id
     *
     * @return void
     */
    public function remove($id)
    {
        $crud = Crud::find($id);
        $model = GeneratorUtils::setModelName($crud->name, 'default');
        $path = GeneratorUtils::getModelLocation($crud->name);

        $modelNameSingularPascalCase = GeneratorUtils::singularPascalCase($model);
        $modelNamePluralLowercase = GeneratorUtils::pluralKebabCase($model);

        if ($path != '') {
            $controllerClass = "\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\Admin\\" . str_replace('/', '\\', $path) . "\\" . $modelNameSingularPascalCase . "Controller::class)->middleware('auth');";
        } else {
            $controllerClass = "\n" . "Route::resource('" . $modelNamePluralLowercase . "', App\Http\Controllers\Admin\\" . $modelNameSingularPascalCase . "Controller::class)->middleware('auth');";
        }

        File::replaceInFile($controllerClass, '', base_path('routes/web.php'));
    }
}
