<?php

/* @var $this yii\web\View */
/* @var $issue common\models\Issue */
/* @var $doctors common\models\User[] */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Assign Doctor: ' . $issue->title;
$this->params['breadcrumbs'][] = ['label' => 'Manage Issues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $issue->title, 'url' => ['view', 'id' => $issue->id]];
$this->params['breadcrumbs'][] = 'Assign Doctor';
?>
<div class="admin-assign-doctor">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="card">
        <div class="card-body">
            <h5>Issue Details</h5>
            <p><strong>Title:</strong> <?= Html::encode($issue->title) ?></p>
            <p><strong>Patient:</strong> <?= Html::encode($issue->patient->getFullName()) ?></p>
            <p><strong>Priority:</strong> <?= $issue->getPriorityLabel() ?></p>
            <p><strong>Status:</strong> <?= $issue->getStatusLabel() ?></p>
        </div>
    </div>

    <div class="mt-4">
        <h5>Select Doctor</h5>
        
        <form method="post">
            <div class="form-group">
                <label for="doctor_id">Choose a doctor to assign:</label>
                <select name="doctor_id" id="doctor_id" class="form-control">
                    <option value="">Select a doctor...</option>
                    <?php foreach ($doctors as $doctor): ?>
                        <option value="<?= $doctor->id ?>" <?= $issue->assigned_doctor_id == $doctor->id ? 'selected' : '' ?>>
                            <?= Html::encode($doctor->getFullName()) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group mt-3">
                <?= Html::submitButton('Assign Doctor', ['class' => 'btn btn-success']) ?>
                <?= Html::a('Cancel', ['view', 'id' => $issue->id], ['class' => 'btn btn-secondary']) ?>
            </div>
        </form>
    </div>

</div> 