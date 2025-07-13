<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Manage Issues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="admin-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'patient_id',
                'value' => function ($model) {
                    return $model->patient ? $model->patient->getFullName() : 'Unknown';
                },
            ],
            'description:ntext',
            [
                'attribute' => 'priority',
                'value' => function ($model) {
                    return $model->getPriorityLabel();
                },
                'contentOptions' => function ($model) {
                    return ['class' => 'priority-' . $model->priority];
                },
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->getStatusLabel();
                },
                'contentOptions' => function ($model) {
                    return ['class' => 'status-' . $model->status];
                },
            ],
            [
                'attribute' => 'assigned_doctor_id',
                'value' => function ($model) {
                    return $model->assignedDoctor ? $model->assignedDoctor->getFullName() : 'Not Assigned';
                },
            ],
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {assign-doctor}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'View',
                            'class' => 'btn btn-sm btn-outline-primary',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => 'Update',
                            'class' => 'btn btn-sm btn-outline-warning',
                        ]);
                    },
                    'assign-doctor' => function ($url, $model) {
                        return Html::a('<i class="fas fa-user-md"></i>', ['assign-doctor', 'id' => $model->id], [
                            'title' => 'Assign Doctor',
                            'class' => 'btn btn-sm btn-outline-success',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div> 