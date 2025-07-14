<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'Cases';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doctor-index">

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
            'created_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {close}',
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
                    'close' => function ($url, $model) {
                        if ($model->status !== 'closed') {
                            return Html::a('<i class="fas fa-check"></i>', ['close', 'id' => $model->id], [
                                'title' => 'Mark as Closed',
                                'class' => 'btn btn-sm btn-outline-success',
                                'data' => [
                                    'confirm' => 'Are you sure you want to mark this case as closed?',
                                    'method' => 'post',
                                ],
                            ]);
                        }
                        return '';
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div> 