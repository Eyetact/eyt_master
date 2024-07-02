<?php

namespace App\Console\Commands;

use App\Models\MenuManager;
use Illuminate\Console\Command;

class FixParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aya';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $menus= MenuManager::all();
        foreach($menus as $menu)
        {
            $parentMenu = MenuManager::find($menu->parent);

            if(!$parentMenu){

                $menu->parent = 0;
                $menu->save();
            }
            
        }
    }
}
