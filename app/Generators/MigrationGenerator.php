<?php

namespace App\Generators;

use App\Enums\ActionForeign;
use App\Models\Attribute;
use App\Models\Module;
use Illuminate\Support\Facades\Artisan;

class MigrationGenerator
{
    /**
     * Generate a migration file.
     *
     * @param array $request
     * @param int $id
     * @return void
     */
    public function generate(array $request, $id)
    {
        $request['code'] = str()->snake(str_replace(['.', '/', '\\', '-', ' ', '!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '+', '=', '<', '>', ',', '{', '}', '[', ']', ':', ';', '"', '\''], '', str($request['code'])->lower()));

        $model = GeneratorUtils::setModelName($request['code']);
        $tableNamePluralLowercase = GeneratorUtils::pluralSnakeCase($model);

        $setFields = '';
        $totalFields = !empty($request['fields']) ? count($request['fields']) : 0;

        if (!empty($request['fields'][0])) {

            foreach ($request['fields'] as $i => $field) {

                $setFields .= "\$table->" . $request['column_types'][$i] . "('" . str()->snake($field);

                if ($request['column_types'][$i] == 'enum') {
                    $options = explode('|', $request['select_options'][$i]);
                    $totalOptions = count($options);

                    $enum = "[";

                    foreach ($options as $key => $value) {
                        if ($key + 1 != $totalOptions) {
                            $enum .= "'$value', ";
                        } else {
                            $enum .= "'$value']";
                        }
                    }

                    $setFields .= "', " . $enum;
                }

                if (isset($request['max_lengths'][$i]) && $request['max_lengths'][$i] >= 0) {
                    if ($request['column_types'][$i] == 'enum') {

                        $setFields .= ")";
                    } else {

                        switch ($request['input_types'][$i]) {
                            case 'range':
                                $setFields .= "')";
                                break;
                            default:
                                $setFields .= "', " . $request['max_lengths'][$i] . ")";
                                break;
                        }
                    }
                } else {
                    if ($request['column_types'][$i] == 'enum') {

                        $setFields .= ")";
                    } else {

                        $setFields .= "')";
                    }
                }

                if ($request['requireds'][$i] != 'yes') {

                    $setFields .= "->nullable()";
                }

                if ($request['default_values'][$i]) {

                    $defaultValue = "'" . $request['default_values'][$i] . "'";

                    if ($request['input_types'][$i] == 'month') {
                        $defaultValue = "\Carbon\Carbon::createFromFormat('Y-m', '" . $request['default_values'][$i] . "')";
                    }

                    $setFields .= "->default($defaultValue)";
                }

                if ($request['input_types'][$i] === 'email') {

                    $setFields .= "->unique()";
                }

                $constrainName = '';
                if ($request['column_types'][$i] == 'foreignId') {
                    $constrainName = GeneratorUtils::setModelName($request['constrains'][$i]);
                }

                if ($i + 1 != $totalFields) {
                    if ($request['column_types'][$i] == 'foreignId') {
                        if ($request['on_delete_foreign'][$i] == ActionForeign::NULL->value) {
                            $setFields .= "->nullable()";
                        }

                        $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "')";

                        if ($request['on_update_foreign'][$i] == ActionForeign::CASCADE->value) {
                            $setFields .= "->cascadeOnUpdate()";
                        } elseif ($request['on_update_foreign'][$i] == ActionForeign::RESTRICT->value) {
                            $setFields .= "->restrictOnUpdate()";
                        }

                        if ($request['on_delete_foreign'][$i] == ActionForeign::CASCADE->value) {
                            $setFields .= "->cascadeOnDelete();\n\t\t\t";
                        } elseif ($request['on_delete_foreign'][$i] == ActionForeign::RESTRICT->value) {
                            $setFields .= "->restrictOnDelete();\n\t\t\t";
                        } elseif ($request['on_delete_foreign'][$i] == ActionForeign::NULL->value) {
                            $setFields .= "->nullOnDelete();\n\t\t\t";
                        } else {
                            $setFields .= ";\n\t\t\t";
                        }
                    } else {
                        $setFields .= ";\n\t\t\t";
                    }
                } else {
                    if ($request['column_types'][$i] == 'foreignId') {
                        $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "')";

                        if ($request['on_update_foreign'][$i] == ActionForeign::CASCADE->value) {
                            $setFields .= "->cascadeOnUpdate()";
                        } elseif ($request['on_update_foreign'][$i] == ActionForeign::RESTRICT->value) {
                            $setFields .= "->restrictOnUpdate()";
                        }

                        if ($request['on_delete_foreign'][$i] == ActionForeign::CASCADE->value) {
                            $setFields .= "->cascadeOnDelete();";
                        } elseif ($request['on_delete_foreign'][$i] == ActionForeign::RESTRICT->value) {
                            $setFields .= "->restrictOnDelete();";
                        } elseif ($request['on_delete_foreign'][$i] == ActionForeign::NULL->value) {
                            $setFields .= "->nullOnDelete();";
                        } else {
                            $setFields .= ";";
                        }
                    } else {
                        $setFields .= ";";
                    }
                }
            }
        }

        $template = str_replace(
            [
                '{{tableNamePluralLowecase}}',
                '{{fields}}',
            ],
            [
                $tableNamePluralLowercase,
                $setFields,
            ],
            GeneratorUtils::getTemplate('migration')
        );

        $migrationName = date('Y') . '_' . date('m') . '_' . date('d') . '_' . date('h') . date('i') . date('s') . '_create_' . $tableNamePluralLowercase . '_table.php';
        $module = Module::find($id);
        $module->migration = $migrationName;
        $module->save();
        GeneratorUtils::checkFolder(database_path('/migrations/Admin'));

        file_put_contents(database_path("/migrations/Admin/$migrationName"), $template);
    }

    public function reGenerate($id)
    {
        $module = Module::find($id);

        $model = GeneratorUtils::setModelName($module->code);
        $tableNamePluralLowercase = GeneratorUtils::pluralSnakeCase($model);

        $setFields = '';
        $setFields2 = '';
        $totalFields = count($module->fields);

        if( count($module->fields()->orderBy('created_at', 'desc')->take(1)->get()) <=0 ){
            return;
        }

        foreach ($module->fields()->orderBy('created_at', 'desc')->take(1)->get() as $i => $field) {
            $field->code = !empty($field->code) ? GeneratorUtils::singularSnakeCase($field->code) : GeneratorUtils::singularSnakeCase($field->name);

            if ($field->type == 'date' && $field->input == 'month') {
                $setFields .= "\$table->text('" . str()->snake($field->code);
            } elseif ($field->type == 'multi' && $field->input == 'multi') {
                $setFields .= "\$table->text('" . str()->snake($field->code);
            }
            elseif ($field->type == 'doubleattr' && $field->input == 'doubleattr') {

                if($field->primary == 'text' || $field->primary == 'select')
                {
                $setFields .= "\$table->text('" . str()->snake($field->code);
                }

                if($field->primary == 'integer' || $field->primary == 'decimal')
                {
                $setFields .= "\$table->double('" . str()->snake($field->code);
                }

                if($field->primary == 'lookup')
                {
                    $setFields .= "\$table->foreignId('" . str()->snake($field->code);
                }
            }
            elseif ($field->type == 'fk' && $field->input == 'fk') {

                if($field->fk_type == 'condition' || $field->fk_type == 'basic')
                {
                $setFields .= "\$table->text('" . str()->snake($field->code);
                }


                if($field->fk_type == 'based')
                {
                    $setFields .= "\$table->foreignId('" . str()->snake($field->code);
                    $setFields2 .= "\$table->foreignId('" . str()->snake($field->constrain2.'_'.$field->attribute2. '_id');

                }
            }
            elseif ($field->type == 'enum' && $field->input == 'select') {
                $setFields .= "\$table->text('" . str()->snake($field->code);
            }
            elseif ($field->type == 'enum' && $field->input == 'radioselect') {
                $setFields .= "\$table->text('" . str()->snake($field->code);
            }
            elseif ($field->type == 'condition' ) {
                $setFields .= "\$table->foreignId('" . str()->snake($field->code);
            }
            elseif ($field->type == 'informatic' ) {
                $setFields .= "\$table->foreignId('" . str()->snake($field->code);
            }
            elseif ($field->type == 'doublefk' ) {
                $setFields .= "\$table->foreignId('" . str()->snake($field->code);
                $setFields2 .= "\$table->foreignId('" . str()->snake($field->constrain2.'_'.$field->attribute2. '_id');
            } else {
                $setFields .= "\$table->" . $field->type . "('" . str()->snake($field->code);
            }

            if ($field->type == 'enum') {
                //     $options = explode('|', $field->select_option);
                //     $totalOptions = count($options);

                //     $enum = "[";

                //     foreach ($options as $key => $value) {
                //         if ($key + 1 != $totalOptions) {
                //             $enum .= "'$value', ";
                //         } else {
                //             $enum .= "'$value']";
                //         }
                //     }

                // $setFields .= "', " . $enum;
                $setFields .= "'";
            }

            if (isset($field->max_length) && $field->max_length >= 0) {
                if ($field->type == 'enum') {

                    $setFields .= ")";
                } else {

                    switch ($field->input) {
                        case 'range':
                            $setFields .= "')";
                            break;
                        default:
                            $setFields .= "', " . $field->max_length . ")";
                            if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){
                            $setFields2 .= "', " . $field->max_length . ")";
                            }
                            break;
                    }
                }
            } else {
                if ($field->type == 'enum') {

                    $setFields .= ")";
                } else {
                    if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){

                        $setFields2 .= "')";
                    }
                    $setFields .= "')";
                }
            }

            if ($field->required != 'on') {

                $setFields .= "->nullable()";
            }

            if ($field->default_value) {

                $defaultValue = "'" . $field->default_value . "'";

                if ($field->type == 'month') {
                    $defaultValue = "\Carbon\Carbon::createFromFormat('Y-m', '" . $field->default_value . "')";
                }

                $setFields .= "->default($defaultValue)";
            }

            if ($field->type === 'email') {

                $setFields .= "->unique()";
            }

            $constrainName = '';
            $constrainName2 = '';
            if ($field->type == 'foreignId' ||  $field->type == 'condition' ||  $field->type == 'doublefk' || $field->primary == 'lookup' ||  $field->type == 'fk') {
                $constrainName = GeneratorUtils::setModelName($field->constrain);
                if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){
                    $constrainName2 = GeneratorUtils::setModelName($field->constrain2);

                }
            }

            if ($i + 1 != $totalFields) {
                if ($field->type == 'foreignId' ||  $field->type == 'condition'||  $field->type == 'doublefk' || $field->primary == 'lookup' ||  $field->type == 'fk') {
                    if ($field->on_delete_foreign == ActionForeign::NULL->value) {
                        if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){

                            $setFields2 .= "->nullable()";

                        }
                        $setFields .= "->nullable()";
                    }
                    if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){

                        $setFields2 .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName2) . "')";

                    }

                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "')";

                    if ($field->on_update_foreign == ActionForeign::CASCADE->value) {
                        if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){

                            $setFields2 .= "->cascadeOnUpdate()";
                        }
                        $setFields .= "->cascadeOnUpdate()";
                    } elseif ($field->on_update_foreign == ActionForeign::RESTRICT->value) {
                        if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){

                            $setFields2 .= "->restrictOnUpdate()";
                        }

                        $setFields .= "->restrictOnUpdate()";
                    }

                    if ($field->on_delete_foreign == ActionForeign::CASCADE->value) {
                        $setFields .= "->cascadeOnDelete();\n\t\t\t";
                    } elseif ($field->on_delete_foreign == ActionForeign::RESTRICT->value) {
                        $setFields .= "->restrictOnDelete();\n\t\t\t";
                    } elseif ($field->on_delete_foreign == ActionForeign::NULL->value) {
                        $setFields .= "->nullOnDelete();\n\t\t\t";
                    } else {
                        $setFields .= ";\n\t\t\t";
                    }

                    if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){

                        if ($field->on_delete_foreign == ActionForeign::CASCADE->value) {
                            $setFields2 .= "->cascadeOnDelete();\n\t\t\t";
                        } elseif ($field->on_delete_foreign == ActionForeign::RESTRICT->value) {
                            $setFields2 .= "->restrictOnDelete();\n\t\t\t";
                        } elseif ($field->on_delete_foreign == ActionForeign::NULL->value) {
                            $setFields2 .= "->nullOnDelete();\n\t\t\t";
                        } else {
                            $setFields2 .= ";\n\t\t\t";
                        }
                    }
                } else {
                    $setFields .= ";\n\t\t\t";
                }
            } else {
                if ($field->type == 'foreignId' ||  $field->type == 'condition' ||  $field->type == 'doublefk' || $field->primary == 'lookup' ||  $field->type == 'fk') {
                    $setFields .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName) . "')";

                    if( $field->type == 'doublefk' ||  $field->fk_type == 'based'){
                        $setFields2 .= "->constrained('" . GeneratorUtils::pluralSnakeCase($constrainName2) . "')";


                        if ($field->on_update_foreign == ActionForeign::CASCADE->value) {
                            $setFields2 .= "->cascadeOnUpdate()";
                        } elseif ($field->on_update_foreign == ActionForeign::RESTRICT->value) {
                            $setFields2 .= "->restrictOnUpdate()";
                        }

                        if ($field->on_delete_foreign == ActionForeign::CASCADE->value) {
                            $setFields2 .= "->cascadeOnDelete();";
                        } elseif ($field->on_delete_foreign == ActionForeign::RESTRICT->value) {
                            $setFields2 .= "->restrictOnDelete();";
                        } elseif ($field->on_delete_foreign == ActionForeign::NULL->value) {
                            $setFields2 .= "->nullOnDelete();";
                        } else {
                            $setFields2 .= ";";
                        }

                    }

                    if ($field->on_update_foreign == ActionForeign::CASCADE->value) {
                        $setFields .= "->cascadeOnUpdate()";
                    } elseif ($field->on_update_foreign == ActionForeign::RESTRICT->value) {
                        $setFields .= "->restrictOnUpdate()";
                    }

                    if ($field->on_delete_foreign == ActionForeign::CASCADE->value) {
                        $setFields .= "->cascadeOnDelete();";
                    } elseif ($field->on_delete_foreign == ActionForeign::RESTRICT->value) {
                        $setFields .= "->restrictOnDelete();";
                    } elseif ($field->on_delete_foreign == ActionForeign::NULL->value) {
                        $setFields .= "->nullOnDelete();";
                    } else {
                        $setFields .= ";";
                    }
                } else {
                    $setFields .= ";";
                }
            }

        }

        $setFields .= $setFields2;

        if ($field->type == 'assign') {
            $template = str_replace(
                [
                    '{{tableNamePluralLowecase}}',
                    '{{fields}}',
                    '{{fieldName}}'
                ],
                [
                    $tableNamePluralLowercase,
                    $setFields,
                    str()->snake($field->code)
                ],
                GeneratorUtils::getTemplate('migration-assign')
            );
        } else {
            $template = str_replace(
                [
                    '{{tableNamePluralLowecase}}',
                    '{{fields}}',
                    '{{fieldName}}'
                ],
                [
                    $tableNamePluralLowercase,
                    $setFields,
                    str()->snake($field->code)
                ],
                GeneratorUtils::getTemplate('migration-edit')
            );
        }



        $migrationName = date('Y') . '_' . date('m') . '_' . date('d') . '_' . date('h') . date('i') . date('s') . '_edit_' . $tableNamePluralLowercase . '_table.php';
        // $module = Module::find($id);
        // $migrationName = $module->migration ;
        GeneratorUtils::checkFolder(database_path('/migrations/Admin'));

        file_put_contents(database_path("/migrations/Admin/$migrationName"), $template);


    }

    public function remove($id, $attr_id)
    {
        $module = Module::find($id);

        $field = Attribute::find($attr_id);



        $model = GeneratorUtils::setModelName($module->code);
        $tableNamePluralLowercase = GeneratorUtils::pluralSnakeCase($model);

        $setFields = '';
        $setFields .= "DB::statement('SET FOREIGN_KEY_CHECKS=0;');\n";
        if ($field->type == 'foreignId' ||  $field->type == 'condition' ||  $field->type == 'informatic' ||  $field->primary == 'lookup' ||  $field->type == 'fk') {
            $setFields .= "\$table->dropForeign('" . $tableNamePluralLowercase . '_' . GeneratorUtils::singularSnakeCase(str()->snake($field->code)) . "_foreign');\n";

        }


        if ($field->type == 'doublefk' ||  $field->fk_type == 'based' ) {
            $setFields .= "if (Schema::hasColumn('$tableNamePluralLowercase', '" . $tableNamePluralLowercase . '_' . GeneratorUtils::singularSnakeCase(str()->snake($field->code)) . "_foreign')) {\n";
            $setFields .= "\$table->dropForeign('" . $tableNamePluralLowercase . '_' . GeneratorUtils::singularSnakeCase(str()->snake($field->code)) . "_foreign');\n";
            $setFields .= "}\n";
            $setFields .= "if (Schema::hasColumn('$tableNamePluralLowercase', '" . $tableNamePluralLowercase . '_' . GeneratorUtils::singularSnakeCase(str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id')) . "_foreign')) {\n";
            $setFields .= "\$table->dropForeign('" . $tableNamePluralLowercase . '_' . GeneratorUtils::singularSnakeCase(str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id')) . "_foreign');\n";
            $setFields .= "}\n";
        }

        // ithink its good lets test againe..ok



        $setFields .= "\$table->dropColumn('" . GeneratorUtils::singularSnakeCase(str()->snake($field->code)) . "');\n";

        if ($field->type == 'doublefk' ||  $field->fk_type == 'based' ) {
            $setFields .= "\$table->dropColumn('" . GeneratorUtils::singularSnakeCase(str()->snake($field->constrain2 . '_' . $field->attribute2 . '_id')) . "');\n";

        }

        $setFields .= "DB::statement('SET FOREIGN_KEY_CHECKS=1;');\n";



        if ($field->type == 'assign') {
            $template = str_replace(
                [
                    '{{tableNamePluralLowecase}}',
                    '{{fields}}',
                    '{{fieldName}}'
                ],
                [
                    $tableNamePluralLowercase,
                    $setFields,
                    GeneratorUtils::singularSnakeCase(str()->snake($field->code))
                ],
                GeneratorUtils::getTemplate('migration-remove-assign')
            );
        } else {
            $template = str_replace(
                [
                    '{{tableNamePluralLowecase}}',
                    '{{fields}}',
                    '{{fieldName}}'
                ],
                [
                    $tableNamePluralLowercase,
                    $setFields,
                    GeneratorUtils::singularSnakeCase(str()->snake($field->code))
                ],
                GeneratorUtils::getTemplate('migration-remove')
            );
        }


        $migrationName = date('Y') . '_' . date('m') . '_' . date('d') . '_' . date('h') . date('i') . date('s') . '_remove_' . $tableNamePluralLowercase . '_table.php';
        // $module = Module::find($id);
        // $migrationName = $module->migration ;

        file_put_contents(database_path("/migrations/Admin/$migrationName"), $template);

        // Artisan::call("migrate");

    }

    public function removeTable($id)
    {
        $module = Module::find($id);

        $model = GeneratorUtils::setModelName($module->code);
        $tableNamePluralLowercase = GeneratorUtils::pluralSnakeCase($model);


        $template = str_replace(
            [
                '{{tableNamePluralLowecase}}',
            ],
            [
                $tableNamePluralLowercase,
            ],
            GeneratorUtils::getTemplate('migration-remove-table')
        );



        $migrationName = date('Y') . '_' . date('m') . '_' . date('d') . '_' . date('h') . date('i') . date('s') . '_remove_' . $tableNamePluralLowercase . '_table.php';
        // $module = Module::find($id);
        // $migrationName = $module->migration ;

        file_put_contents(database_path("/migrations/Admin/$migrationName"), $template);

        Artisan::call("migrate");

    }


    public function generateMultiple($tableNamePluralLowecase,$id1,$id2)
    {


            $template = str_replace(
                [
                    '{{tableNamePluralLowecase}}',
                    '{{id1}}',
                    '{{id2}}'
                ],
                [
                    $tableNamePluralLowecase,
                    $id1,
                    $id2

                ],
                GeneratorUtils::getTemplate('migration-multible')
            );



        $migrationName = date('Y') . '_' . date('m') . '_' . date('d') . '_' . date('h') . date('i') . date('s') . '_multiple_' . $tableNamePluralLowecase . '_table.php';
        // $module = Module::find($id);
        // $migrationName = $module->migration ;

        file_put_contents(database_path("/migrations/Admin/$migrationName"), $template);

        Artisan::call("migrate");

    }
}
