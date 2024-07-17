<?php

namespace App\Controllers;

use App\Core\Exceptions\HttpException;
use App\Core\Request;
use App\Helpers\Arr;
use App\Models\Post;
use App\Views\View;

class PostController
{
    private array $posts = [
        [
            'id' => 1,
            'title' => 'Title 1',
            'description' => 'Description 1',
        ],
        [
            'id' => 2,
            'title' => 'Title 2',
            'description' => 'Description 2',
        ]
    ];


    public function index(): string
    {
        $posts = [];
        foreach ($this->posts as $post) {
            $model = new Post($post);

            $posts[] = $model;
        }

        $view = new View();
        return $view->render('post_list', [
            'posts' => $posts
        ]);
    }

    public function show(Request $request): string
    {
        // todo bind id
        $id = (int)$request->query->get('id');

        $model = null;
        foreach ($this->posts as $post) {
            if ($post['id'] === $id) {
                $model = new Post($post);
                break;
            }
        }

        if (!$model) {
            throw new HttpException(404);
        }

        $view = new View();
        return $view->render('post', [
            'title' => $model->getAttribute('title'),
            'description' => $model->getAttribute('description'),
        ]);
    }

    public function update(Request $request): string
    {
        // todo bind id
        $id = (int)$request->query->get('id');

        $model = null;
        foreach ($this->posts as $post) {
            if ($post['id'] === $id) {
                $model = new Post($post);
                break;
            }
        }

        if (!$model) {
            throw new HttpException(404);
        }

        $data = $request->all();

        $title = Arr::get($data, 'title');
        $description = Arr::get($data, 'description');

        $model->setAttribute('title', $title);
        $model->setAttribute('description', $description);

        $view = new View();
        return $view->render('post', [
            'title' => $model->getAttribute('title'),
            'description' => $model->getAttribute('description'),
        ]);
    }
}