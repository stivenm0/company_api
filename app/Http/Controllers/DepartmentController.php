<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends Controller
{
    
    public function index()
    {
        $departments = Department::all();

        return response()->json($departments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request, [
            'name'=>'required|string|min:1|max:100'
        ]);

        if($validate->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validate->errors()
            ], 400 );
        }

        $department =  new Department($request->input());
        $department->save();

        return response()->json([
            'status'=> true,
            'message'=> 'Department Created Successfully'
        ], 200 );
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return response()->json([
            'status'=>true,
            'data' => $department
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validate = Validator::make($request, [
            'name'=>'required|string|min:1|max:100'
        ]);

        if($validate->fails()){
            return response()->json([
                'status'=> false,
                'errors'=> $validate->errors()
            ], 400 );
        }

        $department->update($request->input());

        return response()->json([
            'status'=> true,
            'message'=> 'Department Updated Successfully'
        ], 200 );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'status'=>true,
            'message' => 'Department Deleted Successfully'
        ]);
    }


}
