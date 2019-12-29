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
                <?php if (isset($adminForm) && $admin === "question") { ?>
                    <div class="form-q-a">
                        <?= $adminForm ?>
                    </div>
                <?php } else { ?>
                    <?php
                        $edit = $activeUser ? "&nbsp;&nbsp;&nbsp;&nbsp;<i id='question-edit-$question->id' class='fas fa-edit'></i>" : null;
                        $delete = $activeUser ? "<i id='question-delete-$question->id' class='fas fa-trash-alt'></i>" : null;
                    ?>
                    <?= $textFilter->doFilter("$question->text<br>$question->user $question->created $edit $delete", "markdown") ?>
                    <?php if ($activeUser) { ?>
                        <script>
                            reloadHere("question-edit-<?= $question->id ?>", "<?= url("game/question/$question->id?admin=question&adminType=edit") ?>");
                            reloadHere("question-delete-<?= $question->id ?>", "<?= url("game/question/$question->id?admin=question&adminType=delete") ?>");
                        </script>
                    <?php } ?>
                <?php } ?>
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
                        <?php if ($activeUser) { ?>
                            <?php
                                $voteDown = "&nbsp;&nbsp;&nbsp;&nbsp;<i id='comment-vote-down-q-$comment->id' class='fas fa-arrow-alt-circle-down'></i>";
                                $voteUp = "<i id='comment-vote-up-q-$comment->id' class='fas fa-arrow-alt-circle-up'></i>";
                                $edit = "&nbsp;&nbsp;&nbsp;&nbsp;<i id='comment-edit-q-$comment->id' class='fas fa-edit'></i>";
                                $delete = "<i id='comment-delete-q-$comment->id' class='fas fa-trash-alt'></i>";
                            ?>
                            <?= $textFilter->doFilter("$comment->text - $comment->user $comment->created $voteDown $comment->vote $voteUp $edit $delete", "markdown") ?>
                            <script>
                                reloadHere("comment-vote-down-q-<?= $comment->id ?>", "<?= url("game/vote/comment-question/$comment->id/down") ?>");
                                reloadHere("comment-vote-up-q-<?= $comment->id ?>", "<?= url("game/vote/comment-question/$comment->id/up") ?>");
                                reloadHere("comment-edit-q-<?= $comment->id ?>", "<?= url("game/question/$question->id?admin=comment-question&adminType=edit&adminId=$comment->id") ?>");
                                reloadHere("comment-delete-q-<?= $comment->id ?>", "<?= url("game/question/$question->id?admin=comment-question&adminType=delete&adminId=$comment->id") ?>");
                            </script>
                        <?php } else { ?>
                            <?= $textFilter->doFilter("$comment->text - $comment->user $comment->created", "markdown") ?>
                        <?php } ?>
                    </p>
                <?php endforeach ?>
            </div>
            <!-- Comment form -->
            <?php if ($activeUser) { ?>
                <div class="flex-row">
                    <?php if (isset($adminForm) && $admin === "comment-question") { ?>
                        <div class="form-comment">
                            <?= $adminForm ?>
                        </div>
                    <?php } else { ?>
                        <button id="comment-q-<?= $question->id ?>">Add comment</button>
                        <script>reloadHere("comment-q-<?= $question->id ?>", "<?= url("game/question/$question->id?admin=comment-question&adminType=create") ?>");</script>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
