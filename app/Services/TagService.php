<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function createTags(string $dataTags, int $taskId)
    {
        $arrTags = explode(' ', $dataTags);

        foreach ($arrTags as $tag) {
            $tags[] = ['name' => $tag, 'task_id' => $taskId];
        }
        Tag::insert($tags);
    }

    public function deleteTags(int $taskId)
    {
        Tag::query()->where('task_id','=',$taskId)->delete();
    }
}
