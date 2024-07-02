<?php

namespace App\Generators;

use App\Models\Module;

class ModelGenerator
{
    /**
     * Generate a model file.
     *
     * @param array $request
     * @return void
     */
    public function generate(array $request)
    {
        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $path = GeneratorUtils::getModelLocation($request['name']);
        $model = GeneratorUtils::setModelName($request['name']);

        $modelName = GeneratorUtils::setModelName($request['code']);

        $fields = "[";
        $casts = "[";
        $relations = "";
        $methods = "";
        $totalFields = !empty($request['fields']) ? count($request['fields']) : 0;
        $dateTimeFormat = config('generator.format.datetime') ? config('generator.format.datetime') : 'd/m/Y H:i';
        $protectedHidden = "";
        if (!empty($request['fields'][0])) {
            if (in_array('password', $request['input_types'])) {
                $protectedHidden .= <<<PHP
            /**
                 * The attributes that should be hidden for serialization.
                 *
                 * @var string[]
                */
                protected \$hidden = [
            PHP;
            }
        }

        switch ($path) {
            case '':
                $namespace = "namespace App\\Models\Admin;";
                break;
            default:
                $namespace = "namespace App\\Models\Admin\\$path;";
                break;
        }

        $fields .= "'customer_id', 'customer_group_id'  ";
        if (!empty($request['fields'][0])) {


            foreach ($request['fields'] as $i => $field) {

                switch ($i + 1 != $totalFields) {
                    case true:
                        $fields .= "'" . str()->snake($field) . "', ";
                        break;
                    default:
                        $fields .= "'" . str()->snake($field) . "'";
                        break;
                }

                if ($request['input_types'][$i] == 'password') {
                    $protectedHidden .= "'" . str()->snake($field) . "', ";

                    if ($i > 0) {
                        $methods .= "\t";
                    }

                    $fieldNameSingularPascalCase = GeneratorUtils::singularPascalCase($field);

                    $methods .= "\n\tpublic function set" . $fieldNameSingularPascalCase . "Attribute(\$value)\n\t{\n\t\t\$this->attributes['" . $field . "'] = bcrypt(\$value);\n\t}";
                }

                if ($request['input_types'][$i] == 'file') {

                    if ($i > 0) {
                        $methods .= "\t";
                    }

                    $fieldNameSingularPascalCase = GeneratorUtils::singularPascalCase($field);


                    $methods .= "\n\tpublic function set" . $fieldNameSingularPascalCase . "Attribute(\$value)\n\t{\n\t\tif (\$value){\n\t\t\t\$file = \$value;\n\t\t\t\$extension = \$file->getClientOriginalExtension(); // getting image extension\n\t\t\t\$filename =time().mt_rand(1000,9999).'.'.\$extension;\n\t\t\t\$file->move(public_path('files/'), \$filename);\n\t\t\t\$this->attributes['" . $field . "'] =  'files/'.\$filename;\n\t\t}\n\t}";
                }

                switch ($request['column_types'][$i]) {
                    case 'date':
                        if ($request['input_types'][$i] != 'month') {
                            $dateFormat = config('generator.format.date') ? config('generator.format.date') : 'd/m/Y';
                            $casts .= "'" . str()->snake($field) . "' => 'date:$dateFormat', ";
                        }
                        break;
                    case 'time':
                        $timeFormat = config('generator.format.time') ? config('generator.format.time') : 'H:i';
                        $casts .= "'" . str()->snake($field) . "' => 'datetime:$timeFormat', ";
                        break;
                    case 'year':
                        $casts .= "'" . str()->snake($field) . "' => 'integer', ";
                        break;
                    case 'dateTime':
                        $casts .= "'" . str()->snake($field) . "' => 'datetime:$dateTimeFormat', ";
                        break;
                    case 'float':
                        $casts .= "'" . str()->snake($field) . "' => 'float', ";
                        break;
                    case 'boolean':
                        $casts .= "'" . str()->snake($field) . "' => 'boolean', ";
                        break;
                    case 'double':
                        $casts .= "'" . str()->snake($field) . "' => 'double', ";
                        break;
                    case 'foreignId':
                    case 'condition':

                        $constrainPath = GeneratorUtils::getModelLocation($request['constrains'][$i]);
                        $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);

                        $foreign_id = isset($request['foreign_ids'][$i]) ? ", '" . $request['foreign_ids'][$i] . "'" : '';

                        if ($i > 0) {
                            $relations .= "\t";
                        }

                        if ($constrainPath != '') {
                            $constrainPath = "\\App\\Models\\$constrainPath\\$constrainName";
                        } else {
                            $constrainPath = "\\App\\Models\\$constrainName";
                        }

                        $relations .= "\n\tpublic function " . str()->snake($constrainName) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

                        break;
                }



                switch ($request['input_types'][$i]) {
                    case 'month':
                        $castFormat = config('generator.format.month') ? config('generator.format.month') : 'm/Y';
                        $casts .= "'" . str()->snake($field) . "' => 'date:$castFormat', ";
                        break;
                    case 'week':
                        $casts .= "'" . str()->snake($field) . "' => 'date:Y-\WW', ";
                        break;
                }

                if (str_contains($request['column_types'][$i], 'integer')) {
                    $casts .= "'" . str()->snake($field) . "' => 'integer', ";
                }

                if (
                    str_contains($request['column_types'][$i], 'string') ||
                    str_contains($request['column_types'][$i], 'text') ||
                    str_contains($request['column_types'][$i], 'char')
                ) {
                    if ($request['input_types'][$i] != 'week') {
                        $casts .= "'" . str()->snake($field) . "' => 'string', ";
                    }
                }
            }
        }
        $relations .= "\n\tpublic function customer()\n\t{\n\t\treturn \$this->belongsTo(\\App\\Models\User::class , \"customer_id\");\n\t}";
            $relations .= "\n\tpublic function group()\n\t{\n\t\treturn \$this->belongsTo(\\App\\Models\CustomerGroup::class , \"customer_group_id\");\n\t}";


        $fields .= "]";


        if ($protectedHidden != "") {
            $protectedHidden = substr($protectedHidden, 0, -2);
            $protectedHidden .= "];";
        }

        $casts .= <<<PHP
        'created_at' => 'datetime:$dateTimeFormat', 'updated_at' => 'datetime:$dateTimeFormat']
        PHP;

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}',
                '{{casts}}',
                '{{relations}}',
                '{{namespace}}',
                '{{protectedHidden}}',
                '{{methods}}'
            ],
            [
                $modelName,
                $fields,
                $casts,
                $relations,
                $namespace,
                $protectedHidden,
                $methods
            ],
            GeneratorUtils::getTemplate('model')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(app_path('/Models/Admin'));

                file_put_contents(app_path("/Models/Admin/$modelName.php"), $template);
                break;
            default:
                $fullPath = app_path("/Models/Admin/$path");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/$modelName.php", $template);
                break;
        }
    }


    public function reGenerate($id)
    {
        $module = Module::find($id);
        $path = GeneratorUtils::getModelLocation($module->name);
        $model = GeneratorUtils::setModelName($module->name);
        $modelName = GeneratorUtils::setModelName($module->code);

        $fields = "[";
        $casts = "[";
        $relations = "";
        $methods = "";
        $totalFields = count($module->fields()->where('is_enable', 1)->get());
        $dateTimeFormat = config('generator.format.datetime') ? config('generator.format.datetime') : 'd/m/Y H:i';
        $protectedHidden = "";


        if (count($module->fields()->where('input', 'password')->get()) > 0) {
            $protectedHidden .= <<<PHP
            /**
                 * The attributes that should be hidden for serialization.
                 *
                 * @var string[]
                */
                protected \$hidden = [
            PHP;
        }


        switch ($path) {
            case '':
                $namespace = "namespace App\\Models\Admin;";
                break;
            default:
                $namespace = "namespace App\\Models\Admin\\$path;";
                break;
        }


        $fields = "[ 'customer_id', 'customer_group_id' ";
        if(count($module->fields()->where('is_enable', 1)->get())){
            $fields = "[ 'customer_id', 'customer_group_id' ,";

        }

        foreach ($module->fields()->where('is_enable', 1)->get() as $i => $field) {
            $field->code = !empty($field->code) ? GeneratorUtils::singularSnakeCase($field->code) : GeneratorUtils::singularSnakeCase($field->name);
        // $fields = "[ 'customer_id', 'customer_group_id', ";

            if ($field->type == 'lknlknlnlk') {
                switch ($i + 1 != $totalFields) {
                    case true:
                        $fields .= "'customer_id', 'customer_group_id' , ";
                        break;
                    default:
                        $fields .= "'customer_id', 'customer_group_id'";
                        break;
                }
            } else {

                if($field->type == 'doublefk' || $field->fk_type == 'based'){
                    switch ($i + 1 != $totalFields) {

                        case true:
                            $fields .= "'" . str()->snake($field->code) . "', '" . str()->snake($field->constrain2.'_'.$field->attribute2. '_id') . "', " ;
                            break;
                        default:
                            $fields .= "'" . str()->snake($field->code) . "', '" . str()->snake($field->constrain2.'_'.$field->attribute2. '_id')  . "'";
                            break;
                    }
                }else{
                    switch ($i + 1 != $totalFields) {

                        case true:
                            $fields .= "'" . str()->snake($field->code) . "', ";
                            break;
                        default:
                            $fields .= "'" . str()->snake($field->code) . "'";
                            break;
                    }
                }

            }

            if ($field->input == 'password') {
                $protectedHidden .= "'" . str()->snake($field->code) . "', ";

                if ($i > 0) {
                    $methods .= "\t";
                }

                $fieldNameSingularPascalCase = GeneratorUtils::pluralPascalCase($field->code);

                $methods .= "\n\tpublic function set" . $fieldNameSingularPascalCase . "Attribute(\$value)\n\t{\n\t\t\$this->attributes['" . $field->code . "'] = bcrypt(\$value);\n\t}";
            }

            if ($field->input == 'multi' || ($field->input == 'select' && $field->is_multi)) {

                if ($i > 0) {
                    $methods .= "\t";
                }

                $fieldNameSingularPascalCase = GeneratorUtils::singularPascalCase($field->code);

                $methods .= "\n\tpublic function set" . $fieldNameSingularPascalCase . "Attribute(\$value)\n\t{\n\t\tif(\$value){\$this->attributes['" . $field->code . "'] = json_encode(\$value,true);}else{ \$this->attributes['" . $field->name . "'] = null; }\n\t}";
            }

            if ($field->input == 'file' || $field->input == 'image') {

                if ($i > 0) {
                    $methods .= "\t";
                }

                $fieldNameSingularPascalCase = GeneratorUtils::singularPascalCase($field->code);


                $methods .= "\n\tpublic function set" . $fieldNameSingularPascalCase . "Attribute(\$value)\n\t{\n\t\tif (\$value){\n\t\t\t\$file = \$value;\n\t\t\t\$extension = \$file->getClientOriginalExtension(); // getting image extension\n\t\t\t\$filename =time().mt_rand(1000,9999).'.'.\$extension;\n\t\t\t\$file->move(public_path('files/'), \$filename);\n\t\t\t\$this->attributes['" . $field->code . "'] =  'files/'.\$filename;\n\t\t}\n\t}";
            }

            switch ($field->type) {
                case 'date':
                    if ($field->input != 'month') {
                        $dateFormat = config('generator.format.date') ? config('generator.format.date') : 'd/m/Y';
                        $casts .= "'" . str()->snake($field->code) . "' => 'date:$dateFormat', ";
                    }
                    break;
                case 'time':
                    $timeFormat = config('generator.format.time') ? config('generator.format.time') : 'H:i';
                    $casts .= "'" . str()->snake($field->code) . "' => 'datetime:$timeFormat', ";
                    break;
                case 'year':
                    $casts .= "'" . str()->snake($field->code) . "' => 'integer', ";
                    break;
                case 'dateTime':
                    $casts .= "'" . str()->snake($field->code) . "' => 'datetime:$dateTimeFormat', ";
                    break;
                case 'float':
                    $casts .= "'" . str()->snake($field->code) . "' => 'float', ";
                    break;
                case 'boolean':
                    $casts .= "'" . str()->snake($field->code) . "' => 'boolean', ";
                    break;
                case 'double':
                    $casts .= "'" . str()->snake($field->code) . "' => 'double', ";
                    break;
                case 'foreignId':
                case 'condition':
                case 'informatic':
                    $constrainPath = GeneratorUtils::getModelLocation($field->constrain);
                    $constrainName = GeneratorUtils::setModelName($field->constrain);

                    $foreign_id = ' ,"' .$field->code . '"';

                    if ($i > 0) {
                        $relations .= "\t";
                    }

                    if ($constrainPath != '') {
                        $constrainPath = "\\App\\Models\Admin\\$constrainPath\\$constrainName";
                    } else {
                        $constrainPath = "\\App\\Models\Admin\\$constrainName";
                    }

                    if($field->multiple)
                    {

                        $model1=GeneratorUtils::singularSnakeCase(Module::find($field->module)->name);

                        $model2=GeneratorUtils::singularSnakeCase($field->constrain);

                        $table_name= $model1 . "_" . $model2;
                        $id1=$model1 . "_id";
                        $id2=$model2 . "_id";



                        $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsToMany(" . $constrainPath . "::class" . ",'" . $table_name . "','" . $id1 . "','" . $id2 .    "');\n\t}";
                    }
                    else{

                    $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

                    }

                    break;



                        case 'fk':
                            if($field->fk_type == 'basic' || $field->fk_type == 'condition')
                            {
                            $constrainPath = GeneratorUtils::getModelLocation($field->constrain);
                            $constrainName = GeneratorUtils::setModelName($field->constrain);

                            $foreign_id = ' ,"' .$field->code . '"';

                            if ($i > 0) {
                                $relations .= "\t";
                            }

                            if ($constrainPath != '') {
                                $constrainPath = "\\App\\Models\Admin\\$constrainPath\\$constrainName";
                            } else {
                                $constrainPath = "\\App\\Models\Admin\\$constrainName";
                            }

                            if($field->multiple)
                            {

                                $model1=GeneratorUtils::singularSnakeCase(Module::find($field->module)->name);

                                $model2=GeneratorUtils::singularSnakeCase($field->constrain);

                                $table_name= $model1 . "_" . $model2;
                                $id1=$model1 . "_id";
                                $id2=$model2 . "_id";



                                $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsToMany(" . $constrainPath . "::class" . ",'" . $table_name . "','" . $id1 . "','" . $id2 .    "');\n\t}";
                            }
                            else{

                            $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

                            }
                        }

                        if( $field->fk_type == 'based')
                        {



                        // $fields.=" '" . . "'";
                        $constrainPath = GeneratorUtils::getModelLocation($field->constrain);
                        $constrainName = GeneratorUtils::setModelName($field->constrain);

                        $constrainPath2 = GeneratorUtils::getModelLocation($field->constrain2);
                        $constrainName2 = GeneratorUtils::setModelName($field->constrain2);

                        $foreign_id = ' ,"' .$field->code . '"';
                        $foreign_id2 = ' ,"' . str()->snake($field->constrain2.'_'.$field->attribute2. '_id') . '"';

                        if ($i > 0) {
                            $relations .= "\t";
                        }

                        if ($constrainPath != '') {
                            $constrainPath = "\\App\\Models\Admin\\$constrainPath\\$constrainName";
                            $constrainPath2 = "\\App\\Models\Admin\\$constrainPath2\\$constrainName2";
                        } else {
                            $constrainPath = "\\App\\Models\Admin\\$constrainName";
                            $constrainPath2 = "\\App\\Models\Admin\\$constrainName2";
                        }


                    if($field->multiple)
                    {

                        $model1=GeneratorUtils::singularSnakeCase(Module::find($field->module)->name);

                        $model2=GeneratorUtils::singularSnakeCase($field->constrain);

                        $table_name= $model1 . "_" . $model2;
                        $id1=$model1 . "_id";
                        $id2=$model2 . "_id";



                        $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsToMany(" . $constrainPath . "::class" . ",'" . $table_name . "','" . $id1 . "','" . $id2 .    "');\n\t}";
                    }
                    else{

                    $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";
                    $relations .= "\n\tpublic function " . str()->snake($constrainName2). "_" .str()->snake($field->attribute2) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath2 . "::class" . $foreign_id2 . ");\n\t}";

                    }

                        }

                            break;


                    case 'doubleattr':
                        if($field->primary == 'lookup'){


                        $constrainPath = GeneratorUtils::getModelLocation($field->constrain);
                        $constrainName = GeneratorUtils::setModelName($field->constrain);

                        $foreign_id = ' ,"' .$field->code . '"';

                        if ($i > 0) {
                            $relations .= "\t";
                        }

                        if ($constrainPath != '') {
                            $constrainPath = "\\App\\Models\Admin\\$constrainPath\\$constrainName";
                        } else {
                            $constrainPath = "\\App\\Models\Admin\\$constrainName";
                        }

                        if($field->multiple)
                        {

                            $model1=GeneratorUtils::singularSnakeCase(Module::find($field->module)->name);

                            $model2=GeneratorUtils::singularSnakeCase($field->constrain);

                            $table_name= $model1 . "_" . $model2;
                            $id1=$model1 . "_id";
                            $id2=$model2 . "_id";



                            $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsToMany(" . $constrainPath . "::class" . ",'" . $table_name . "','" . $id1 . "','" . $id2 .    "');\n\t}";
                        }
                        else{

                        $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";

                        }

                    }
                        break;




                    case 'doublefk':

                        // $fields.=" '" . . "'";
                        $constrainPath = GeneratorUtils::getModelLocation($field->constrain);
                        $constrainName = GeneratorUtils::setModelName($field->constrain);

                        $constrainPath2 = GeneratorUtils::getModelLocation($field->constrain2);
                        $constrainName2 = GeneratorUtils::setModelName($field->constrain2);

                        $foreign_id = ' ,"' .$field->code . '"';
                        $foreign_id2 = ' ,"' . str()->snake($field->constrain2.'_'.$field->attribute2. '_id') . '"';

                        if ($i > 0) {
                            $relations .= "\t";
                        }

                        if ($constrainPath != '') {
                            $constrainPath = "\\App\\Models\Admin\\$constrainPath\\$constrainName";
                            $constrainPath2 = "\\App\\Models\Admin\\$constrainPath2\\$constrainName2";
                        } else {
                            $constrainPath = "\\App\\Models\Admin\\$constrainName";
                            $constrainPath2 = "\\App\\Models\Admin\\$constrainName2";
                        }


                    if($field->multiple)
                    {

                        $model1=GeneratorUtils::singularSnakeCase(Module::find($field->module)->name);

                        $model2=GeneratorUtils::singularSnakeCase($field->constrain);

                        $table_name= $model1 . "_" . $model2;
                        $id1=$model1 . "_id";
                        $id2=$model2 . "_id";



                        $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsToMany(" . $constrainPath . "::class" . ",'" . $table_name . "','" . $id1 . "','" . $id2 .    "');\n\t}";
                    }
                    else{

                    $relations .= "\n\tpublic function " . str()->snake($constrainName). "_" .str()->snake($field->attribute) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath . "::class" . $foreign_id . ");\n\t}";
                    $relations .= "\n\tpublic function " . str()->snake($constrainName2). "_" .str()->snake($field->attribute2) . "()\n\t{\n\t\treturn \$this->belongsTo(" . $constrainPath2 . "::class" . $foreign_id2 . ");\n\t}";

                    }

                    break;



                case 'assign':

                    break;

            }



            switch ($field->input) {
                case 'month':
                    $castFormat = config('generator.format.month') ? config('generator.format.month') : 'm/Y';
                    $casts .= "'" . str()->snake($field->code) . "' => 'date:$castFormat', ";
                    break;
                case 'week':
                    $casts .= "'" . str()->snake($field->code) . "' => 'date:Y-\WW', ";
                    break;
            }

            if (str_contains($field->type, 'integer')) {
                $casts .= "'" . str()->snake($field->code) . "' => 'integer', ";
            }

            if (
                str_contains($field->type, 'string') ||
                str_contains($field->type, 'text') ||
                str_contains($field->type, 'char')
            ) {
                if ($field->input != 'week') {
                    $casts .= "'" . str()->snake($field->code) . "' => 'string', ";
                }
            }


            if (
                str_contains($field->type, 'doubleattr')
            ) {
                if ($field->primary == 'text') {
                    $casts .= "'" . str()->snake($field->code) . "' => 'string', ";
                }

                if ($field->primary == 'integer' || $field->primary == 'decimal') {

                $casts .= "'" . str()->snake($field->code) . "' => 'double', ";

                }
            }
        }


        $fields .= "]";

        $relations .= "\n\tpublic function customer()\n\t{\n\t\treturn \$this->belongsTo(\\App\\Models\User::class , \"customer_id\");\n\t}";
            $relations .= "\n\tpublic function group()\n\t{\n\t\treturn \$this->belongsTo(\\App\\Models\CustomerGroup::class , \"customer_group_id\");\n\t}";



        if ($protectedHidden != "") {
            $protectedHidden = substr($protectedHidden, 0, -2);
            $protectedHidden .= "];";
        }

        $casts .= <<<PHP
        'created_at' => 'datetime:$dateTimeFormat', 'updated_at' => 'datetime:$dateTimeFormat']
        PHP;

        $template = str_replace(
            [
                '{{modelName}}',
                '{{fields}}',
                '{{casts}}',
                '{{relations}}',
                '{{namespace}}',
                '{{protectedHidden}}',
                '{{methods}}'
            ],
            [
                $modelName,
                $fields,
                $casts,
                $relations,
                $namespace,
                $protectedHidden,
                $methods
            ],
            GeneratorUtils::getTemplate('model')
        );

        switch ($path) {
            case '':
                GeneratorUtils::checkFolder(app_path('/Models/Admin'));
                file_put_contents(app_path("/Models/Admin/$modelName.php"), $template);
                break;
            default:
                $fullPath = app_path("/Models/Admin/$path");
                GeneratorUtils::checkFolder($fullPath);
                file_put_contents($fullPath . "/$modelName.php", $template);
                break;
        }
    }
}
