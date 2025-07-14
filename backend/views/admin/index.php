<?php

/* @var $this yii\web\View */
/* @var $issues common\models\Issue[] */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Emergency Call Management';
?>
<div class="admin-index">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php if (empty($issues)): ?>
        <div class="alert alert-info">
            <p>No emergency calls found.</p>
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
                                        <strong>Doctor:</strong> <?= Html::encode($issue->assignedDoctor->getFullName()) ?>
                                    </small>
                                </div>
                            <?php else: ?>
                                <div class="mb-2">
                                    <small class="text-muted text-warning">
                                        <strong>Doctor:</strong> Not assigned
                                    </small>
                                </div>
                            <?php endif; ?>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <strong>Created:</strong> <?= Yii::$app->formatter->asDatetime($issue->created_at) ?>
                                </small>
                            </div>
                            
                            <!-- Labels Section -->
                            <?php if (!empty($issue->labels)): ?>
                                <div class="mb-2">
                                    <small class="text-muted">
                                        <strong>Labels:</strong>
                                    </small>
                                    <div class="mt-1">
                                        <?php foreach ($issue->labels as $label): ?>
                                            <span class="badge bg-secondary me-1"><?= Html::encode($label->label) ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="btn-group w-100" role="group">
                                <?= Html::a('View', ['view', 'id' => $issue->id], ['class' => 'btn btn-outline-primary btn-sm']) ?>
                                <?= Html::a('Update', ['update', 'id' => $issue->id], ['class' => 'btn btn-outline-secondary btn-sm']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div> 