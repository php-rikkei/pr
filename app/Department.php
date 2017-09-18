<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    //
    protected $table = 'departments';

    public function users()
    {
        return $this->hasMany('App\User');
    }

    /**
     * get  department by id
     *
     * @param $id
     * @return Object
     */
    public static function getDepartmentByID($id)
    {
        $department = Department::findOrFail($id);
        return $department;
    }

    /**
     * Store a new department in the database.
     *
     * @param  array
     * @return object
     */
    public static function insertDepartment($data)
    {
        $department = new Department;
        $department->name = $data['name'];
        $department->save();
        return $department;
    }

    /**
     * update  department in the database.
     *
     * @param  array
     * @return object
     */
    public static function updateDepartment($data)
    {
        $department = Department::find($data['id']);
        $department->name = $data['name'];
        $department->save();
        return $department;
    }
}
