<?php

namespace Anax\View;

// Gather incoming variables and use default values if not set
$tags = isset($tags) ? $tags : null;

?><h1 class="center">All Tags</h1>

<?php if (!$tags) : ?>
    <p>Sorry there are no tags to show.</p>
    <?php return;
endif;
?>

<script type="text/javascript" src='js/pamo.js'></script>

<?php foreach ($tags as $tag) :
    $name = str_replace(" ", "-", $tag->name); ?>
    <div class="flex-align-middle">
        <a class="button wide-button" href=<?= url("question/tag/$name") ?>>
            Go to questions for <?= $tag->name ?>
        </a>
        <a class="button wide-button game-tag-button" href=<?= url("tag/info/$name") ?>>
            Game Tag <?= $tag->name ?>
        </a>
    </div>

    <table class="results-table clickable">
        <thead>
            <tr class="first">
                <th>Question</th>
                <th>User</th>
                <th>Created</th>
            </tr>
        </thead>
        <?php foreach ($game->tagQuestion->joinWhere("tagname = ?", $tag->name, "Question", "questionid = Question.id") as $row) : ?>
            <tr id="q-<?= $row->id ?>">
                <td width="50%"><?= $row->title ?></td>
                <td width="25%"><?= $row->user ?></td>
                <td width="25%"><?= $row->created ?></td>
            </tr>
            <script>tableLinks("q-<?= $row->id ?>", "<?= url("game/question/$row->id") ?>");</script>
        <?php endforeach; ?>
    </table>
<?php endforeach; ?>
