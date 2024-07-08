<?php

namespace App\Providers\Generator;

use App\Models\CustomerGroup;
use App\Models\MenuManager;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {

            $menus = MenuManager::where('include_in_menu', 1)->where('parent', 0)->where('is_delete', 0)->orderBy('sequence', 'asc')->get();
            return $view->with('side_menus', $menus);
        });

        View::composer('admin.*', function ($view) {

            $roleNames = ['admin', 'vendor'];

            if (auth()->user()->hasRole('super')) {

                $customers = User::role(['admin', 'vendor'])->get();

                $customer_groups = CustomerGroup::all();

            } else {

                $userId = auth()->user()->id;
                $usersOfCustomers = User::role($roleNames)
                    ->where('user_id', $userId)
                    ->pluck('id');

                $customers = User::whereIn('id', $usersOfCustomers)

                    ->get();

                $ids = User::where('user_id', $userId)->pluck('id');

                $customer_groups = CustomerGroup::where('created_by', $userId)
                    ->orWhereIn('created_by', $ids)
                    ->get();
            }
            $view->with('customers', $customers);
            return $view->with('customer_groups', $customer_groups);
        });

        /*********************************/
        if (Schema::hasTable('categories')) {
            if (Schema::hasColumn('categories', 'classification_id')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_categories',
                        \App\Models\Admin\Category::all()
                    );
                });
            }
        }


        if (Schema::hasTable('components')) {
            if (Schema::hasColumn('components', 'compo_name')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_components',
                        \App\Models\Admin\Component::all()
                    );
                });
            }
        }


        if (Schema::hasTable('components_sets')) {
            if (Schema::hasColumn('components_sets', 'set_components')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_componentsSets',
                        \App\Models\Admin\ComponentsSet::all()
                    );
                });
            }
        }


        if (Schema::hasTable('pumps')) {
            if (Schema::hasColumn('pumps', 'pump_flowrate')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_pumps',
                        \App\Models\Admin\Pump::all()
                    );
                });
            }
        }

        if (Schema::hasTable('elements')) {
            if (Schema::hasColumn('elements', 'element_name')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_elements',
                        \App\Models\Admin\Element::all()
                    );
                });
            }
        }

        if (Schema::hasTable('main_parts')) {
            if (Schema::hasColumn('main_parts', 'main_code')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_mainParts',
                        \App\Models\Admin\MainPart::all()
                    );
                });
            }
        }

        if (Schema::hasTable('mixtures')) {
            if (Schema::hasColumn('mixtures', 'mix_component')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_mixtures',
                        \App\Models\Admin\Mixture::all()
                    );
                });
            }
        }


        if (Schema::hasTable('classifications')) {
            if (Schema::hasColumn('classifications', 'class_parent')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_classifications',
                        \App\Models\Admin\Classification::all()
                    );
                });
            }
        }


        if (Schema::hasTable('software')) {
            if (Schema::hasColumn('software', 'serial_number')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_software',
                        \App\Models\Admin\Software::all()
                    );
                });
            }
        }


        if (Schema::hasTable('units')) {
            if (Schema::hasColumn('units', 'unit_code')) {
                View::composer(['admin.*', 'admin.*'], function ($view) {
                    return $view->with(
                        'look_units',
                        \App\Models\Admin\Unit::all()
                    );
                });
            }
        }



		if(Schema::hasTable('software')){
    if (Schema::hasColumn('software', 'serial_number')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_software',
                \App\Models\Admin\Software::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'set_components')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('mixtures')){
    if (Schema::hasColumn('mixtures', 'mix_components')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mixtures',
                \App\Models\Admin\Mixture::all()
            );
        });
}
}




		if(Schema::hasTable('software')){
    if (Schema::hasColumn('software', 'serial_number')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_software',
                \App\Models\Admin\Software::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'set_components')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('mixtures')){
    if (Schema::hasColumn('mixtures', 'mix_components')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mixtures',
                \App\Models\Admin\Mixture::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('pumps')){
    if (Schema::hasColumn('pumps', 'pump_flowrate')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_pumps',
                \App\Models\Admin\Pump::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('classifications')){
    if (Schema::hasColumn('classifications', 'class_child')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_classifications',
                \App\Models\Admin\Classification::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}









		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}



























		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('components_sets')){
    if (Schema::hasColumn('components_sets', 'compo_set_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_componentsSets',
                \App\Models\Admin\ComponentsSet::all()
            );
        });
}
}




		if(Schema::hasTable('categories')){
    if (Schema::hasColumn('categories', 'classification_id')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_categories',
                \App\Models\Admin\Category::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('k_products')){
    if (Schema::hasColumn('k_products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_kProducts',
                \App\Models\Admin\KProduct::all()
            );
        });
}
}




		if(Schema::hasTable('k_products')){
    if (Schema::hasColumn('k_products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_kProducts',
                \App\Models\Admin\KProduct::all()
            );
        });
}
}




		if(Schema::hasTable('k_a_s')){
    if (Schema::hasColumn('k_a_s', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_kAS',
                \App\Models\Admin\KA::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('products')){
    if (Schema::hasColumn('products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_products',
                \App\Models\Admin\Product::all()
            );
        });
}
}




		if(Schema::hasTable('products')){
    if (Schema::hasColumn('products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_products',
                \App\Models\Admin\Product::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'elements_eu_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('products')){
    if (Schema::hasColumn('products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_products',
                \App\Models\Admin\Product::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'elements_eu_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('products')){
    if (Schema::hasColumn('products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_products',
                \App\Models\Admin\Product::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'elements_eu_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('users')){
    if (Schema::hasColumn('users', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_users',
                \App\Models\User::all()
            );
        });
}
}




		if(Schema::hasTable('products')){
    if (Schema::hasColumn('products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_products',
                \App\Models\Admin\Product::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'elements_eu_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('users')){
    if (Schema::hasColumn('users', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_users',
                \App\Models\User::all()
            );
        });
}
}









		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('products')){
    if (Schema::hasColumn('products', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_products',
                \App\Models\Admin\Product::all()
            );
        });
}
}




		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'elements_eu_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('users')){
    if (Schema::hasColumn('users', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_users',
                \App\Models\User::all()
            );
        });
}
}





		if(Schema::hasTable('elements')){
    if (Schema::hasColumn('elements', 'element_name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_elements',
                \App\Models\Admin\Element::all()
            );
        });
}
}




		if(Schema::hasTable('units')){
    if (Schema::hasColumn('units', 'unit_code')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_units',
                \App\Models\Admin\Unit::all()
            );
        });
}
}




		if(Schema::hasTable('main_parts')){
    if (Schema::hasColumn('main_parts', 'main_usage_category')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_mainParts',
                \App\Models\Admin\MainPart::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}




		if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
}
}




		if(Schema::hasTable('countries')){
    if (Schema::hasColumn('countries', 'name')){
    View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_countries',
                \App\Models\Admin\Country::all()
            );
        });
}
}



if(Schema::hasTable('cities')){
    if (Schema::hasColumn('cities', 'name')){
        View::composer(['admin.*', 'admin.*'], function ($view) {
            return $view->with(
                'look_cities',
                \App\Models\Admin\City::all()
            );
        });
    }
}




	}
}
