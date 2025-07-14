<?php

/* @var $this yii\web\View */

$this->title = 'Emergency Call System';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-light">
        <h1 class="display-4">Emergency Call System</h1>
        <p class="lead">A comprehensive platform for managing emergency medical issues</p>
        <hr class="my-4">
        <p>Get immediate assistance for your medical emergencies</p>
        <?php if ( Yii::$app->user->isGuest ): ?>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="<?= \yii\helpers\Url::to(['site/login']) ?>" role="button">Login</a>
                <a class="btn btn-success btn-lg" href="<?= \yii\helpers\Url::to(['site/register']) ?>" role="button">Register</a>
            </p>
        <?php else: ?>
            <p class="lead">
                <a class="btn btn-primary btn-lg" href="<?= \yii\helpers\Url::to(['issue/index']) ?>" role="button">View My Issues</a>
                <a class="btn btn-success btn-lg" href="<?= \yii\helpers\Url::to(['issue/create']) ?>" role="button">Create New Issue</a>
            </p>
        <?php endif; ?>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>For Patients</h2>
                <p>Report your medical issues and get professional assistance. Our system allows you to:</p>
                <ul>
                    <li>Create detailed issue reports</li>
                    <li>Track the status of your cases</li>
                    <li>Communicate with medical staff</li>
                    <li>Receive timely updates</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h2>For Reception</h2>
                <p>Manage and prioritize emergency cases efficiently. Reception staff can:</p>
                <ul>
                    <li>Sort issues by priority</li>
                    <li>Assign appropriate labels</li>
                    <li>Route cases to doctors</li>
                    <li>Monitor case progress</li>
                </ul>
            </div>
            <div class="col-lg-4">
                <h2>For Doctors</h2>
                <p>Handle patient cases and provide medical assistance. Doctors can:</p>
                <ul>
                    <li>Review assigned cases</li>
                    <li>Add medical comments</li>
                    <li>Update case status</li>
                    <li>Close resolved cases</li>
                </ul>
            </div>
        </div>

    </div>
</div> 