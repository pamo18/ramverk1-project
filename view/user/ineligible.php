<?php

namespace Anax\View;

?>

<script type="text/javascript" src=<?= url('js/pamo.js') ?>></script>

<h1 class="center"><?= $title ?></h1>
<p class="center">You are ineligible to vote for your own content.</p>

<p class="center">
    <a class="button" id="back-button">
        <i class="fas fa-arrow-left"></i>
    </a>
</p>

<script> goBack("back-button") </script>
