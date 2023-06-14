<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShareRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Models\Permission;
use App\Models\Share;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\Request;

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
        $user = auth()->user();

        $tasks = $user->tasks()->paginate(5);

        $permissions = Permission::all();

        $otherUsers = User::query()->select('users.*')->whereNotIn('users.id',[$user->id])->get();

        $shares = Share::with('tasks')->where('shared_user_id','=',$user->id)->get();

        return view('tasks.showTasks', [
            'tasks' => $tasks,
            'otherUsers' => $otherUsers,
            'permissions' => $permissions,
            'shares' => $shares,
        ]);
    }

    public function filter(Request $request)
    {
        $permissions = Permission::all();
        $user = auth()->user();
        $otherUsers = User::query()->select('users.*')->whereNotIn('users.id',[$user->id])->get();

        $title = $request->input('title');
        $description = $request->input('description');
        $status = $request->input('status');
        $tag = $request->input('tag');

        $tasks = Task::query()->select('tasks.*',)->distinct()->when($title, function($query, $title) {
                return $query->where('tasks.title', 'like', '%'.$title.'%');
            })->when($description, function($query, $description) {
                return $query->where('tasks.description', 'like', '%'.$description.'%');
            })->when($status, function($query, $status) {
                return $query->where('tasks.status', $status);
            })->when($tag, function($query, $tags) {
                return $query->join('tags', 'tasks.id','=','tags.task_id')->whereIn('tags.name', $tags);
            })->paginate();

        return view('tasks.showTasks', [
            'tasks' => $tasks,
            'otherUsers' => $otherUsers,
            'permissions' => $permissions,
        ]);
    }

    public function share(StoreShareRequest $request)
    {
        $userIdShared = $request->input('userIdShared');

        $access = $request->input('access');

        $user = auth()->user();

        Share::create([
            'owner_id' => $user->id,
            'shared_user_id' => $userIdShared,
            'access' => $access,
        ]);

        return redirect()->route('tasks.showTasks');
    }
}
