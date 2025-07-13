<?php

/* @var $this yii\web\View */
/* @var $issue common\models\Issue */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Update Issue: ' . $issue->title;
$this->params['breadcrumbs'][] = ['label' => 'Manage Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $issue->title, 'url' => ['view', 'id' => $issue->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="admin-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="issue-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($issue, 'priority')->dropDownList([
            'low' => 'Low',
            'medium' => 'Medium',
            'high' => 'High',
            'critical' => 'Critical',
        ]) ?>

        <?= $form->field($issue, 'status')->dropDownList([
            'open' => 'Open',
            'in_progress' => 'In Progress',
            'closed' => 'Closed',
        ]) ?>

        <div class="form-group">
            <label>Labels (comma-separated)</label>
            <input type="text" name="labels[]" class="form-control" value="<?= implode(', ', array_map(function($label) { return $label->label; }, $issue->issueLabels)) ?>" placeholder="e.g., cardiology, urgent, emergency">
        </div>

        <div class="form-group">
            <?= Html::submitButton('Update Issue', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Cancel', ['view', 'id' => $issue->id], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div> 