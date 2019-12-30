<?php

namespace Anax\View;

?>

<script type="text/javascript" src=<?= url('js/pamo.js') ?>></script>

<div class="flex-column-right">
    <?php foreach ($answers as $answer) : ?>
        <div class="flex-row results-box answer">
            <!-- Left column -->
            <div class="flex-column-left flex-align-middle">
                <i id="answer-vote-up-<?= $answer->id ?>" class="fas fa-arrow-alt-circle-up"></i>
                <p>
                    <?= $answer->vote ?>
                </p>
                <i id="answer-vote-down-<?= $answer->id ?>" class="fas fa-arrow-alt-circle-down"></i>
                <br>
                <?php if ($question->accepted === $answer->id) { ?>
                    <i id="unaccept-answer-<?= $answer->id ?>" class="fas fa-check-circle accepted"></i>
                    <script>reloadHere("unaccept-answer-<?= $answer->id ?>", "<?= url("question/unaccept/$question->id") ?>");</script>
                <?php } else { ?>
                    <i id="accept-answer-<?= $answer->id ?>" class="fas fa-star"></i>
                    <script>reloadHere("accept-answer-<?= $answer->id ?>", "<?= url("question/accept/$question->id/$answer->id") ?>");</script>
                <?php } ?>
                <script>
                    reloadHere("answer-vote-up-<?= $answer->id ?>", "<?= url("game/vote/answer/$answer->id/up") ?>");
                    reloadHere("answer-vote-down-<?= $answer->id ?>", "<?= url("game/vote/answer/$answer->id/down") ?>");
                </script>
            </div>
            <!-- Right column -->
            <div class="flex-column-right">
                <div class="flex-row">
                    <!-- Answer -->
                    <p>
                        <?= $textFilter->doFilter("$answer->text<br>$answer->user $answer->created", "markdown") ?>
                    </p>
                </div>
                <!-- Gravatar -->
                <div class="flex-row flex-align-right">
                    <figure>
                        <a href=" <?= url("user/activity/$answer->user") ?>">
                            <img src="<?= $gravatar->get($user->find("username", $answer->user)->email) ?? null?>" alt="Gravatar"/>
                        </a>
                        <figcaption class="gravatar-user">
                            <?= $user->find("username", $answer->user)->username ?>
                        </figcaption>
                    </figure>
                </div>
                <!-- Comments -->
                <div class="flex-column comment">
                    <?php foreach ($commentAnswer->findAllWhere("answerid = ?", $answer->id) as $comment) : ?>
                        <p>
                            <?php
                                $voteDown = "&nbsp;&nbsp;&nbsp;&nbsp;<i id='comment-vote-down-a-$comment->id' class='fas fa-arrow-alt-circle-down'></i>";
                                $voteUp = "<i id='comment-vote-up-a-$comment->id' class='fas fa-arrow-alt-circle-up'></i>";
                            ?>
                            <?= $textFilter->doFilter("$comment->text - $comment->user $comment->created $voteDown $comment->vote $voteUp", "markdown") ?>
                            <script>
                                reloadHere("comment-vote-down-a-<?= $comment->id ?>", "<?= url("game/vote/comment-answer/$comment->id/down") ?>");
                                reloadHere("comment-vote-up-a-<?= $comment->id ?>", "<?= url("game/vote/comment-answer/$comment->id/up") ?>");
                            </script>
                        </p>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<!-- Answer form -->
<div class="form-answer">
    <p class="button-link">
        <a class="button" href=<?= url("user/login") ?>>
            Login to answer
        </a>
    </p>
</div>
