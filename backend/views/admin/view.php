<?php

/* @var $this yii\web\View */
/* @var $issue app\models\Issue */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $issue->title;
?>
<div class="admin-view">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <div>
            <?= Html::a('Update', ['update', 'id' => $issue->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>

    <div class="card">
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
            
            <!-- Labels Section -->
            <div class="mt-4">
                <h6>Labels:</h6>
                <?php if (!empty($issue->labels)): ?>
                    <div class="mb-2">
                        <?php foreach ($issue->labels as $label): ?>
                            <span class="badge bg-secondary me-1"><?= Html::encode($label->label) ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No labels assigned.</p>
                <?php endif; ?>
            </div>
            
            <!-- Doctor Assignment Section -->
            <div class="mt-4">
                <h6>Doctor Assignment:</h6>
                <?php if ($issue->assignedDoctor): ?>
                    <p><strong>Currently Assigned:</strong> <?= Html::encode($issue->assignedDoctor->getFullName()) ?></p>
                <?php else: ?>
                    <p class="text-warning"><strong>No doctor assigned</strong></p>
                <?php endif; ?>
                <p class="text-muted">Use the Update button above to assign or change the doctor.</p>
            </div>
        </div>
    </div>
</div> 