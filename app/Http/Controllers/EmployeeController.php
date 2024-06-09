<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class EmployeeController extends Controller
{
    /**
     * Synchronize the employee with the specified departments.
     *
     * @param Employee $employee
     * @param array $departments
     * @return void
     * @throws Exception
     */
    private function syncDepartments(Employee $employee, array $departments): void
    {
        if (!empty($departments)) {
            $employee->departments()->sync($departments);
        } else {
            throw new Exception('At least one department must be assigned');
        }
    }

    /**
     * Retrieve all employees with optional pagination.
     *
     * @param $limit
     * @return Response
     */
    public function index($limit = null): Response
    {
        try {
            $query = Employee::withBasicInfo();
            $employees = ($limit) ? $query->paginate($limit) : $query->get();

            return response(['status' => 'success', 'response' => ['employees' => $employees]]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Create a new employee.
     *
     * @param StoreEmployeeRequest $request
     * @return Response
     */
    public function store(StoreEmployeeRequest $request): Response
    {
        try {
            $input = $request->input();
            $employee = new Employee();
            $employee->first_name = $input['first_name'] ?? null;
            $employee->last_name = $input['last_name'] ?? null;
            $employee->middle_name = $input['middle_name'] ?? null;
            $employee->gender = $input['gender'] ?? null;
            $employee->salary = $input['salary'] ?? null;

            if (!$employee->save()) {
                throw new Exception('An error occurred while creating an employee');
            }

            $this->syncDepartments($employee, $input['departments']);

            return response(['status' => 'success', 'response' => ['employee' => $employee]], HttpResponse::HTTP_CREATED);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update an existing employee.
     *
     * @param UpdateEmployeeRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateEmployeeRequest $request, int $id): Response
    {
        try {
            $employee = Employee::where('id', $id)->first();

            if (!$employee) {
                throw new Exception('Employee not found');
            }

            $input = $request->input();
            $employee->first_name = $input['first_name'] ?? null;
            $employee->last_name = $input['last_name'] ?? null;
            $employee->middle_name = $input['middle_name'] ?? null;
            $employee->gender = $input['gender'] ?? null;
            $employee->salary = $input['salary'] ?? null;

            if (!$employee->save()) {
                throw new Exception('An error occurred when updating an employee');
            }

            $this->syncDepartments($employee, $input['departments']);

            return response(['status' => 'success', 'response' => ['employee' => $employee]]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete an employee.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        try {
            $employee = Employee::where('id', $id)->first();

            if (!$employee) {
                throw new Exception('Employee not found');
            }

            $employee->delete();

            return response(['status' => 'success', 'response' => 'Employee deleted successfully']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
