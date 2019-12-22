<?php

namespace Anax\View;

$commentAnswers = isset($commentAnswers) ? $commentAnswers : null;

?>

<?php if (!$commentAnswers) {
    return;
} ?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<h2>Answer Comments</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Comments</th>
            <th>Created</th>
            <th>User</th>
        </tr>
    </thead>
    <?php foreach ($commentAnswers as $comment) : ?>
    <tr id="<?= "comment-a-$comment->commentid" ?>">
        <td width="65%"><?= $comment->comment ?></td>
        <td width="20%"><?= $comment->commentdate ?></td>
        <td width="15%"><?= $comment->commentuser ?></td>
    </tr>
    <script>tableLinks("<?= "comment-a-$comment->commentid" ?>", "<?= url("game/question/$comment->questionid") ?>");</script>
    <?php endforeach; ?>
</table>
