<?php

namespace App\Services;

use App\Exceptions\SaveTaskExeptions;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TaskService
{
    private TagService $tagService;
    private ImageService $imageService;

    public function __construct(TagService $tagService, ImageService $imageService)
    {
        $this->tagService = $tagService;
        $this->imageService = $imageService;
    }
    public function createTask(array $data, ?UploadedFile $image)
    {
        DB::beginTransaction();

        try {
            $task = new Task();
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->user_id = auth()->id();
            $task->status = $data['status'];

            $task->save();

            $this->tagService->createTags($data['tags'], $task->id);

            if (!empty($image)) {
                $this->imageService->saveImage($task, $image);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw new SaveTaskExeptions("Don't save task with images",0, $e);
        }
    }

    public function updateTask(array $data, ?UploadedFile $image, Task $task, bool $deleteImage)
    {
        $task->title = $data['title'];
        $task->status = $data['status'];
        $task->description = $data['description'];
        $task->save();

        if (!empty($data['tags'])) {
            $this->tagService->deleteTags($task['id']);
            $this->tagService->createTags($data['tags'], $task['id']);
        }

        if (!empty($image)) {
            $this->imageService->updateImage($task, $image);
        }

        if ($deleteImage) {
            $this->imageService->deleteImage($task);
        }
    }

    public function deleteTask(Task $task)
    {
        $task->delete();
        $this->imageService->deleteImage($task);
    }
}

