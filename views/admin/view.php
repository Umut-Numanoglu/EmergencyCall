<?php

/** @var yii\web\View $this */
/** @var app\models\Issue $issue */

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
        </div>
    </div>
</div> 