<?php

namespace Anax\View;

// Gather incoming variables and use default values if not set
$items = isset($users) ? $users : null;

?><h1 class="center"><?= $title ?></h1>

<?php if (!$users) : ?>
    <p>There are no users to show.</p>
    <?php return;
endif;
?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>User</th>
            <th>Rank</th>
            <th>Votes given</th>
            <th>Created</th>
        </tr>
    </thead>
    <?php foreach ($users as $user) : ?>
    <tr id="<?= "user-$user->id" ?>">
        <td width="50">
            <figure>
                <img src="<?= $gravatar->get($user->email) ?>" alt="Gravatar"/>
                <figcaption class="gravatar-user">
                    <?= $user->username ?>
                </figcaption>
            </figure>
        </td>
        <td width="10"><?= $user->rank ?></td>
        <td width="10"><?= $user->voted ?></td>
        <td width="30"><?= $user->created ?></td>
    </tr>
    <script>tableLinks("<?= "user-$user->id" ?>", "<?= url("user/activity/$user->username") ?>");</script>
    <?php endforeach; ?>
</table>
