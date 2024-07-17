<?php

namespace App\Controllers;

use App\Core\Request;
use App\Models\Post;
use App\Views\View;

class PostController
{
    public function index(): string
    {
        $post1 = new Post();

        $post1->setAttribute('title', 'title_test1');
        $post1->setAttribute('description', 'description_test');

        $post2 = new Post();

        $post2->setAttribute('title', 'title_test2');
        $post2->setAttribute('description', 'description_test');

        $view = new View();
        return $view->render('post_list', [
            'posts' => [
                $post1,
                $post2
            ]
        ]);
    }

    public function update(Request $request): string
    {
        $post = new Post($request->all());

        $view = new View();
        return $view->render('post', [
            'title' => $post->getAttribute('title'),
            'description' => $post->getAttribute('description'),
        ]);
    }
}