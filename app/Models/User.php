<?php

namespace App\Models;

use App\Generators\GeneratorUtils;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;
use Psy\SuperglobalsEnv;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property mixed avatar
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'website',
        'avatar',
        'user_id',
        'group_id',
        'ugroup_id',
        'username',
        'access_table',
        'code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getProfileUrlAttribute()
    {
        return asset('uploads/users/' . $this->avatar);
    }

    public function setAvatarAttribute($value)
    {
        if ($value) {
            try {
                // dd($value);
            $ext = $value->getClientOriginalExtension();
            $file_name = time() . mt_rand(1000, 9000) . '.' . $ext;
            $value->move(public_path('uploads/users/'), $file_name);
            $this->attributes['avatar'] = $file_name;
            } catch (\Throwable $th) {
               // dd($value);

            $this->attributes['avatar'] = $value;
            }
        }
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function vendors()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }

    public function admins()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function settings()
    {
        return $this->hasOne(Setting::class, 'created_by');
    }

    public function groups()
    {
        return $this->hasMany(UCGroup::class, 'user_id');
    }

    public function folders()
    {
        return $this->hasMany(Folder::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }


    public function setAccessTableAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['access_table'] = "Individual";
        }
        $this->attributes['access_table'] = $value;

    }

    public function getAccessTableAttribute()
    {
        if ($this->attributes['access_table'] == null) {
            return "Individual";
        }

        return $this->attributes['access_table'];

    }

    public function getCountAttribute()
    {
        $super = $this->hasRole('super');
        if ($super) {

            return 10000;

        }
        //employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return 10000;

            }
        }


        if ($this->hasRole('admin')) {

            $sub_id = $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;

            $sum = Limit::where('subscription_id', $sub_id)->sum('data_limit');

            $users = User::role('vendor')->where('user_id', $this->id)->get();


            foreach ($users as $user) {

                $sub_id = $user->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;
                if ($sub_id) {
                    $sum += Limit::where('subscription_id', $sub_id)->sum('data_limit');
                }

            }
            // dd($sum);

        } else if ($this->hasRole('vendor')) {

            $sub_id = $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;

            $sum = Limit::where('subscription_id', $sub_id)->sum('data_limit');

        } else {

            $customer = User::find($this->user_id);
            $sub_id = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;
            $sum = Limit::where('subscription_id', $sub_id)->sum('data_limit');

        }

        return $sum;
    }


    protected function getDefaultGuardName(): string
    {
        return 'web';
    }



    public function getModelLimitAttribute() // $user->model_limit
    {

        $super = $this->hasRole('super');
        if ($super) {

            return 10000;

        }
        //employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return 10000;

            }

            $customer = User::find($this->user_id);
            return $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan?->model_limit;
        }

        return (int) $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan?->model_limit;

    }

    public function getDataLimitAttribute() // $user->model_limit
    {
        $super = $this->hasRole('super');
        if ($super) {

            return 2000;

        }
        //employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return 2000;

            }

            $customer = User::find($this->user_id);
            return $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan?->data_limit;
        }


        return $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan?->data_limit;

    }


    public function getDataLimitByModel($module_id)
    {
        $super = $this->hasRole('super');
        if ($super) {
            return 10000;
        }
        //employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return 1000;

            }

            $customer = User::find($this->user_id);
            $current_plan = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan;

            $limit = Limit::where('plan_id', $current_plan->id)->where('module_id', $module_id)->first()->data_limit;
            return $limit;

        }

        if ($this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()) {
            $current_plan = $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan;

            $limit = Limit::where('plan_id', $current_plan->id)->where('module_id', $module_id)->first();

            // if ($this->hasRole('vendor')) {
            //     $customer = User::find($this->user_id);
            //     $current_plan = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->plan;
            //     $limit2 = Limit::where('plan_id', $current_plan->id)->where('module_id', $module_id)->first();
            //     if ($limit) {

            //         return $limit?->data_limit + $limit2?->data_limit;
            //     }
            // }



            if ($limit) {

                return $limit?->data_limit;
            }
        }

        return 0;


    }

    public function getCurrentModelLimitAttribute()
    {
        $super = $this->hasRole('super');
        if ($super) {

            return 0;

        }
        //employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return 0;

            }
        }

        $users = User::where('user_id', $this->id)->pluck('id');
        $customer = $this;
        // if ($this->hasRole('vendor')) {
        //     $customer = User::find($this->user_id);
        //     $users = User::where('user_id', $customer->id)->pluck('id');
        // }

        return count(Module::whereIn('user_id', $users)->orWhere('user_id', $customer->id)->get());

    }

    //$user->model_limit < $user->current_model_limit

    //$user->data_limit < count(unit::all())

    public function getCountByModelID($model_id, $only_me = false)
    {
        $model = Module::find($model_id);
        $sum = 0;

        $super = $this->hasRole('super');
        if ($super) {

            return 10000;

        }
        //employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return 10000;

            }
        }

        if ($model->user_id == 1) {
            if (in_array($model->id, [1, 2, 3, 4, 5])) {

                $modelName = "App\Models\\" . GeneratorUtils::setModelName($model->code);
            } else {
                $modelName = "App\Models\Admin\\" . GeneratorUtils::setModelName($model->code);

            }


            $users = User::where('user_id', $this->id)->pluck('id');

            if (!$this->hasRole('admin') && !$this->hasRole('vendor')) {
                $customer = User::find($this->user_id);
                $users = User::where('user_id', $customer->id)->pluck('id');
                if ($model->id == 5) {
                    $sum = $modelName::whereIn('created_by', $users)->orWhere('created_by', $customer->id)->count();
                    return $sum;

                } else {
                    $sum = $modelName::whereIn('user_id', $users)->orWhere('user_id', $customer->id)->count();
                    return $sum;
                }

            } else {
                if ($this->hasRole('admin')) {
                    $vendors = User::role('vendor')->where('user_id', $this->id)->pluck('id');
                    $users = User::whereIn('user_id', $vendors)->orWhere('user_id', $this->id)->pluck('id');

                    if ($model->id == 5) {
                        $sum = $modelName::whereIn('created_by', $users)->orWhere('created_by', $this->id)->count();
                        return $sum;

                    } else {
                        $sum = $modelName::whereIn('user_id', $users)->orWhere('user_id', $this->id)->count();

                        return $sum;
                    }
                }
                if ($this->hasRole('vendor')) {

                    if ($only_me) {
                        $customer = User::find($this->user_id);
                        if ($model->id == 5) {
                            $sum = $modelName::whereIn('created_by', $users)->orWhere('created_by', $this->id)
                                ->count();

                            return $sum;

                        } else {
                            $sum = $modelName::whereIn('user_id', $users)->orWhere('user_id', $this->id)
                                ->count();
                            return $sum;
                        }
                    }

                    $customer = User::find($this->user_id);
                    if ($model->id == 5) {
                        $sum = $modelName::whereIn('created_by', $users)->orWhere('created_by', $this->id)
                            ->orWhere('created_by', $customer->id)->count();

                        return $sum;

                    } else {
                        $sum = $modelName::whereIn('user_id', $users)->orWhere('user_id', $this->id)
                            ->orWhere('user_id', $customer->id)->count();
                        return $sum;
                    }

                }
            }





        } else {


            if ($this->hasRole('admin')) {

                $sub_id = $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;
                $sum = Limit::where('subscription_id', $sub_id)->where('module_id', $model_id)->sum('data_limit');

                $users = User::role('vendor')->where('user_id', $this->user_id)->get();
                foreach ($users as $user) {

                    $sub_id = $user->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;

                    $sum += Limit::where('subscription_id', $sub_id)->sum('data_limit');

                }

            } else if ($this->hasRole('vendor')) {

                $sub_id = $this->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;
                $sum = Limit::where('subscription_id', $sub_id)->where('module_id', $model_id)->sum('data_limit');
                return $sum;

            } else {

                $customer = User::find($this->user_id);
                $sub_id = $customer->subscriptions()->where('status', 'active')->orderBy('created_at', 'desc')->first()?->id;
                $sum = Limit::where('subscription_id', $sub_id)->where('module_id', $model_id)->sum('data_limit');
                return $sum;

            }

            return $sum;
        }
    }

    public function checkAllowdMode()
    {

        if ($this->hasRole('vendor')) {

            $customer = User::find($this->user_id);
            if ($customer->checkAllowdMode()) {
                return $this->model_limit > $this->current_model_limit;

            } else {
                return false;
            }
        }

        return $this->model_limit > $this->current_model_limit;
    }


    public function checkAllowdByModelID($model_id)
    {
        $model = Module::find($model_id);
        // dd($model);
        $sum = 0;

        $super = $this->hasRole('super');
        if ($super) {

            return true;

        }
        //admin employee case
        if (count($this->subscriptions) == 0) {

            if ($this->user_id == 1) {
                return true;

            }
        }

        if ($model->user_id == 1) {

            if (count($this->subscriptions) == 0) {
                $parent = User::find($this->user_id);
                if ($parent->hasRole('vendor')) {

                    $customer = User::find($parent->user_id);
                    if ($customer->checkAllowdByModelID($model_id)) {
                        return $this->getDataLimitByModel($model_id) > $this->getCountByModelID($model_id);
                    } else {
                        return false;
                    }
                }


                if ($parent->hasRole('admin')) {

                    return $parent->checkAllowdByModelID($model_id);
                }
            }
            if ($this->hasRole('vendor')) {

                $customer = User::find($this->user_id);
                if ($customer->checkAllowdByModelID($model_id)) {
                    return $this->getDataLimitByModel($model_id) > $this->getCountByModelID($model_id,true);

                } else {
                    return false;
                }
            }

            if ($this->getDataLimitByModel($model_id) >= 10000) {
                return true;
            }
            return $this->getDataLimitByModel($model_id) > $this->getCountByModelID($model_id);
        }
        // dd($this->count);
        if ($this->hasRole('vendor')) {

            $customer = User::find($this->user_id);
            if ($customer->checkAllowdByModelID($model_id)) {
                return $this->data_limit > $this->count;

            } else {
                return false;
            }
        }

        if (count($this->subscriptions) == 0) {
            $parent = User::find($this->user_id);
            if ($parent->hasRole('vendor')) {

                $customer = User::find($parent->user_id);
                if ($customer->checkAllowdByModelID($model_id)) {
                    return $parent->checkAllowdByModelID($model_id);
                } else {
                    return false;
                }
            }


            if ($parent->hasRole('admin')) {

                return $parent->checkAllowdByModelID($model_id);
            }
        }

        return $this->data_limit > $this->count;

    }

}
// $this->getDataLimitByModel => get max allowd data limit
// $this->getCountByModelID($model_id) get inserted data from models by admin
