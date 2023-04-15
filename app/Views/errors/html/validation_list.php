<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger alert-dismissible fade show pl-0 mt-3 text-left" role="alert">
        <ul>
            <?php foreach ($errors as $error) : ?>
                <li><?= esc($error) ?></li>
            <?php endforeach ?>
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>
    <?php
 endif ?>