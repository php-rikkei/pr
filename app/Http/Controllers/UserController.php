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

    public function index()
    {
        if (Auth::user()->root == 1)
        {
            $users = User::paginate(3);
            return view('users.index')->withUsers($users)->with('label', 'All users');
        }
        else if (Auth::user()->is_manager == 1)
            {
                $users = User::where('department_id', '=', Auth::user()->department_id)->paginate(3)->get();
                /*dd(Auth::user()->department_id);*/
                $label = "Department: ".Auth::user()->department->name;
                return view('users.index')->withUsers($users)->with('label', $label);
            }
            else
            {
                return "You do not have access";
            }
    }

    public function getInfomation() 
    {
        $user = Auth::user();
        return view('infomation.index')->with('user', $user);
    }

    public function editInfomation() 
    {
        $user = Auth::user();
        return view('infomation.edit')->with('user', $user);
    }

    public function updateInfomation(Request $request)
    {
        $request->validate([
            'username' => 'required|max:100|min:5|unique:users,username,'.Auth::user()->id,
            'password' => 'required|max:100|min:6',
            'name' => 'required|max:100|min:6',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
        ]);

        $user = Auth::user();
        $user->username = $request->username;
        if ($user->password != $request->password)
        {
            $user->password = bcrypt($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return redirect('/infomation')->withSuccess("The user has been successfully updated");
    }

    public function getChangePassword()
    {
        return view('auth.changepassword');
    }

    public function postChangePassword(Request $request)
    {
        $this->validate($request, [
        'password' => 'required|confirmed|min:6',
        ]);
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->status = '1';
        $user->save();
        return redirect('/home');
    }
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

        $user = new User;
        $user->username = $request->username;
        $user->password = bcrypt($request->password);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department_id;
        $user->is_manager = $request->is_manager;
        $user->root = 0;
        $user->status = 0;
        $user->save();
        $user->password = $request->password;
        Mail::to($user->email)->send(new SendInfomation($user));
        return redirect('/users')->withSuccess("The user has been successfully created");
    }

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
        $user = User::findOrFail($id);
        return view('users.edit')->with('departments', $departments)->with('user', $user);
    }

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
        $user = User::findOrFail($request->id);
        $user->username = $request->username;
        if ($user->password != $request->password)
        {
            $user->password = bcrypt($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->department_id = $request->department_id;
        $user->is_manager = $request->is_manager;
        $user->save();
        return redirect('/users')->withSuccess("The user has been successfully updated");
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
        $user = User::findOrFail($request->user_id);
        $user->delete();
        return redirect('/users')->withSuccess("The user has been successfully deleted");
    }

    public function resetPassword(Request $request) //reset a password of user     
    {

        $request->validate([
            'user_id' => 'required|numeric',
        ]);
        $user = User::findOrFail($request->user_id);
        $password = "123456";
        $user->password = bcrypt($password);
        $user->status = '0';
        $user->save();
        $user->password = $password;
        Mail::to($user->email)->send(new SendInfomation($user));
        return redirect('/users')->withSuccess("The password has been reset");
    }

    public function resetMultiplePasswords(Request $request) //Reset mulltible passwords
    {
        if($request->ajax())
        {
            foreach ($request->arr as $user_id) {
                $user = User::findOrFail($user_id);
                $password = "123456";
                $user->password = bcrypt($password);
                $user->status = '0';
                $user->save();
                $user->password = $password;
                Mail::to($user->email)->send(new SendInfomation($user));
            }
        }
    }

    public function exportToExcel() //Export users to excel
    {
        $export = User::select('id', 'username', 'name', 'email')->get();
        Excel::create('export data', function($excel) use($export){
            $excel->sheet('Sheet 1', function($sheet) use($export){
                $sheet->fromArray($export);
                $sheet->row(1, array(
                    'ID', 'User name', 'Name', 'Email address'
                ));            });
        })->export('xlsx');
    }

}
