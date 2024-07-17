<?php

namespace App\Models;

class Post extends Model
{
    public function properties(): array
    {
        return [
            'title',
            'description',
        ];
    }
}