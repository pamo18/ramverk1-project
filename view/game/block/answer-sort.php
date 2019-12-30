<?php

namespace Anax\View;

?>

<div class="flex-column flex-align-middle results-box buttons">
    <p><?= count($answers) ?><?= count($answers) === 1 ? " Answer" : " Answers" ?></p>
    <p>
        <a class="button <?= $activeSort == "created DESC" ? "active" : null ?>" href="<?= url("game/question/$question->id?sortABy=created&sortAType=DESC") ?>">
            Sort By Newest <i class="fas fa-arrow-down"></i>
        </a>
        <a class="button <?= $activeSort == "created ASC" ? "active" : null ?>" href="<?= url("game/question/$question->id?sortABy=created&sortAType=ASC") ?>">
            Sort By Oldest <i class="fas fa-arrow-up"></i>
        </a>
        <a class="button <?= $activeSort == "vote DESC" ? "active" : null ?>" href="<?= url("game/question/$question->id?sortABy=vote&sortAType=DESC") ?>">
            Sort By Best Rank <i class="fas fa-arrow-down"></i>
        </a>
        <a class="button <?= $activeSort == "vote ASC" ? "active" : null ?>" href="<?= url("game/question/$question->id?sortABy=vote&sortAType=ASC") ?>">
            Sort By Worst Rank <i class="fas fa-arrow-up"></i>
        </a>
    </p>
</div>
