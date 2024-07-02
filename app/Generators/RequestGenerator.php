<?php

namespace App\Generators;

use App\Models\Crud;
use App\Models\Module;
use File;

class RequestGenerator
{
    /**
     * Generate a request validation class file.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request): void
    {
        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $model = GeneratorUtils::setModelName($request['code']);
        $path = GeneratorUtils::getModelLocation($request['code']);

        $validations = '';
        $totalFields = !empty($request['fields']) ? count($request['fields']) : 0;

        switch ($path) {
            case '':
                $namespace = "namespace App\Http\Requests\Admin;";
                break;
            default:
                $namespace = "namespace App\Http\Requests\Admin\\$path;";
                break;
        }

        if (!empty($request['fields'][0])) {
            foreach ($request['fields'] as $i => $field) {
                /**
                 * will generate like:
                 * 'name' =>
                 */
                $validations .= "'" . str($field)->snake() . "' => ";

                /**
                 * will generate like:
                 * 'name' => 'required
                 */
                match ($request['requireds'][$i]) {
                    'yes' => $validations .= "'required",
                    default => $validations .= "'nullable"
                };

                switch ($request['input_types'][$i]) {
                    case 'url':
                        /**
                         * will generate like:
                         * 'name' => 'required|url',
                         */
                        $validations .= "|url";
                        break;
                    case 'email':
                        $uniqueValidation = 'unique:' . GeneratorUtils::pluralSnakeCase($model) . ',' . GeneratorUtils::singularSnakeCase($field);

                        /**
                         * will generate like:
                         * 'name' => 'required|email',
                         */
                        $validations .= "|email|" . $uniqueValidation;
                        break;
                    case 'date':
                        /**
                         * will generate like:
                         * 'name' => 'required|date',
                         */
                        $validations .= "|date";
                        break;
                    case 'password':
                        /**
                         * will generate like:
                         * 'name' => 'required|confirmed',
                         */
                        $validations .= "|confirmed";
                        break;
                    default:
                        # code...
                        break;
                }

                if ($request['input_types'][$i] == 'file' && $request['file_types'][$i] == 'image') {

                    $maxSize = 1024;
                    if (config('generator.image.size_max')) {
                        $maxSize = config('generator.image.size_max');
                    }

                    if ($request['files_sizes'][$i]) {
                        $maxSize = $request['files_sizes'][$i];
                    }

                    /**
                     * will generate like:
                     * 'cover' => 'required|image|size:1024',
                     */
                    $validations .= "|image|max:" . $maxSize;
                } elseif ($request['input_types'][$i] == 'file' && $request['file_types'][$i] == 'file') {
                    /**
                     * will generate like:
                     * 'name' => 'required|mimes|size:1024',
                     */




                    $validations .= "|mimes:" . $request['mimes'][$i] . "|size:" . $request['files_sizes'][$i];
                }

                if ($request['column_types'][$i] == 'enum') {
                    /**
                     * will generate like:
                     * 'name' => 'required|in:water,fire',
                     */
                    $in = "|in:";

                    $options = explode('|', $request['select_options'][$i]);

                    $totalOptions = count($options);

                    foreach ($options as $key => $option) {
                        if ($key + 1 != $totalOptions) {
                            $in .= $option . ",";
                        } else {
                            // for latest validation
                            $in .= $option;
                        }
                    }

                    $validations .= $in;
                }

                if ($request['input_types'][$i] == 'text' || $request['input_types'][$i] == 'textarea') {
                    /**
                     * will generate like:
                     * 'name' => 'required|string',
                     */
                    $validations .= "|string";
                }

                if ($request['input_types'][$i] == 'number' || $request['column_types'][$i] == 'year' || $request['input_types'][$i] == 'range') {
                    /**
                     * will generate like:
                     * 'name' => 'required|numeric',
                     */
                    $validations .= "|numeric";
                }

                if ($request['input_types'][$i] == 'range' && $request['max_lengths'][$i] >= 0) {
                    /**
                     * will generate like:
                     * 'name' => 'numeric|between:1,10',
                     */
                    $validations .= "|between:" . $request['min_lengths'][$i] . "," . $request['max_lengths'][$i];
                }

                if ($request['min_lengths'][$i] && $request['input_types'][$i] !== 'range') {
                    /**
                     * will generate like:
                     * 'name' => 'required|min:5',
                     */
                    $validations .= "|min:" . $request['min_lengths'][$i];
                }

                if ($request['max_lengths'][$i] && $request['max_lengths'][$i] >= 0 && $request['input_types'][$i] !== 'range') {
                    /**
                     * will generate like:
                     * 'name' => 'required|max:30',
                     */
                    $validations .= "|max:" . $request['max_lengths'][$i];
                }

                switch ($request['column_types'][$i]) {
                    case 'boolean':
                        /**
                         * will generate like:
                         * 'name' => 'required|boolean',
                         */
                        $validations .= "|boolean',";
                        break;
                    case 'foreignId':
                        case 'condition':
                        // remove '/' or sub folders

                        if (!isset($request['multiple'][$i])) {


                            $constrainModel = GeneratorUtils::setModelName($request['constrains'][$i]);
                            $constrainpath = GeneratorUtils::getModelLocation($request['constrains'][$i]);

                            switch ($constrainpath != '') {
                                case true:
                                    /**
                                     * will generate like:
                                     * 'name' => 'required|max:30|exists:App\Models\Master\Product,id',
                                     */
                                    $validations .= "|exists:App\Models\\" . str_replace('/', '\\', $constrainpath) . "\\" . GeneratorUtils::singularPascalCase($constrainModel) . ",id',";
                                    break;
                                default:
                                    /**
                                     * will generate like:
                                     * 'name' => 'required|max:30|exists:App\Models\Product,id',
                                     */
                                    $validations .= "|exists:App\Models\\" . GeneratorUtils::singularPascalCase($constrainModel) . ",id',";
                                    break;
                            }

                        }
                        break;
                    default:
                        /**
                         * will generate like:
                         * 'name' => 'required|max:30|exists:App\Models\Product,id',
                         */
                        $validations .= "',";
                        break;
                }

                if ($i + 1 != $totalFields) {
                    $validations .= "\n\t\t\t";
                }
            }
            // end of foreach
        }
        $storeRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}',
                '{{namespace}}',
            ],
            [
                "Store$model",
                $validations,
                $namespace,
            ],
            GeneratorUtils::getTemplate('request')
        );

        /**
         * on update request if any image validation, then set 'required' to nullbale
         */
        switch (str_contains($storeRequestTemplate, "required|image")) {
            case true:
                $updateValidations = str_replace("required|image", "nullable|image", $validations);
                break;
            default:
                $updateValidations = $validations;
                break;
        }

        if (isset($uniqueValidation)) {
            /**
             * Will generate something like:
             *
             * unique:users,email,' . $this->user->id
             */
            $updateValidations = str_replace($uniqueValidation, $uniqueValidation . ",' . \$this->" . GeneratorUtils::singularCamelCase($model) . "->id", $validations);

            // change ->id', to ->id,
            $updateValidations = str_replace("->id'", "->id", $updateValidations);
        }
        if (!empty($request['fields'][0])) {

            if (in_array('password', $request['input_types'])) {
                foreach ($request['input_types'] as $key => $input) {
                    if ($input == 'password' && $request['requireds'][$key] == 'yes') {
                        /**
                         * change:
                         * 'password' => 'required' to 'password' => 'nullable' in update request validation
                         */
                        $updateValidations = str_replace(
                            "'" . $request['fields'][$key] . "' => 'required",
                            "'" . $request['fields'][$key] . "' => 'nullable",
                            $updateValidations
                        );
                    }
                }
            }
        }

        $updateRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}',
                '{{namespace}}',
            ],
            [
                "Update$model",
                $updateValidations,
                $namespace,
            ],
            GeneratorUtils::getTemplate('request')
        );

        /**
         * Create a request class file.
         */
        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(app_path('/Http/Requests/Admin'));
                file_put_contents(app_path("/Http/Requests/Admin/Store{$model}Request.php"), $storeRequestTemplate);
                file_put_contents(app_path("/Http/Requests/Admin/Update{$model}Request.php"), $updateRequestTemplate);
                break;
            default:
                $fullPath = app_path("/Http/Requests/Admin/$path");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents("$fullPath/Store{$model}Request.php", $storeRequestTemplate);
                file_put_contents("$fullPath/Update{$model}Request.php", $updateRequestTemplate);
                break;
        }
    }

    public function reGenerate($id): void
    {
        $module = Module::find($id);
        $model = GeneratorUtils::setModelName($module->code);
        $path = GeneratorUtils::getModelLocation($module->code);

        $validations = '';
        $totalFields = count($module->fields()->where('is_enable', 1)->get());


        switch ($path) {
            case '':
                $namespace = "namespace App\Http\Requests\Admin;";
                break;
            default:
                $namespace = "namespace App\Http\Requests\Admin\\$path;";
                break;
        }

        foreach ($module->fields()->where('is_enable', 1)->get() as $i => $field) {
            $field->code = !empty($field->code) ? GeneratorUtils::singularSnakeCase($field->code) : GeneratorUtils::singularSnakeCase($field->name);

            if ($field->multiple <= 0):
                /**
                 * will generate like:
                 * 'name' =>
                 */

                if ($field->type == 'assign') {
                    $validations .= "'customer_id' => 'nullable' , ";
                    $validations .= "'customer_group_id' => 'nullable' , ";
                    // dd('aaa');

                } else {


                    $validations .= "'" . str($field->code)->snake() . "' => ";

                    /**
                     * will generate like:
                     * 'name' => 'required
                     */
                    if ($field->type != 'multi') {

                        if($field->type != 'boolean' &&  $field->type != 'doublefk'  &&  $field->fk_type != 'based')
                        {
                        match ($field->required) {
                            'yes' => $validations .= "'required",
                            'on' => $validations .= "'required",
                            default => $validations .= "'nullable"
                        };


                    }
                    if($field->type == 'doublefk'  ||  $field->fk_type == 'based')
                    {
                    match ($field->required) {
                        'yes' => $validations .= "'required',\n'" . str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id') . "' => 'required",
                        'on' => $validations .= "'required',\n'" . str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id') . "' => 'required",
                        default => $validations .= "'nullable',\n'" . str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id') . "' => 'nullable",
                    };



                }
                    if($field->type == 'boolean' ){

                        match ($field->required) {
                            'yes' => $validations .= "'nullable",
                            'on' => $validations .= "'nullable",
                            default => $validations .= "'nullable"
                        };

                    }

                    } else {
                        $validations .= "'nullable";
                    }


                    switch ($field->input) {
                        case 'url':
                            /**
                             * will generate like:
                             * 'name' => 'required|url',
                             */
                            $validations .= "|url";
                            break;
                        case 'email':
                            $uniqueValidation = 'unique:' . GeneratorUtils::pluralSnakeCase($model) . ',' . GeneratorUtils::singularSnakeCase($field->code);

                            /**
                             * will generate like:
                             * 'name' => 'required|email',
                             */
                            $validations .= "|email|" . $uniqueValidation;
                            break;
                        case 'date':
                            /**
                             * will generate like:
                             * 'name' => 'required|date',
                             */
                            $validations .= "|date";
                            break;
                        case 'password':
                            /**
                             * will generate like:
                             * 'name' => 'required|confirmed',
                             */
                            $validations .= "|confirmed";
                            break;
                        default:
                            # code...
                            break;
                    }

                    if ($field->input == 'image' && $field->file_type == 'image') {

                        $maxSize = 1024;
                        if (config('generator.image.size_max')) {
                            $maxSize = config('generator.image.size_max');
                        }

                        if ($field->max_size) {
                            $maxSize = $field->max_size;
                        }

                        /**
                         * will generate like:
                         * 'cover' => 'required|image|size:1024',
                         */
                        $validations .= "|image|max:" . $maxSize;
                    } elseif ($field->input == 'file' && $field->file_type == 'file') {
                        /**
                         * will generate like:
                         * 'name' => 'required|mimes|size:1024',
                         */
                        $validations .= "|max:" . $field->max_size;
                    }

                    if ($field->type == 'enum' || $field->primary == 'select') {
                        /**
                         * will generate like:
                         * 'name' => 'required|in:water,fire',
                         */
                        $in = "|in:";

                        $options = explode('|', $field->select_option);

                        $totalOptions = count($options);

                        foreach ($options as $key => $option) {
                            if ($key + 1 != $totalOptions) {
                                $in .= $option . ",";
                            } else {
                                // for latest validation
                                $in .= $option;
                            }
                        }

                        if ($field->is_multi) {
                            $in = '';
                        }

                        $validations .= $in;
                    }

                    if ($field->input == 'text' || $field->input == 'textarea' || $field->input == 'texteditor') {
                        /**
                         * will generate like:
                         * 'name' => 'required|string',
                         */
                        $validations .= "|string";
                    }

                    if ($field->input == 'number' || $field->input == 'decimal' || $field->type == 'year' || $field->input == 'range' || $field->primary == 'integer' || $field->primary == 'decimal') {
                        /**
                         * will generate like:
                         * 'name' => 'required|numeric',
                         */
                        $validations .= "|numeric";
                    }

                    if ($field->input == 'range' && $field->max_length >= 0) {
                        /**
                         * will generate like:
                         * 'name' => 'numeric|between:1,10',
                         */
                        $validations .= "|between:" . $field->min_length . "," . $field->max_length;
                    }

                    if ($field->min_length && $field->input !== 'range') {
                        /**
                         * will generate like:
                         * 'name' => 'required|min:5',
                         */
                        $validations .= "|min:" . $field->min_length;
                    }

                    if ($field->max_length && $field->max_length >= 0 && $field->input !== 'range') {
                        /**
                         * will generate like:
                         * 'name' => 'required|max:30',
                         */
                        $validations .= "|max:" . $field->max_length;
                    }

                    switch ($field->type) {
                        case 'boolean':
                            /**
                             * will generate like:
                             * 'name' => 'required|boolean',
                             */
                            $validations .= "|boolean',";
                            break;
                        case 'foreignId':
                        case 'condition':

                            // remove '/' or sub folders



                            $constrainModel = GeneratorUtils::setModelName($field->constrain);
                            $constrainpath = GeneratorUtils::getModelLocation($field->constrain);

                            switch ($constrainpath != '') {
                                case true:
                                    /**
                                     * will generate like:
                                     * 'name' => 'required|max:30|exists:App\Models\Master\Product,id',
                                     */
                                    $validations .= "|exists:App\Models\Admin\\" . str_replace('/', '\\', $constrainpath) . "\\" . GeneratorUtils::singularPascalCase($constrainModel) . ",id',";
                                    break;
                                default:
                                    /**
                                     * will generate like:
                                     * 'name' => 'required|max:30|exists:App\Models\Product,id',
                                     */
                                    $validations .= "|exists:App\Models\Admin\\" . GeneratorUtils::singularPascalCase($constrainModel) . ",id',";
                                    break;
                            }


                            break;
                        default:
                            /**
                             * will generate like:
                             * 'name' => 'required|max:30|exists:App\Models\Product,id',
                             */
                            $validations .= "',";
                            break;
                    }
                }

                if ($i + 1 != $totalFields) {
                    $validations .= "\n\t\t\t";
                }
            endif;

        }
        // end of foreach

        $storeRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}',
                '{{namespace}}',
            ],
            [
                "Store$model",
                $validations,
                $namespace,
            ],
            GeneratorUtils::getTemplate('request')
        );

        /**
         * on update request if any image validation, then set 'required' to nullbale
         */
        switch (str_contains($storeRequestTemplate, "required|image")) {
            case true:
                $updateValidations = str_replace("required|image", "nullable|image", $validations);
                break;
            default:
                $updateValidations = $validations;
                break;
        }

        if (isset($uniqueValidation)) {
            /**
             * Will generate something like:
             *
             * unique:users,email,' . $this->user->id
             */
            $updateValidations = str_replace($uniqueValidation, $uniqueValidation . ",' . \$this->" . GeneratorUtils::singularCamelCase($model) . "->id", $validations);

            // change ->id', to ->id,
            $updateValidations = str_replace("->id'", "->id", $updateValidations);
        }
        if (count($module->fields()->where('input', 'password')->get()) > 0) {
            foreach ($module->fields()->where('is_enable', 1)->get() as $key => $field) {
                if ($field->input == 'password' && ($field->required == 'yes' || $field->required == 'on')) {
                    /**
                     * change:
                     * 'password' => 'required' to 'password' => 'nullable' in update request validation
                     */
                    $updateValidations = str_replace(
                        "'" . $field->code . "' => 'required",
                        "'" . $field->code . "' => 'nullable",
                        $updateValidations
                    );
                }
            }
        }

        $updateRequestTemplate = str_replace(
            [
                '{{modelNamePascalCase}}',
                '{{fields}}',
                '{{namespace}}',
            ],
            [
                "Update$model",
                $updateValidations,
                $namespace,
            ],
            GeneratorUtils::getTemplate('request')
        );

        /**
         * Create a request class file.
         */
        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(app_path('/Http/Requests/Admin'));
                file_put_contents(app_path("/Http/Requests/Admin/Store{$model}Request.php"), $storeRequestTemplate);
                file_put_contents(app_path("/Http/Requests/Admin/Update{$model}Request.php"), $updateRequestTemplate);
                break;
            default:
                $fullPath = app_path("/Http/Requests/Admin/$path");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents("$fullPath/Store{$model}Request.php", $storeRequestTemplate);
                file_put_contents("$fullPath/Update{$model}Request.php", $updateRequestTemplate);
                break;
        }
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

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(app_path('/Http/Requests'));
                $storePath = app_path("/Http/Requests/Store{$model}Request.php");
                $updatePath = app_path("/Http/Requests/Update{$model}Request.php");
                File::delete($storePath);
                File::delete($updatePath);

                break;
            default:
                $fullPath = app_path("/Http/Requests/$path");
                $storePath = "$fullPath/Store{$model}Request.php";
                $updatePath = "$fullPath/Update{$model}Request.php";
                File::delete($storePath);
                File::delete($updatePath);
                break;
        }
    }
}
