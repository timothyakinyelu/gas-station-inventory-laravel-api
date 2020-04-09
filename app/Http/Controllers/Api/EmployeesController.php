<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\NewEmployeeRequest;

use Auth;
use Image;
use App\Employee;

class EmployeesController extends Controller
{
    public function index($id, Request $request) 
    {
        $response = Gate::inspect('viewAny', [ Employee::class ]);

        if ($response->allowed()) {
            $dec = \base64_decode($id);
            $employees = Employee::where('company_id', $dec)
                ->orderBy('created_at', 'DESC')
                ->get();
            $data = EmployeeResource::collection($employees);

            $items = $data->toArray($request);

            $currentPage = Paginator::resolveCurrentPage();
            $perPage = 20;
            $currentItems = array_slice($items, $perPage * ($currentPage - 1), $perPage);
            $total = count($items);

            $paginator= new Paginator($currentItems, $total, $perPage, $currentPage);

            $paginator->withPath(config('app.url').'/api/v2/employees/'.$dec);
            return response()->json($paginator);
        } else {
            return $response->message();
        }
    }

    public function store(NewEmployeeRequest $request) 
    {
        $response = Gate::inspect('create', [ Employee::class]);
        $dec = \base64_decode($request->input('company_id'));

        if ($response->allowed()) {
            // The action is authorized...
            $employee = new Employee;
            $employee->firstName = $request->input('firstName');
            $employee->secondName = $request->input('secondName');
            $employee->lastName = $request->input('lastName');
            $employee->phone = $request->input('phone');
            $employee->date_of_birth = $request->input('date_of_birth');
            $employee->address = $request->input('address');
            $employee->salary = $request->input('salary');
            $employee->station_id = $request->input('station_id');
            $employee->company_id = $dec;
            $employee->role = $request->input('role');
            $employee->date_hired = $request->input('date_hired');
            $employee->save();

            if($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                
                $filename = time().'.' . $avatar->getClientOriginalName();
                Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatar/' .$filename ) );
        
                $employee->avatar = $filename;

                $employee->save();
            }

            return response()->json([
                'data' => 'employee Added',
            ]);
        } else {
            return $response->message();
        }
    }

    public function getEmployeeToEdit($id)
    {
        $employee = Employee::findOrFail($id);

        return response()->json([
            'employee' => $employee
        ]);
    }

    public function update($id, Request $request) 
    {
        
        $employee = Employee::find($id);
        // dd($employee);

        $response = Gate::inspect('update', $employee);
        
        if ($response->allowed()) {
            if( $request->get('firstName') !== ''){
                $firstName = $request->get('firstName');
            }else{
                $firstName = $employee->firstName;
            }
    
            if( $request->get('firstName') !== ''){
                $secondName = $request->get('secondName');
            }else{
                $secondName = $employee->secondName;
            }
    
            if( $request->get('lastName') !== ''){
                $lastName = $request->get('lastName');
            }else{
                $lastName = $employee->lastName;
            }
    
            if( $request->get('phone') !== ''){
                $phone = $request->get('phone');
            }else{
                $phone = $employee->phone;
            }
    
            if( $request->get('date_of_birth') !== '' ){
                $date_of_birth = $request->get('date_of_birth');
            }else{
                $date_of_birth = $employee->date_of_birth;
            }
    
            if( $request->get('address') !== '' ){
                $address = $request->get('address');
            }else{
                $address = $employee->address;
            }
    
            if( $request->get('salary') !== '' ){
                $salary = $request->get('salary');
            }else{
                $salary = $employee->salary;
            }
    
            if( $request->get('station_id') !== '' ){
                $station_id = $request->get('station_id');
            }else{
                $station_id = $employee->station_id;
            }

            if( $request->get('company_id') !== '' ){
                $company_id = $request->get('company_id');
            }else{
                $company_id = $employee->company_id;
            }
    
            if( $request->get('role') !== '' ){
                $role = $request->get('role');
            }else{
                $role = $employee->role;
            }
    
            if( $request->get('date_hired') !== '' ){
                $date_hired = $request->get('date_hired');
            }else{
                $date_hired = $employee->date_hired;
            }
            
            $employee->firstName = $request->input('firstName');
            $employee->secondName = $request->input('secondName');
            $employee->lastName = $request->input('lastName');
            $employee->phone = $request->input('phone');
            $employee->date_of_birth = $request->input('date_of_birth');
            $employee->address = $request->input('address');
            $employee->salary = $request->input('salary');
            $employee->station_id = $request->input('station_id');
            $employee->company_id = $request->input('company_id');
            $employee->role = $request->input('role');
            $employee->date_hired = $request->input('date_hired');
            $employee->save();

            if($request->hasFile('avatar')) {
                $avatar = $request->file('avatar');
                
                $filename = time().'.' . $avatar->getClientOriginalName();
                Image::make($avatar)->resize(300, 300)->save( public_path('/uploads/avatar/' .$filename ) );
        
                $oldPhoto = $employee->avatar;
                $employee->avatar = $filename;
    
                $employee->save();

                $oldPhoto->delete();
                unlink(public_path('/uploads/avatar/' .$filename ));
            }
       
            return response()->json([
                'success' => 'User updated successfully'
            ]);
        } else {
            return $response->message();
        }
    }

    public function delete($ids, Employee $employee) 
    {
        // dd($request->all());
        $response = Gate::inspect('delete', $employee);

        if ($response->allowed()) {
            $id = explode(",", $ids);
            $employees_to_delete = Employee::find($id);

            $employee = Employee::whereIn('id', $id)->delete();

            if($employee) {
                return response()->json(
                    ['status' => 'Employee has been deleted']
                );
            }
        } else {
            return $response->message();
        }
    }

    public function employeeSearch($id, Request $request) 
    {
        $dec = \base64_decode($id);
        $term = $request->input('search');

        $emps = Employee::where('company_id', $dec)->get();

        $employees = $emps->filter(function($item) use ($term) {
            return strpos($item['firstName'].' '.$item['lastName'], strval($term)) !== false;
        });

        return response()->json(['employees' => EmployeeResource::collection($employees)]);
    }
}
