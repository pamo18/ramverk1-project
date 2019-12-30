<?php

namespace Anax\View;

// Gather incoming variables and use default values if not set
$question = isset($question) ? $question : null;
?>

<?php if (!$question) : ?>
    <p>There is no question to show.</p>
    <?php return;
endif;
?>
<script type="text/javascript" src=<?= url('js/pamo.js') ?>></script>
<h1 class="center"><?= $question->title ?></h1>
<div class="flex-row flex-align-middle">
    <?php foreach ($tags as $tag) : ?>
        <a class="button tag-button" href="<?= url("question/tag/$tag->tagname") ?>">
            <?= $tag->tagname ?>
        </a>
        <a class="game-tag" href="<?= url("tag/info/$tag->tagname") ?>">
            <i class="fas fa-tag"></i>
        </a>
    <?php endforeach ?>
</div>

<div class="flex-row results-box question">
    <!-- Left column -->
    <div class="flex-column-left flex-align-middle">
        <i id="question-vote-up" class="fas fa-arrow-alt-circle-up"></i>
        <p>
            <?= $question->vote ?>
        </p>
        <i id="question-vote-down" class="fas fa-arrow-alt-circle-down"></i>
        <script>
            reloadHere("question-vote-up", "<?= url("game/vote/question/$question->id/up") ?>");
            reloadHere("question-vote-down", "<?= url("game/vote/question/$question->id/down") ?>");
        </script>
    </div>
    <!-- Right column -->
    <div class="flex-column-right">
        <div class="flex-row">
            <!-- Question -->
            <p>
                <?= $textFilter->doFilter("$question->text<br>$question->user $question->created", "markdown") ?>
            </p>
        </div>
        <!-- Gravatar -->
        <div class="flex-row flex-align-right">
            <figure>
                <a href=" <?= url("user/activity/$question->user") ?>">
                    <img src="<?= $gravatar->get($user->find("username", $question->user)->email) ?>" alt="Gravatar"/>
                </a>
                <figcaption class="gravatar-user">
                    <?= $user->find("username", $question->user)->username ?>
                </figcaption>
            </figure>
            <!-- Comments -->
            <div class="flex-column comment">
                <?php foreach ($commentQuestion as $comment) : ?>
                    <p>
                        <?= $textFilter->doFilter("$comment->text - $comment->user $comment->created", "markdown") ?>
                    </p>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>
