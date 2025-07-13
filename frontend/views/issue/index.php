<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title = 'My Issues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="issue-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create New Issue', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
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
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => 'View',
                            'class' => 'btn btn-sm btn-outline-primary',
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div> 