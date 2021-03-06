<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use App\User;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index')->with('departments', $departments );
    }
    public function create()
    {
        $departments = Department::all();
        return view('departments.create')->with('departments', $departments);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|min:3',
        ]);

        $department = new Department;
        $department->name = $request->name;
        $department->save();

        return redirect('/departments')->withSuccess("The department has been successfully created");
    }
    public function edit($id)
    {
        $department = Department::find($id);
        return view('departments.edit')->with('department', $department);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|min:3',
        ]);
        $department = Department::find($request->id);
        $department->name = $request->name;
        $department->save();
  
        return redirect('/departments')->withSuccess("The department has been successfully updated");
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        if (count($department->users) == 0)
        {
            $department->delete();
            return redirect('/departments')->withSuccess("The department has been successfully deleted");
        }
        else
        {
            return redirect('/departments')->withSuccess("Failed. Existing personnel in the department");
        }
    }
}
