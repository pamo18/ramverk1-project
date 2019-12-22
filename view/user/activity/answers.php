<?php

namespace Anax\View;

$answers = isset($answers) ? $answers : null;

?>

<?php if (!$answers) : ?>
    <p>There are no answers to show.</p>
    <?php return;
endif;
?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<h2>Answers</h2>
<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Answer</th>
            <th>Created</th>
            <th>User</th>
        </tr>
    </thead>
    <?php foreach ($answers as $answer) : ?>
    <tr id="<?= "answer-$answer->id" ?>">
        <td width="65%"><?= $answer->text ?></td>
        <td width="20%"><?= $answer->created ?></td>
        <td width="15%"><?= $answer->user ?></td>
    </tr>
    <script>tableLinks("<?= "answer-$answer->id" ?>", "<?= url("game/question/$answer->questionid") ?>");</script>
    <?php endforeach; ?>
</table>
