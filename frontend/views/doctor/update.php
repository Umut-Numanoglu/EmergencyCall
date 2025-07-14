<?php

/* @var $this yii\web\View */
/* @var $issue common\models\Issue */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Update Case: ' . $issue->title;
$this->params['breadcrumbs'][] = ['label' => 'Cases', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $issue->title, 'url' => ['view', 'id' => $issue->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="doctor-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5>Case Details</h5>
            <p><strong>Patient:</strong> <?= Html::encode($issue->patient->getFullName()) ?></p>
            <p><strong>Title:</strong> <?= Html::encode($issue->title) ?></p>
            <p><strong>Description:</strong> <?= nl2br(Html::encode($issue->description)) ?></p>
        </div>
    </div>

    <div class="mt-4">
        <h5>Update Case Status</h5>
        
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($issue, 'status')->dropDownList([
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'closed' => 'Closed',
        ]) ?>

        <div class="form-group">
            <?= Html::submitButton('Update Case', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['view', 'id' => $issue->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div> 