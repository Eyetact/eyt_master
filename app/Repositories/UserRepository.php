<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class UserRepository extends AbstractRepository
{

      /**
     * holds the specific model itself.
     *
     * @var Model
     */
    protected $model;

    /**
     * Create new Library class.
     *
     * this abstraction expects the child class to have a protected attribute named model.
     * that will hold the model name with its full namespace.
     */
    public function __construct( Model $model )
    {
        $this->model = $model;
    }


    /**
     * saveRestaurant function
     *
     * @param object $data
     * @return object
     */
    public function save($data)
    {

        $model = $this->model->create([

            'email'=>$data->email,
             'name' => $data->serial_number,
            'password'=>Hash::make($data->password),
        ]);


        return $model->fresh();

    }


    public function edit($data,$user)
    {

        $user->update($data->all());


         return $user->fresh();

    }
    /**
     * asignRoleToUser function
     *
     * @return Collection
     */
    public function asignRoleToUser($id, $roles)
    {
        try {

            $user = $this->model->where('id', $id)->firstOrFail();
            $user->syncRoles($roles);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function insertImage($file, $user)
    {
        $user->image = $file;
        return $user->save();
    }

}
