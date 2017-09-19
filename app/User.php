<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'root', 'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function department()
    {
        return $this->belongsTo('App\Department');
    }

    /**
     * get all user
     *
     * @param
     * @return Collection
     */
    public static function getAllUsers()
    {
        $users =  User::paginate(PAGE_NUM);
        return $users;
    }

    /**
     * Store a new user in the database.
     *
     * @param  array
     * @return object
     */
    public static function insertUser($data)
    {
        $user = new User;
        $user->username = $data['username'];
        $user->password = bcrypt($data['password']);
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->department_id = $data['department_id'];
        $user->is_manager = $data['is_manager'];
        $user->root = 0;
        $user->status = 0;
        $user->save();
        return $user;
    }

    /**
     * Update user in the database.
     *
     * @param  array
     * @return object
     */
    public static function updateUser($data)
    {
        $user = User::findOrFail($data['id']);
        $user->username = $data['username'];
        if ($user->password != $data['password'])
        {
            $user->password = bcrypt($data['password']);
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->department_id = $data['department_id'];
        $user->is_manager = $data['is_manager'];
        $user->save();

        return $user;
    }

    /**
     * delete user in the database.
     *
     * @param  $id
     * @return
     */
    public static function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return 0;
    }

    /**
     * reset password of the user in the database.
     *
     * @param  $id, $password
     * @return object
     */
    public static function resetPasswordUser($id, $password)
    {
        $user = User::findOrFail($id);
        $user->password = bcrypt($password);
        $user->status = '0';
        $user->save();
        return $user;
    }

    /**
     * get all user to export excel
     *
     * @param
     * @return Collection
     */
    public static function getAllUserstoExport()
    {
        $users =  User::select('id', 'username', 'name', 'email')->get();
        return $users;
    }

    /**
     * get all user by department_id of manager
     *
     * @param
     * @return Collection
     */
    public static function getAllUsersByDepartment()
    {
        $users =   User::where('department_id', '=', Auth::user()->department_id)->paginate(3);
        return $users;
    }

    /**
     * get  user by id
     *
     * @param $id
     * @return Object
     */
    public static function getUserByID($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    /**
     * update current  user
     *
     * @param array
     * @return Object
     */
    public static function updateCurrentUser($data)
    {
        $user = Auth::user();
        $user->username = $data['username'];
        if ($user->password != $data['password'])
        {
            $user->password = bcrypt($data['password']);
        }
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->save();
        return $user;
    }

    /**
     * update password of current  user
     *
     * @param string
     * @return Object
     */
    public static function updatePasswordCurrentUser($password)
    {
        $user = Auth::user();
        $user->password = bcrypt($password);
        $user->status = '1';
        $user->save();
        return $user;
    }
}
