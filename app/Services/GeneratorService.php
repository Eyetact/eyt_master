<?php

namespace App\Services;

use App\Generators\MigrationGenerator;
use App\Generators\ModelGenerator;
use App\Generators\Views\ActionViewGenerator;
use App\Generators\Views\CreateViewGenerator;
use App\Generators\Views\FormViewGenerator;
use App\Generators\Views\ShowViewGenerator;
use App\Generators\WebControllerGenerator;
use App\Generators\RequestGenerator;
use App\Generators\WebRouteGenerator;
use App\Generators\Views\IndexViewGenerator;
use App\Generators\Views\EditViewGenerator;
use App\Generators\ViewComposerGenerator;
use App\Generators\PermissionGenerator;





class GeneratorService
{
    /**
     * Generate Model.
     *
     * @param array $request
     * @return void
     */
    public function generateModel(array $request): void
    {
        (new ModelGenerator)->generate($request);
    }

    public function reGenerateModel($id): void
    {
        (new ModelGenerator)->reGenerate($id);
    }


    public function generateMigration(array $request,$id): void
    {
        (new MigrationGenerator)->generate($request,$id);
    }

    public function reGenerateMigration($id): void
    {
        (new MigrationGenerator)->reGenerate($id);
    }
    public function removeMigration($id, $attr_id): void
    {
        (new MigrationGenerator)->remove($id, $attr_id);
    }

    public function removeMigrationTable($id): void
    {
        (new MigrationGenerator)->removeTable($id,);
    }

    public function generateMultipleMigration($table_name,$id1, $id2): void
    {
        (new MigrationGenerator)->generateMultiple($table_name,$id1, $id2);
    }


    public function generateController(array $request): void
    {
        (new WebControllerGenerator)->generate($request);
    }

    public function reGenerateController($id): void
    {
        (new WebControllerGenerator)->reGenerate($id);
    }

    public function generateRequest(array $request): void
    {
        (new RequestGenerator)->generate($request);
    }

    public function reGenerateRequest($id): void
    {
        (new RequestGenerator)->reGenerate($id);
    }

    public function generateRoute(array $request): void
    {
        (new WebRouteGenerator)->generate($request);
    }


    public function generateViews(array $request): void
    {
        (new IndexViewGenerator)->generate($request);
        (new ActionViewGenerator)->generate($request);
        (new CreateViewGenerator)->generate($request);
        (new FormViewGenerator)->generate($request);
        (new ShowViewGenerator)->generate($request);
        (new EditViewGenerator)->generate($request);
        (new ViewComposerGenerator)->generate($request);
    }

    public function reGenerateViews($id): void
    {
        (new IndexViewGenerator)->reGenerate($id);
        (new CreateViewGenerator)->reGenerate($id);
        (new FormViewGenerator)->reGenerate($id);
        (new ActionViewGenerator)->reGenerate($id);


        (new ShowViewGenerator)->reGenerate($id);
        (new EditViewGenerator)->reGenerate($id);

        (new ViewComposerGenerator)->reGenerate($id);
    }

    public function reGenerateForm($id): void
    {
        (new IndexViewGenerator)->reGenerate($id);
        (new FormViewGenerator)->reGenerate($id);

    }

    public function reGenerateFormWithSub($id): void
    {
        (new FormViewGenerator)->reGenerateWithSub($id);

    }

    public function generatePermission(array $request,$id): void
    {
        (new PermissionGenerator)->generate($request,$id);
    }

    public function reGeneratePermission($id): void
    {
        (new PermissionGenerator)->regenerate($id);
    }

    public function generatePermissionForAttr(array $request,$id): void
    {
        (new PermissionGenerator)->generateForAttr($request,$id);
    }

}
