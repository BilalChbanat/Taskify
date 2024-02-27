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
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();

        if (count($tasks) > 0){

            return response()->json([
            'status' => '200',
            'tasks' => $tasks
            ], 200);
        }else{
            return response()->json([
            'status' => '404',
            'message' => 'NULL '
        ], 404);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $task = Task::find($id);

        if($task){
            return response()->json([
            'status' => 200,
            'task' => $task,
        ], 200);
        }else{
            return response()->json([
            'status' => 404,
            'message' => 'Task not found',
        ], 404);
        }
        // return response()->json($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $task = Task::find($id);

        if($task){
            return response()->json([
            'status' => 200,
            'task' => $task,
        ], 200);
        }else{
            return response()->json([
            'status' => 404,
            'message' => 'Task not found',
        ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $task = Task::find($id);

        if($task){
            $task->delete();
             return response()->json([
            'status' => 200,
            'message' => 'Task deleted successfully',
        ], 200);
        }else{
             return response()->json([
            'status' => 404,
            'message' => 'Task not found',
        ], 404);
        }
    }
}
