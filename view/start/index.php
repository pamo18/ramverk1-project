<?php

namespace Anax\View;

?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<h1 class="center"><?= $title ?></h1>

<div class="flex-row flex-align-middle user-header">
    <p class="flex-align-middle center">
        Questions<br><?= $questionCount ?>
    </p>

    <p class="flex-align-middle center">
        Tags<br><?= $tagCount ?>
    </p>

    <p class="flex-align-middle center">
        Users<br><?= $userCount ?>
    </p>
</div>

<h2>Newest Question</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Question</th>
            <th>Created</th>
        </tr>
    </thead>
    <?php foreach ($question as $row) : ?>
    <tr id="<?= "question-$row->id" ?>">
        <td width="50%"><?= $row->title ?></td>
        <td width="50%"><?= $row->created ?></td>
    </tr>
    <script>tableLinks("<?= "question-$row->id" ?>", "<?= url("game/question/$row->id") ?>");</script>
    <?php endforeach; ?>
</table>

<h2>Most Popular Tags</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Tag name</th>
            <th>Used in</th>
        </tr>
    </thead>
    <?php foreach ($tags as $row) : ?>
    <tr id="<?= "tag-$row->id" ?>">
        <td width="50%"><?= $row->tagname ?></td>
        <td width="50%"><?= $row->cnt ?> <?= $row->cnt == 1 ? "question" : "questions" ?></td>
    </tr>
    <script>tableLinks("<?= "tag-$row->id" ?>", "<?= url("question/tag/$row->tagname") ?>");</script>
    <?php endforeach; ?>
</table>

<h2>Best Ranked Users</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>User</th>
            <th>Rank</th>
        </tr>
    </thead>
    <?php foreach ($users as $row) : ?>
    <tr id="<?= "user-$row->id" ?>">
        <td width="50%">
            <figure>
                <img src="<?= $gravatar->get($row->email) ?>" alt="Gravatar"/>
                <figcaption class="gravatar-user">
                    <?= $row->username ?>
                </figcaption>
            </figure>
        </td>
        <td width="50%" class="table-font-large"><?= $row->rank ?></td>
    </tr>
    <script>tableLinks("<?= "user-$row->id" ?>", "<?= url("user/activity/$row->username") ?>");</script>
    <?php endforeach; ?>
</table>
