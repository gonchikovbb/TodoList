<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;

class TaskController extends Controller
{
    private TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     * @param $taskValidationService
     */

    public function store(StoreTaskRequest $request)
    {
        $data = $request->all();

        $this->taskService->createTask($data, $request->file('image'));

        return redirect()->route('tasks.showTasks');
    }

    public function update(StoreTaskRequest $request, Task $task)
    {
        $data = $request->all();

        $deleteImage = $request->has('delete_image');

        $this->taskService->updateTask($data, $request->file('image'), $task, $deleteImage);

        return redirect()->route('tasks.showTasks');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function delete(Task $task)
    {
        $userId = auth()->id();

        if ($task->user_id === $userId) {
            $this->taskService->deleteTask($task);
        }

        return redirect()->route('tasks.showTasks');
    }

    public function showTasks()
    {
        $tasks = auth()->user()->tasks()->paginate(5);

        return view('tasks.showTasks', compact('tasks'));
    }

    public function filter(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $status = $request->input('status');
        $tag = $request->input('tag');
        $arrTags = explode(' ', $tag);

        $tasks = Task::query()
            ->join('tags', 'tasks.id','=','tags.task_id')
            ->select('tasks.*',)->distinct()
            ->when($title, function($query, $title) {
                return $query->where('tasks.title', 'like', '%'.$title.'%');
            })
            ->when($description, function($query, $description) {
                return $query->where('tasks.description', 'like', '%'.$description.'%');
            })
            ->when($status, function($query, $status) {
                return $query->where('tasks.status', $status);
            })
            ->when($arrTags, function($query, $tags) {
                return $query->whereIn('tags.name', $tags);
            })
            ->paginate();

        return view('tasks.showTasks', compact('tasks'));
    }
}
