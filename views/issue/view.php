<?php

/** @var yii\web\View $this */
/** @var app\models\Issue $issue */
/** @var app\models\Comment $comment */

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\ActiveForm;

$this->title = $issue->title;
?>
<div class="issue-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?php if (Yii::$app->user->identity->isPatient() && $issue->patient_id === Yii::$app->user->id): ?>
                <?= Html::a('Edit', ['update', 'id' => $issue->id], ['class' => 'btn btn-primary']) ?>
            <?php endif; ?>
            <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Issue Details</h5>
                </div>
                <div class="card-body">
                    <?= DetailView::widget([
                        'model' => $issue,
                        'attributes' => [
                            'id',
                            'title',
                            'description:ntext',
                            [
                                'attribute' => 'priority',
                                'value' => ucfirst($issue->priority),
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $class = $model->priority === 'critical' ? 'danger' : 
                                            ($model->priority === 'high' ? 'warning' : 'info');
                                    return Html::tag('span', ucfirst($model->priority), ['class' => "badge bg-{$class}"]);
                                }
                            ],
                            [
                                'attribute' => 'status',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    $class = $model->status === 'closed' ? 'success' : 
                                            ($model->status === 'in_progress' ? 'warning' : 'secondary');
                                    return Html::tag('span', ucfirst(str_replace('_', ' ', $model->status)), ['class' => "badge bg-{$class}"]);
                                }
                            ],
                            [
                                'attribute' => 'patient_id',
                                'value' => $issue->patient ? $issue->patient->getFullName() : 'N/A',
                            ],
                            [
                                'attribute' => 'assigned_doctor_id',
                                'value' => $issue->assignedDoctor ? $issue->assignedDoctor->getFullName() : 'Not assigned',
                            ],
                            [
                                'attribute' => 'receptionist_id',
                                'value' => $issue->receptionist ? $issue->receptionist->getFullName() : 'N/A',
                            ],
                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Comments</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($issue->comments)): ?>
                        <?php foreach ($issue->comments as $comment): ?>
                            <div class="comment mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= Html::encode($comment->user->getFullName()) ?></strong>
                                        <small class="text-muted">(<?= Html::encode($comment->user->role) ?>)</small>
                                    </div>
                                    <small class="text-muted"><?= Yii::$app->formatter->asDatetime($comment->created_at) ?></small>
                                </div>
                                <div class="mt-2">
                                    <?= Html::encode($comment->comment) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No comments yet.</p>
                    <?php endif; ?>

                    <!-- Add Comment Form -->
                    <div class="mt-4">
                        <h6>Add Comment</h6>
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="form-group">
                            <?= $form->field($comment, 'comment')->textarea(['rows' => 3, 'class' => 'form-control'])->label(false) ?>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Add Comment', ['class' => 'btn btn-primary']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Action Buttons -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <?php if (Yii::$app->user->identity->isDoctor() && $issue->assigned_doctor_id === Yii::$app->user->id): ?>
                        <?php if ($issue->status === 'open'): ?>
                            <?= Html::a('Start Progress', ['doctor/start-progress', 'id' => $issue->id], [
                                'class' => 'btn btn-warning btn-block mb-2',
                                'data' => ['method' => 'post', 'confirm' => 'Are you sure you want to start working on this issue?']
                            ]) ?>
                        <?php endif; ?>
                        
                        <?php if ($issue->status === 'in_progress'): ?>
                            <?= Html::a('Close Issue', ['doctor/close', 'id' => $issue->id], [
                                'class' => 'btn btn-success btn-block mb-2',
                                'data' => ['method' => 'post', 'confirm' => 'Are you sure you want to close this issue?']
                            ]) ?>
                        <?php endif; ?>
                        
                        <?php if ($issue->status === 'closed'): ?>
                            <?= Html::a('Reopen Issue', ['doctor/reopen', 'id' => $issue->id], [
                                'class' => 'btn btn-info btn-block mb-2',
                                'data' => ['method' => 'post', 'confirm' => 'Are you sure you want to reopen this issue?']
                            ]) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Labels -->
            <?php if (!empty($issue->labels)): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Labels</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($issue->labels as $label): ?>
                            <span class="badge bg-secondary me-1 mb-1"><?= Html::encode($label->label) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div> 