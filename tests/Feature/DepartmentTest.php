<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DepartmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders([
            'Authorization' => 'Bearer ' . env('API_TOKEN'),
        ]);
    }

    #[Test]
    public function it_can_create_a_department()
    {
        $data = [
            'name' => 'New Department',
        ];

        $response = $this->postJson('/api/v1/department', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'response' => [
                    'department' => [
                        'name' => 'New Department',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('departments', $data);
    }

    #[Test]
    public function it_can_update_a_department()
    {
        $department = Department::factory()->create();

        $data = [
            'name' => 'Updated Department',
        ];

        $response = $this->putJson("/api/v1/department/{$department->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'response' => [
                    'department' => [
                        'name' => 'Updated Department',
                    ],
                ],
            ]);

        $this->assertDatabaseHas('departments', $data);
    }

    #[Test]
    public function it_can_delete_a_department()
    {
        $department = Department::factory()->create();

        $response = $this->deleteJson("/api/v1/department/{$department->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'response' => 'Department deleted successfully',
            ]);

        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }

    #[Test]
    public function it_cannot_delete_a_department_with_employees()
    {
        $department = Department::factory()->create();
        $employee = Employee::factory()->create();
        $department->employees()->attach($employee->id);

        $response = $this->deleteJson("/api/v1/department/{$department->id}");

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'response' => ['Cannot delete department with employees'],
            ]);

        $this->assertDatabaseHas('departments', ['id' => $department->id]);
    }
}
