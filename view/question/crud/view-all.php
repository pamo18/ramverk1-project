<?php

namespace Anax\View;

// Gather incoming variables and use default values if not set
$question = isset($question) ? $question : null;

?><h1 class="center"><?= $title ?></h1>

<?php if (!$question) : ?>
    <p>There are no questions to show.</p>
    <?php return;
endif;
?>

<script type="text/javascript" src=<?= url("js/pamo.js") ?>></script>

<div class="flex-column flex-align-middle results-box buttons">
    <p>
        <a class="button <?= $activeSort == "created DESC" ? "active" : null ?>" href="<?= url("?sortQBy=created&sortQType=DESC") ?>">
            Sort By Newest <i class="fas fa-arrow-down"></i>
        </a>
        <a class="button <?= $activeSort == "created ASC" ? "active" : null ?>" href="<?= url("?sortQBy=created&sortQType=ASC") ?>">
            Sort By Oldest <i class="fas fa-arrow-up"></i>
        </a>
        <a class="button <?= $activeSort == "vote DESC" ? "active" : null ?>" href="<?= url("?sortQBy=vote&sortQType=DESC") ?>">
            Sort By Best Rank <i class="fas fa-arrow-down"></i>
        </a>
        <a class="button <?= $activeSort == "vote ASC" ? "active" : null ?>" href="<?= url("?sortQBy=vote&sortQType=ASC") ?>">
            Sort By Worst Rank <i class="fas fa-arrow-up"></i>
        </a>
    </p>
</div>

<table class="results-table clickable">
    <thead>
        <tr class="first">
            <th>Rank</th>
            <th>Question</th>
            <th>User</th>
            <th>Tags</th>
            <th>Answers</th>
            <th>Created</th>
        </tr>
    </thead>
    <?php foreach ($question as $row) : ?>
    <tr id="<?= "question-$row->id" ?>">
        <td width="5%"><?= $row->vote ?></td>
        <td width="30%"><?= $row->title ?></td>
        <td width="10%"><?= $row->user ?></td>
        <td width="40%">
            <div class="flex-row flex-align-middle">
                <?php $tags = explode(",", $row->tags);
                foreach ($tags as $tag) :
                    $tagUrl = str_replace(" ", "-", $tag); ?>
                    <a class="button tag-button" href="<?= url("question/tag/$tagUrl") ?>"><?= $tag ?></a>
                    <a class="game-tag" href="<?= url("tag/info/$tagUrl") ?>">
                        <i class="fas fa-tag"></i>
                    </a>
                <?php  endforeach ?>
            </div>
        </td>
        <td width="5%"><?= count($answer->findAllWhere("questionid = ?", $row->id)) ?></td>
        <td width="10%"><?= $row->created ?></td>
    </tr>
    <script>tableLinks("<?= "question-$row->id" ?>", "<?= url("game/question/$row->id") ?>");</script>
    <?php endforeach; ?>
</table>

<p class="button-link">
    <a class="button right" href="<?= url("question/create") ?>">Ask a question</a>
</p>
