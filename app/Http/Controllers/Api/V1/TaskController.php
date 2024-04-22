<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{

    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Get all tasks",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="No tasks found")
     * )
     */
    public function index()
    {
        // Authorize the user to view tasks
        $this->authorize('viewAny', Task::class);
        
        // Get the authenticated user
        $user = auth()->user();

        // Retrieve tasks associated with the authenticated user using the repository
        $tasks = $this->taskRepository->userTasks($user->id);

        if (count($tasks) > 0) {
            return TaskResource::collection($tasks); // Assuming TaskResource is the correct resource class
        } else {
            return response()->json([
                'status' => '404',
                'message' => 'No tasks found for the user'
            ], 404);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/v1/tasks",
     *     summary="Create a new task",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Task created successfully")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'status' => 'required',
        ]);

        // $userId = Auth()->user()->id;
        $task = new Task;
        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->status = $request->input('status');
        $task->user_id = 11;


        $task->save();

        return response()->json(['message' => 'Task created successfully', 'task' => $task], 201);
    }


    /**
     * @OA\Get(
     *     path="/api/v1/tasks/{id}",
     *     summary="Get a specific task by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function show(int $id)
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }

        return response()->json([
            'status' => 200,
            'task' => $task,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $task = $this->taskRepository->find($id);

        if ($task) {
            return response()->json([
                'status' => 200,
                'task' => $task,
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{id}",
     *     summary="Update a specific task by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="status", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Task updated successfully"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function update(UpdateTaskRequest $request, int $id)
    {
        
         $validatedData = $request->validated();

        $task = $this->taskRepository->find($id);

        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }

        // $this->authorize('update', $task);

        $this->taskRepository->update($id, $validatedData);

        return response()->json([
            'message' => 'Task updated successfully',
        ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tasks/{id}",
     *     summary="Delete a specific task by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Task deleted successfully"),
     *     @OA\Response(response=404, description="Task not found")
     * )
     */
    public function destroy(int $id)
    {
        $task = $this->taskRepository->find($id);

        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }


        $this->taskRepository->delete($id);

        return response()->json([
            'status' => 200,
            'message' => 'Task deleted successfully',
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/my-tasks",
     *     summary="Get tasks owned by the authenticated user",
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="No tasks found")
     * )
     */
    public function myTasks()
    {
        $tasks = $this->taskRepository->userTasks(auth()->id());

        if (count($tasks) > 0) {
            return response()->json([
                'status' => '200',
                'tasks' => $tasks
            ], 200);
        } else {
            return response()->json([
                'status' => '404',
                'message' => 'No tasks found'
            ], 404);
        }
    }
}
