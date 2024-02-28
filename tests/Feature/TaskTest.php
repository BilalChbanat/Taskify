<?php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user for testing
        $this->user = User::factory()->create();
        
    }

    public function user_can_get_all_tasks()
    {
        // Create a task associated with the user
        Task::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Task Name',
            'description' => 'Task Description', // Provide a value for the "description" field
            // Add other fields as needed
        ]);

        // Send a request to get all tasks for the user
        $response = $this->actingAs($this->user)->getJson('/api/v1/tasks');

        // Assert that the response is successful
        $response->assertStatus(200);

        // Add other assertions as needed
    }


    /** @test */
    public function user_can_create_task()
    {
        // Create task data
        $taskData = [
            'name' => 'Task Name',
            'description' => 'Task Description',
            // Add other task data as needed
        ];

        // Send a request to create a new task for the user
        $response = $this->actingAs($this->user)->postJson('/api/v1/tasks', $taskData);

        // Assert that the response is successful
        $response->assertStatus(201);

        // Add other assertions as needed
    }

    /** @test */
    public function user_can_update_task()
    {
        // Create a task associated with the user
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Task data to be updated
        $updatedTaskData = [
            'name' => 'Updated Task',
            'description' => 'Updated Task Description',
            'status' => 'completed',
        ];

        // Send a request to update the task
        $response = $this->actingAs($this->user)->putJson("/api/v1/tasks/{$task->id}", $updatedTaskData);

        // Assert that the response is successful
        $response->assertStatus(200);

        // Add other assertions as needed
    }

    /** @test */
    public function user_can_delete_task()
    {
        // Create a task associated with the user
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        // Send a request to delete the task
        $response = $this->actingAs($this->user)->deleteJson("/api/v1/tasks/{$task->id}");

        // Assert that the response is successful
        $response->assertStatus(200);

        // Add other assertions as needed
    }
}
