<?php

namespace App\Imports;

use App\Generators\GeneratorUtils;
use App\Models\Module;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class UsersImport implements ToModel, WithStartRow
{

    private $model_id;

    public function __construct($model_id)
    {
        $this->model_id = $model_id;
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        $module = Module::find($this->model_id);
        if ($this->model_id< 6) {
            $modelName = "App\Models\\" . GeneratorUtils::setModelName($module->code);
        } else {

            $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($module->code);
        }

        $model = new $modelName();

        // $columns = $model->getTableColumns();
        $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());

        $ar = array();
        $i = 0;
        $user= new  $modelName();
        foreach ($columns as $value) {
            if($value != 'created_at' && $value != 'updated_at'){

                $user->$value = $row[$i];
            }
            $i++;
        }
        $user->save();

        return $user;
    }
    public function startRow(): int
    {
        //1 for heading
        return 2;
    }
}
