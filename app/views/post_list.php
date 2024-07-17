<?php

use App\Models\Post;

/** @var Post[] $posts */

?>
<ul>
    <?php foreach ($posts as $post): ?>
        <li>
            <?= $post->getAttribute('title') ?>
        </li>
    <?php endforeach; ?>
</ul>
