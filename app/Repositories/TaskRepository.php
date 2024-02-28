<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface
{
    public function all()
    {
        return Task::all();
    }

    public function userTasks(int $userId)
    {
        return Task::where('user_id', $userId)->get();
    }

    public function create(array $data)
    {
        return Task::create($data);
    }
    public function find(int $id)
    {
        return Task::find($id);
    }

    public function update(int $id, array $data)
    {
        $task = Task::find($id);

        if ($task) {
            $task->update($data);
        }

        return $task;
    }

    public function delete(int $id)
    {
        $task = Task::find($id);

        if ($task) {
            $task->delete();
        }
    }


}