<?php

namespace App\Exports;

use App\Generators\GeneratorUtils;
use App\Models\Admin\Component;
use App\Models\Module;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    use Exportable;

    private $model_id;
    private $template;

    public function __construct($model_id, $template = false)
    {
        $this->model_id = $model_id;
        $this->template = $template;
    }
    public function collection()
    {
        $module = Module::find($this->model_id);
        if ($this->model_id < 6) {
            $modelName = "App\Models\\" . GeneratorUtils::setModelName($module->code);
        } else {

            $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($module->code);
        }

        if ($this->template) {

            return $modelName::where('id', 0)->get();
        }
        return $modelName::all();
    }
    public function headings(): array
    {
        $module = Module::find($this->model_id);
        if ($this->model_id < 6) {
            $modelName = "App\Models\\" . GeneratorUtils::setModelName($module->code);
        } else {

            $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($module->code);
        }

        $model = new $modelName();

        // $columns = $model->getTableColumns();
        $columns = $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
        return $columns;

    }
}
