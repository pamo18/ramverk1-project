<?php

namespace Anax\View;

?>

<div class="flex-row flex-align-middle">
    <?php if (isset($activeUser)) { ?>
        <figure class="nav-icon">
            <a href="<?= url("user/profile") ?>">
                <img src="<?= $gravatar ?>" alt="Gravatar"/>
            </a>
            <figcaption class="gravatar-user">
                <?= $activeUser["username"] ?>
            </figcaption>
        </figure>

        <figure class="nav-icon">
            <a href="<?= url("user/activity/" . $activeUser["username"]) ?>">
                <img src="<?= url("image/icons/research.png") ?>" alt="Activity"/>
            </a>
            <figcaption class="gravatar-user">
                Activity
            </figcaption>
        </figure>

        <figure class="nav-icon">
            <a href="<?= url("user/logout") ?>">
                <img src="<?= url("image/icons/logout.png") ?>" alt="logout">
            </a>
            <figcaption class="gravatar-user">
                Logoff
            </figcaption>
        </figure>
    <?php } else { ?>
        <figure class="nav-icon">
            <a href="<?= url("user/login") ?>">
                <img src="<?= url("image/icons/user.png") ?>" alt="login">
            </a>
            <figcaption class="gravatar-user">
                Login
            </figcaption>
        </figure>
        <figure class="nav-icon">
            <a href="<?= url("user/create") ?>">
                <img src="<?= url("image/icons/add-user.png") ?>" alt="Register">
            </a>
            <figcaption class="gravatar-user">
                Register
            </figcaption>
        </figure>
    <?php } ?>
</div>
