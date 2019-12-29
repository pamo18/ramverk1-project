<?php

namespace Anax\View;

?>

<script type="text/javascript" src=<?= url('js/pamo.js') ?>></script>

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
                        <?php if (isset($adminForm) && $admin === "answer" && $answer->id == $adminAnswerId) { ?>
                            <div class="form-q-a">
                                <?= $adminForm ?>
                            </div>
                        <?php } else { ?>
                            <?php
                                $edit = $activeUser ? "&nbsp;&nbsp;&nbsp;&nbsp;<i id='answer-edit-$answer->id' class='fas fa-edit'></i>" : null;
                                $delete = $activeUser ? "<i id='answer-delete-$answer->id' class='fas fa-trash-alt'></i>" : null;
                            ?>
                            <?= $textFilter->doFilter("$answer->text<br>$answer->user $answer->created $edit $delete", "markdown") ?>
                            <script>
                                reloadHere("answer-edit-<?= $answer->id ?>", "<?= url("game/question/$question->id?admin=answer&adminType=edit&adminId=$answer->id") ?>");
                                reloadHere("answer-delete-<?= $answer->id ?>", "<?= url("game/question/$question->id?admin=answer&adminType=delete&adminId=$answer->id") ?>");
                            </script>
                        <?php } ?>
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
                                $voteDown = $activeUser ? "&nbsp;&nbsp;&nbsp;&nbsp;<i id='comment-vote-down-a-$comment->id' class='fas fa-arrow-alt-circle-down'></i>" : null;
                                $voteUp = $activeUser ? "<i id='comment-vote-up-a-$comment->id' class='fas fa-arrow-alt-circle-up'></i>" : null;
                                $edit = $activeUser ? "&nbsp;&nbsp;&nbsp;&nbsp;<i id='comment-edit-a-$comment->id' class='fas fa-edit'></i>" : null;
                                $delete = $activeUser ? "<i id='comment-delete-a-$comment->id' class='fas fa-trash-alt'></i>" : null;
                            ?>
                            <?= $textFilter->doFilter("$comment->text - $comment->user $comment->created $voteDown $comment->vote $voteUp $edit $delete", "markdown") ?>
                            <script>
                                reloadHere("comment-vote-down-a-<?= $comment->id ?>", "<?= url("game/vote/comment-answer/$comment->id/down") ?>");
                                reloadHere("comment-vote-up-a-<?= $comment->id ?>", "<?= url("game/vote/comment-answer/$comment->id/up") ?>");
                                reloadHere("comment-edit-a-<?= $comment->id ?>", "<?= url("game/question/$question->id?admin=comment-answer&adminType=edit&adminId=$comment->id&answerId=$answer->id") ?>");
                                reloadHere("comment-delete-a-<?= $comment->id ?>", "<?= url("game/question/$question->id?admin=comment-answer&adminType=delete&adminId=$comment->id&answerId=$answer->id") ?>");
                            </script>
                        </p>
                    <?php endforeach ?>
                </div>
                <!-- Comment form -->
                <?php if ($activeUser) { ?>
                    <div class="flex-row">
                        <?php if (isset($adminForm) && $admin === "comment-answer") { ?>
                            <?php if ($adminAnswerId == $answer->id) { ?>
                                <div class="form-comment">
                                    <?= $adminForm ?>
                                </div>
                            <?php } ?>
                        <?php } else { ?>
                            <button id="comment-a-<?= $answer->id ?>">Add comment</button>
                            <script>reloadHere("comment-a-<?= $answer->id ?>", "<?= url("game/question/$question->id?admin=comment-answer&adminType=create&answerId=$answer->id") ?>");</script>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
<!-- Answer form -->
<div class="form-answer">
    <?php if ($activeUser) { ?>
        <?= $answerForm ?>
    <?php } else { ?>
        <p class="button-link">
            <a class="button" href=<?= url("user/login") ?>>
                Login to answer
            </a>
        </p>
    <?php } ?>
</div>
