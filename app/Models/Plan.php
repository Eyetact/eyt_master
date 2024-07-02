<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission;

class Plan extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function setImageAttribute($value){
        if ($value){
            $file = $value;
            $extension = $file->getClientOriginalExtension(); // getting image extension
            $filename =time().mt_rand(1000,9999).'.'.$extension;
            $file->move(public_path('uploads/plans/'), $filename);
            $this->attributes['image'] =  'uploads/plans/'.$filename;
        }
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function subscriptions()
    {

        return $this->hasMany( Subscription::class );

    }

    public function limits( $module_id ){
        return (int)Limit::where('plan_id',$this->id)->where('module_id',$module_id)->first()?->data_limit;
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
