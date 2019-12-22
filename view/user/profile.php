<?php

namespace Anax\View;

$user = isset($user) ? $user : null;

?><h1 class="center"><?= $title ?></h1>

<?php if (!$user) : ?>
    <p>There are no profile to show.</p>
    <?php return;
endif;
?>

<div class="flex-column flex-align-middle">
    <p>Username: <?= $user->username ?></p>
    <p>Email: <?= $user->email ?></p>
    <p>Created: <?= $user->created ?></p>
    <p>Votes given: <?= $user->voted ?></p>
    <p>Rank: <?= $user->rank ?></p>
</div>

<div class="flex-row flex-align-middle">
    <p class="button-link">
        <a class="button" href="<?= url("user/update/$user->username") ?>">Edit Profile</a>
    </p>

    <p class="button-link">
        <a class="button" href="<?= url("user/delete/$user->username") ?>">Delete account</a>
    </p>
</div>
