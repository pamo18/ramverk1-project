<?php

namespace Anax\View;

$questions = isset($questions) ? $questions : null;

?>

<?php if (!$questions) : ?>
    <p>There are no questions to show.</p>
    <?php return;
endif;
?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<h2>Questions</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Title</th>
            <th>Question</th>
            <th>Created</th>
            <th>User</th>
        </tr>
    </thead>
    <?php foreach ($questions as $question) : ?>
    <tr id="<?= "question-$question->id" ?>">
        <td width="25%"><?= $question->title ?></td>
        <td width="40%"><?= $question->text ?></td>
        <td width="20%"><?= $question->created ?></td>
        <td width="15%"><?= $question->user ?></td>
    </tr>
    <script>tableLinks("<?= "question-$question->id" ?>", "<?= url("game/question/$question->id") ?>");</script>
    <?php endforeach; ?>
</table>
