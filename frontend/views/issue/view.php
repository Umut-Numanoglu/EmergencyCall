<?php

/* @var $this yii\web\View */
/* @var $issue common\models\Issue */
/* @var $comment common\models\Comment */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = $issue->title;
$this->params['breadcrumbs'][] = ['label' => 'My Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card issue-card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-8">
                    <h5 class="mb-0">Issue Details</h5>
                </div>
                <div class="col-md-4 text-end">
                    <span class="badge bg-<?= $issue->priority === 'critical' ? 'danger' : ($issue->priority === 'high' ? 'warning' : 'success') ?>">
                        <?= $issue->getPriorityLabel() ?>
                    </span>
                    <span class="badge bg-<?= $issue->status === 'closed' ? 'success' : ($issue->status === 'in_progress' ? 'warning' : 'primary') ?>">
                        <?= $issue->getStatusLabel() ?>
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h6>Description:</h6>
                    <p><?= nl2br(Html::encode($issue->description)) ?></p>
                </div>
                <div class="col-md-4">
                    <h6>Details:</h6>
                    <ul class="list-unstyled">
                        <li><strong>Created:</strong> <?= Yii::$app->formatter->asDatetime($issue->created_at) ?></li>
                        <li><strong>Updated:</strong> <?= Yii::$app->formatter->asDatetime($issue->updated_at) ?></li>
                        <?php if ($issue->assignedDoctor): ?>
                            <li><strong>Assigned Doctor:</strong> <?= Html::encode($issue->assignedDoctor->getFullName()) ?></li>
                        <?php endif; ?>
                        <?php if ($issue->receptionist): ?>
                            <li><strong>Receptionist:</strong> <?= Html::encode($issue->receptionist->getFullName()) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>

            <?php if ($issue->issueLabels): ?>
                <div class="mt-3">
                    <h6>Labels:</h6>
                    <?php foreach ($issue->issueLabels as $label): ?>
                        <span class="badge bg-secondary label-badge"><?= Html::encode($label->label) ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="comment-section">
        <h4>Comments</h4>
        
        <?php if ($issue->comments): ?>
            <?php foreach ($issue->comments as $comment): ?>
                <div class="comment-item">
                    <div class="d-flex justify-content-between">
                        <strong><?= Html::encode($comment->user->getFullName()) ?></strong>
                        <small class="text-muted"><?= Yii::$app->formatter->asDatetime($comment->created_at) ?></small>
                    </div>
                    <p class="mb-0 mt-2"><?= nl2br(Html::encode($comment->comment)) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">No comments yet.</p>
        <?php endif; ?>

        <div class="mt-4">
            <h5>Add Comment</h5>
            <?php $form = ActiveForm::begin(); ?>
            
            <?= $form->field($comment, 'comment')->textarea(['rows' => 3, 'placeholder' => 'Enter your comment...']) ?>
            
            <div class="form-group">
                <?= Html::submitButton('Add Comment', ['class' => 'btn btn-primary']) ?>
            </div>
            
            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div> 