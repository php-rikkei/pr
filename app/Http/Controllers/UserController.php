<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Department;
use App\Mail\SendInfomation;
use Maatwebsite\Excel\Facades\Excel;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Redirect
     */
    public function index()
    {
        if (Auth::user()->root == 1)
        {
            $users = User::getAllUsers();
            return view('users.index')->withUsers($users)->with('label', 'All users');
        }
        else if (Auth::user()->is_manager == 1)
            {
                $users = User::getAllUsersByDepartment();
                /*dd(Auth::user()->department_id);*/
                $label = "Department: ".Auth::user()->department->name;
                return view('users.index')->withUsers($users)->with('label', $label);
            }
            else
            {
                return "You do not have access";
            }
    }

    /**
     * Display the infomation of the user.
     *
     * @param
     * @return Redirect
     */
    public function getInfomation() 
    {
        $user = Auth::user();
        return view('infomation.index')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param
     * @return Redirect
     */
    public function editInfomation() 
    {
        $user = Auth::user();
        return view('infomation.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateInfomation(Request $request)
    {
        $request->validate([
            'username' => 'required|max:100|min:5|unique:users,username,'.Auth::user()->id,
            'password' => 'required|max:100|min:6',
            'name' => 'required|max:100|min:6',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
        ]);
        $data = [
            'username' => $request->username,
            'password' => $request->password,
            'name' => $request->name,
            'email' => $request->email,
        ];
        User::updateCurrentUser($data);
        return redirect('/infomation')->withSuccess(\Message::UPDATE_USER_SUCCESS);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getChangePassword()
    {
        return view('auth.changepassword');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirect
     */
    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
        'password' => 'required|confirmed|min:6',
        ]);
        User::updatePasswordCurrentUser($request->password);
        return redirect('/home');
    }

    /**
     * Show the form for creating a new user.
     *
     * @return Redirect
     */
    public function create()
    {
        if (Auth::user()->root == 1)
        {
            $departments = Department::all();
        }
        else if (Auth::user()->is_manager == 1)
            {
                $department_id = Auth::user()->department_id;
                $departments = Department::where('id', '=', $department_id)->get();
            }
        return view('users.create')->with('departments', $departments);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirect
     */
    public function store(Request $request) // root user create user
    {
        $request->validate([
            'username' => 'required|unique:users|max:100|min:5',
            'password' => 'required|max:100|min:6',
            'name' => 'required|max:100|min:6',
            'email' => 'required|email|unique:users',
            'department_id' => 'required|numeric',
            'is_manager' => 'required|numeric',
        ]);

        $data = [
            'username' => $request->username,
            'password' => $request->password,
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'is_manager' => $request->is_manager,
        ];
        $user = User::insertUser($data);

        $user->password = $request->password;
        Mail::to($user->email)->send(new SendInfomation($user));
        return redirect('/users')->withSuccess(\Message::INSERT_USER_SUCCESS);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Redirect
     */
    public function edit($id)
    {
        if (Auth::user()->root == 1)
        {
            $departments = Department::all();
        }
        else if (Auth::user()->is_manager == 1)
            {
                $department_id = Auth::user()->department_id;
                $departments = Department::where('id', '=', $department_id)->get();
            }
        $user = User::getUserByID($id);
        return view('users.edit')->with('departments', $departments)->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirect
     */
    public function update(Request $request) //updated infomation of user by root user or manager of department
    {
        $request->validate([
            'user_id' => 'required|numeric',
            'username' => 'required|max:100|min:5|unique:users,username,'.$request->id,
            'password' => 'required|max:100|min:6',
            'name' => 'required|max:100|min:6',
            'email' => 'required|email|unique:users,email,'.$request->id,
            'department_id' => 'required|numeric',
            'is_manager' => 'required|numeric',
        ]);

        $data = [
            'id' => $request->user_id,
            'username' => $request->username,
            'password' => $request->password,
            'name' => $request->name,
            'password' => $request->password,
            'email' => $request->email,
            'department_id' => $request->department_id,
            'is_manager' => $request->is_manager,
        ];
        $user = User::updateUser($data);

        return redirect('/users')->withSuccess(\Message::UPDATE_USER_SUCCESS);
    }

    /**
    * Delete user
    *
    * @param  object $request
    * @return Request
    */
    public function destroy(Request $request)
    {
        $request->validate([
            'user_id' => 'required|numeric',
        ]);
        User::deleteUser($request->user_id);
        return redirect('/users')->withSuccess(\Message::DELETE_USER_SUCCESS);
    }

    /**
     * Reset password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirect
     */
    public function resetPassword(Request $request) //reset a password of user     
    {

        $request->validate([
            'user_id' => 'required|numeric',
        ]);
        $password = "123456";
        $user = User::resetPasswordUser($request->user_id, $password);
        $user->password = $password;
        Mail::to($user->email)->send(new SendInfomation($user));
        return redirect('/users')->withSuccess(\Message::RESET_PASSWORD_SUCCESS);
    }

    /**
     * Reset multiple passwords
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Redirect
     */
    public function resetMultiplePasswords(Request $request) //Reset mulltible passwords
    {
        if($request->ajax())
        {
            foreach ($request->arr as $user_id) {
                $password = "123456";
                $user = User::resetPasswordUser($user_id, $password);
                $user->password = $password;
                Mail::to($user->email)->send(new SendInfomation($user));
            }
        }
    }

    /**
     * Export list of users to excel.
     *
     * @return Redirect
     */
    public function exportToExcel() //Export users to excel
    {
        $export = User::getAllUserstoExport();
        Excel::create('export data', function($excel) use($export){
            $excel->sheet('Sheet 1', function($sheet) use($export){
                $sheet->fromArray($export);
                $sheet->row(1, array(
                    'ID', 'User name', 'Name', 'Email address'
                ));            });
        })->export('xlsx');
    }

}
