<?php

/* @var $this yii\web\View */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $exception->getMessage();
?>
<div class="site-error">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($exception->getMessage())) ?>
    </div>

    <p>
        The above error occurred while the Web server was processing your request.
    </p>
    <p>
        Please contact us if you think this is a server error. Thank you.
    </p>

    <p>
        <a href="<?= \yii\helpers\Url::to(['site/index']) ?>" class="btn btn-primary">Go Home</a>
    </p>

</div> 