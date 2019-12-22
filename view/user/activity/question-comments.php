<?php

namespace Anax\View;

$commentQuestions = isset($commentQuestions) ? $commentQuestions : null;

?>

<?php if (!$commentQuestions) {
    return;
} ?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<h2>Question Comments</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Comments</th>
            <th>Created</th>
            <th>User</th>
        </tr>
    </thead>
    <?php foreach ($commentQuestions as $comment) : ?>
    <tr id="<?= "comment-q-$comment->commentid" ?>">
        <td width="65%"><?= $comment->comment ?></td>
        <td width="20%"><?= $comment->commentdate ?></td>
        <td width="15%"><?= $comment->commentuser ?></td>
    </tr>
    <script>tableLinks("<?= "comment-q-$comment->commentid" ?>", "<?= url("game/question/$comment->questionid") ?>");</script>
    <?php endforeach; ?>
</table>
