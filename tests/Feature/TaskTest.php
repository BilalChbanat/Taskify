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

        $this->user = User::factory()->create();
        
    }

    public function user_can_get_all_tasks()
    {
        Task::factory()->create([
            'user_id' => $this->user->id,
            'name' => 'Task Name',
            'description' => 'Task Description',
            'status' => 'Task status',
        ]);

        $response = $this->actingAs($this->user)->getJson('/api/v1/tasks');

        $response->assertStatus(200);

    }


    /** @test */
    public function user_can_create_task()
    {
        $taskData = [
            'name' => 'Task Name',
            'description' => 'Task Description',
            'status' => 'Task status',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/tasks', $taskData);

        $response->assertStatus(201);

    }

    /** @test */
    public function user_can_update_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $updatedTaskData = [
            'name' => 'Updated Task',
            'description' => 'Updated Task Description',
            'status' => 'completed',
        ];

        $response = $this->actingAs($this->user)->putJson("/api/v1/tasks/{$task->id}", $updatedTaskData);

        $response->assertStatus(200);

    }

    /** @test */
    public function user_can_delete_task()
    {
        $task = Task::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(200);

    }
}
