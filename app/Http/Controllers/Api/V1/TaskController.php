<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
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
        $tasks = Task::all();

        if (count($tasks) > 0) {
            return response()->json([
                'status' => '200',
                'tasks' => $tasks
            ], 200);
        } else {
            return response()->json([
                'status' => '404',
                'message' => 'NULL '
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
    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();

        $task = Task::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
        ]);

        return response()->json([
            'message' => 'Task inserted successfully',
        ], 201);
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
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }

        $this->authorize('view', $task);

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
        $task = Task::find($id);

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

        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }

        $this->authorize('update', $task);

        $task->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'status' => $validatedData['status'],
        ]);

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
        $task = Task::find($id);

        if ($task) {
            $this->authorize('delete', $task);

            $task->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Task deleted successfully',
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Task not found',
            ], 404);
        }
    }
}
