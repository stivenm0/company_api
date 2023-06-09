<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    
    public function index()
    {
        $employees = Employee::select('employees.*', 'departments.name as department')->join('departments', 'departments.id','=', 'employees.department_id')->orderBy('id', 'asc')->paginate(10);

        return response()->json($employees);
    }

    public function store(Request $request)
    {
        $validation = $this->validateEmployee($request->input());

        if($validation){
            return response()->json([
                'status'=> false,
                'errors'=> $validation,
            ], 400);

        }else{
            $employee = new Employee($request->input());
            $employee->save();

            return response()->json([
                'status'=> true,
                'message'=> 'Employee Created Successfully',
            ], 200);
        }
    }

    public function show(Employee $employee)
    {
        return response()->json($employee, 200);
    }

    public function update(Request $request, Employee $employee)
    {
        $validation = $this->validateEmployee($request->input());
        
        if($validation){
            return response()->json([
                'status'=> false,
                'errors'=> $validation,
            ], 400);

        }else{
            $employee->update($request->input());

            return response()->json([
                'status'=> true,
                'message'=> 'Employee Updated Successfully',
            ], 200);
        }

    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return response()->json([
            'status'=> true,
            'message'=> 'Employee Deleted Successfully',
        ], 200);
    }

    public function employesDepartments()
    {
        $result = Employee::select(DB::raw('departments.name, COUNT(employees.id) as total_empleados'))
        ->join('departments', 'departments.id','=', 'employees.department_id')
        ->groupBy('departments.name')
        ->get();

        return response()->json($result);
    }



    public function validateEmployee($params){
        $validation = Validator::make($params,[
            'name'=>'required|string|min:2|max:100',
            'email'=>'required|string|email|unique:employees,email|max:80',
            'phone'=>'required|max:15',
            'department_id'=>'required|numeric|min:2',
        ]);

        if($validation->fails()){
            return $validation->errors();

        }else{
            return false;
        }
    }

}
