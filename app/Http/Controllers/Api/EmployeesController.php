<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;

use App\Employee;

class EmployeesController extends Controller
{
    public function employeeSearch($id, Request $request) 
    {
        $dec = \base64_decode($id);
        $term = $request->input('search');

        $emps = Employee::where('company_id', $dec)->get();

        $employees = $emps->filter(function($item) use ($term) {
            return strpos($item['firstName'].' '.$item['lastName'], strval($term)) !== false;
        });

        // iLIKE works cos postgres
        // $employees = $emps::Where('firstName', 'iLIKE', '%'.$term.'%')
        //             ->orWhere('lastName', 'iLIKE', '%'.$term.'%')
        //             ->get();

        return response()->json(['employees' => EmployeeResource::collection($employees)]);
    }
}
