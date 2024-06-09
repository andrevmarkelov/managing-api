<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Models\Department;
use Exception;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class DepartmentController extends Controller
{

    /**
     * Retrieve all departments with optional pagination.
     *
     * @param $limit
     * @return Response
     */
    public function index($limit = null): Response
    {
        try {
            $query = Department::withAggregates();
            $departments =  ($limit) ? $query->paginate($limit) : $query->get();

            return response(['status' => 'success', 'response' => ['departments' => $departments]]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Create a new department.
     *
     * @param StoreDepartmentRequest $request
     * @return Response
     */
    public function store(StoreDepartmentRequest $request): Response
    {
        try {
            $input = $request->input();
            $department = new Department();
            $department->name = $input['name'] ?? null;

            if (!$department->save()) {
                throw new Exception('An error occurred when creating a department');
            }

            return response(['status' => 'success', 'response' => ['department' => $department]], HttpResponse::HTTP_CREATED);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Update an existing department.
     *
     * @param UpdateDepartmentRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateDepartmentRequest $request, int $id): Response
    {
        try {
            $department = Department::where('id', $id)->first();

            if (!$department) {
                throw new Exception('Department not found');
            }

            $input = $request->input();
            $department->name = $input['name'] ?? null;

            if (!$department->save()) {
                throw new Exception('An error occurred while updating the department');
            }

            return response(['status' => 'success', 'response' => ['department' => $department]]);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Delete a department. A department with employees cannot be deleted.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        try {
            $department = Department::with('employees')->where('id', $id)->first();

            if (!$department) {
                throw new Exception('Department not found');
            } else if ($department->employees()->exists()) {
                throw new Exception('Cannot delete department with employees');
            }

            $department->delete();

            return response(['status' => 'success', 'response' => 'Department deleted successfully']);
        } catch (Exception $e) {
            return response(['status' => 'error', 'response' => [$e->getMessage()]], HttpResponse::HTTP_BAD_REQUEST);
        }
    }
}
