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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
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
     * @param Request $request
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Task $task)
    {
        if ($task->update($request->only('task'))) {
            return response()->json([
                'status' => 'true',
            ], 200);
        } else {
            return response()->json(501);
        }
    }

    /**
     * Удаление
     *
     * @param Task $task
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Task $task)
    {
        if ($task->delete()) {
            return response()->json([
                'status' => 'true',
            ], 200);
        } else {
            return response()->json(501);
        }
    }
}