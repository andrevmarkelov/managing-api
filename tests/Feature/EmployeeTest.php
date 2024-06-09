<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EmployeeTest extends TestCase
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
    public function it_can_create_an_employee()
    {
        $departments = Department::factory()->count(2)->create();
        $data = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'middle_name' => 'Smith',
            'gender' => 'male',
            'salary' => 50000,
            'departments' => $departments->pluck('id')->toArray(),
        ];

        $response = $this->postJson('/api/v1/employee', $data);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'response' => [
                    'employee' => [
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'middle_name' => 'Smith',
                        'gender' => 'male',
                        'salary' => 50000,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('employees', ['first_name' => 'John']);
    }

    #[Test]
    public function it_can_update_an_employee()
    {
        $employee = Employee::factory()->create();
        $departments = Department::factory()->count(2)->create();
        $data = [
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'middle_name' => 'Smith',
            'gender' => 'female',
            'salary' => 60000,
            'departments' => $departments->pluck('id')->toArray(),
        ];

        $response = $this->putJson("/api/v1/employee/{$employee->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'response' => [
                    'employee' => [
                        'first_name' => 'Jane',
                        'last_name' => 'Doe',
                        'middle_name' => 'Smith',
                        'gender' => 'female',
                        'salary' => 60000,
                    ],
                ],
            ]);

        $this->assertDatabaseHas('employees', ['first_name' => 'Jane']);
    }

    #[Test]
    public function it_can_delete_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/v1/employee/{$employee->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'response' => 'Employee deleted successfully',
            ]);

        $this->assertDatabaseMissing('employees', ['id' => $employee->id]);
    }
}
