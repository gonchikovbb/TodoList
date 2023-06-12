<?php

namespace App\Services;

use App\Exceptions\SaveTaskExeptions;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    public function saveImage(Task $task, ?UploadedFile $image)
    {
        try {
            $imageOrigPath = $image->store('uploads', 'public');
            $imagePrevPath = $image->store('uploads/previews', 'public');

            if (!$imageOrigPath) {
                throw new SaveTaskExeptions("Don't save photo to uploads");
            }

            $prevPath = public_path('storage/' . $imagePrevPath);
            Image::make($prevPath)->resize(150, 150)->save($prevPath);

            $task->setImageOrigPath($imageOrigPath);
            $task->setImagePrevPath($imagePrevPath);
            $task->setImageOrigName($image->getClientOriginalName());

            $task->save();

        } catch (\Exception $e) {
            Storage::disk('public')->delete($imageOrigPath);
            Storage::disk('public')->delete($imagePrevPath);

            throw new SaveTaskExeptions("Don't save images",0, $e);
        }
    }

    public function updateImage(Task $task, ?UploadedFile $image)
    {
        $oldImageOrigPath = $task->getImageOrigPath();
        $oldImagePrevPath = $task->getImagePrevPath();

        Storage::disk('public')->delete($oldImageOrigPath);
        Storage::disk('public')->delete($oldImagePrevPath);

        $this->saveImage($task, $image);
    }

    public function deleteImage(Task $task)
    {
        $imageOrigPath = $task->getImageOrigPath();
        $imagePrevPath = $task->getImagePrevPath();

        Storage::disk('public')->delete($imageOrigPath);
        Storage::disk('public')->delete($imagePrevPath);
    }
}
