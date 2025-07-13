<?php

/** @var yii\web\View $this */
/** @var app\models\Issue[] $issues */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'All Emergency Calls';
?>
<div class="doctor-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php if (empty($issues)): ?>
        <div class="alert alert-info">
            <p>No emergency calls available.</p>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($issues as $issue): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0"><?= Html::encode($issue->title) ?></h5>
                            <span class="badge bg-<?= $issue->priority === 'critical' ? 'danger' : ($issue->priority === 'high' ? 'warning' : 'info') ?>">
                                <?= ucfirst($issue->priority) ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?= Html::encode(substr($issue->description, 0, 100)) ?>...</p>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <strong>Status:</strong> 
                                    <span class="badge bg-<?= $issue->status === 'closed' ? 'success' : ($issue->status === 'in_progress' ? 'warning' : 'secondary') ?>">
                                        <?= ucfirst(str_replace('_', ' ', $issue->status)) ?>
                                    </span>
                                </small>
                            </div>
                            <div class="mb-2">
                                <small class="text-muted">
                                    <strong>Patient:</strong> <?= Html::encode($issue->patient->getFullName()) ?>
                                </small>
                            </div>
                            <?php if ($issue->assignedDoctor): ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <strong>Assigned Doctor:</strong> <?= Html::encode($issue->assignedDoctor->getFullName()) ?>
                                    </small>
                                </div>
                            <?php else: ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <strong>Assigned Doctor:</strong> <span class="text-warning">Unassigned</span>
                                    </small>
                                </div>
                            <?php endif; ?>
                            <?php if ($issue->receptionist): ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <strong>Receptionist:</strong> <?= Html::encode($issue->receptionist->getFullName()) ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Created:</strong> <?= Yii::$app->formatter->asDatetime($issue->created_at) ?>
                                </small>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <?= Html::a('View', ['view', 'id' => $issue->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                                <?php if ($issue->assigned_doctor_id == Yii::$app->user->id): ?>
                                    <?= Html::a('Update', ['update', 'id' => $issue->id], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                                <?php elseif (!$issue->assigned_doctor_id): ?>
                                    <?= Html::a('Assign to Me', ['assign-to-self', 'id' => $issue->id], [
                                        'class' => 'btn btn-outline-success btn-sm',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Are you sure you want to assign this issue to yourself?'
                                    ]) ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 