<?php

namespace Anax\View;

$questions = isset($questions) ? $questions : null;

?><h1 class="center"><?= $title ?></h1>

<div class="flex-row flex-align-middle user-header">
    <p class="flex-align-middle center">
        Rank<br><?= $user->rank ?>
    </p>

    <figure>
        <img src="<?= $gravatar->get($user->email) ?>" alt="Gravatar"/>
        <figcaption class="gravatar-user">
            <?= $user->username ?>
        </figcaption>
    </figure>

    <p class="flex-align-middle center">
        Votes given<br><?= $user->voted ?>
    </p>
</div>
