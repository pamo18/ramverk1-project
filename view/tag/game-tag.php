<?php

namespace Anax\View;

// Gather incoming variables and use default values if not set
$search = isset($search) ? $search : null;
$results = isset($result["results"]) ? $result["results"] : null;

?>

<h1 class="center">Game Tag - <?= $search ? $search : "Search" ?></h1>

<?php if (isset($form)) { ?>
    <?= $form ?>
<?php } ?>

<?php if ($search && !$results) { ?>
    <p class="center">Sorry there are no results for <?= $search ?></p>
    <?php return;
} else if (!$search && !$results) {
    return;
} ?>

<script type="text/javascript" src="<?= url('js/pamo.js') ?>"></script>

<?php foreach ($results as $row) : ?>
    <!-- Name -->
    <h2 class="center"><?= $row["name"] ?></h2>
    <!-- Image -->
    <?php if ($row["image"]) { ?>
        <div class="flex-align-middle game-tag">
            <a href="<?= $row["image"] ?>">
                <img src="<?= $row["image"] ?>">
            </a>
        </div>
    <?php } ?>
    <!-- Facts -->
    <div class="flex-align-middle-space-evenly center">
        <!-- Platforms -->
        <?php if (isset($row["platforms"])) { ?>
            <div>
                <h4>Platforms</h4>
                <ul>
                    <?php foreach ($row["platforms"] as $platform) : ?>
                        <li><?= $platform["platform"]["name"] ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php } ?>
        <!-- Parents -->
        <?php if (isset($row["parents"])) { ?>
            <div>
                <h4>Owned by</h4>
                <?php foreach ($row["parents"] as $parent) : ?>
                    <p><?= $parent["platform"]["name"] ?></p>
                <?php endforeach ?>
            </div>
        <?php } ?>
        <!-- Released -->
        <?php if (isset($row["released"])) { ?>
        <div>
            <h4>Released</h4>
            <p><?= $row["released"] ?></p>
        </div>
        <?php } ?>
        <!-- Genres -->
        <?php if (isset($row["genres"])) { ?>
            <div>
                <h4>Genres</h4>
                <?php foreach ($row["genres"] as $genre) : ?>
                    <p><?= $genre["name"] ?></p>
                <?php endforeach ?>
            </div>
        <?php } ?>
    </div>
    <!-- Video -->
    <?php if ($row["video"]) { ?>
        <h4 class="center">Video</h4>
        <div class="flex-align-middle game-tag">
            <video width="704" height="480" controls>
                <source src="<?= $row["video"] ?>" type="video/mp4"></source>
                Your browser does not support the video tag.
            </video>
        </div>
    <?php } ?>
<?php endforeach; ?>
