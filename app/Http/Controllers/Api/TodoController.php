<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskCollection;
use App\Task;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Все задачи
     *
     * @return TaskCollection
     */
    public function tasks()
    {
        return new TaskCollection(Task::all());
    }

    /**
     * Создание
     */
    public function store(Request $request)
    {
        $task = Task::create($request->only('task'));

        if ($task instanceof Task) {
            return response()->json([
                'status' => 'true',
                'task' => $task->toArray(),
            ], 200);
        } else {
            return response()->json(501);
        }
    }

    /**
     * Редактирование
     *
     * @param Task $task
     */
    public function update(Task $task)
    {
        //
    }

    /**
     * Удаление
     *
     * @param Task $task
     */
    public function destroy(Task $task)
    {
        //
    }
}