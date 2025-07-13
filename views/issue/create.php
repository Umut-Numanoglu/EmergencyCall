<?php

/** @var yii\web\View $this */
/** @var app\models\Issue $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Create Emergency Call';
?>
<div class="issue-create">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><?= Html::encode($this->title) ?></h1>
        <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Emergency Call Details</h5>
                </div>
                <div class="card-body">
                    <?php $form = ActiveForm::begin(); ?>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true, 'placeholder' => 'Brief description of the emergency']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <?= $form->field($model, 'description')->textarea(['rows' => 6, 'placeholder' => 'Please provide detailed information about your emergency situation...']) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <?= $form->field($model, 'priority')->dropDownList([
                                'low' => 'Low',
                                'medium' => 'Medium', 
                                'high' => 'High',
                                'critical' => 'Critical'
                            ], ['prompt' => 'Select priority level']) ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <?= Html::submitButton('Create Emergency Call', ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>Priority Levels:</h6>
                    <ul class="list-unstyled">
                        <li><span class="badge bg-info">Low</span> - Non-urgent issues</li>
                        <li><span class="badge bg-warning">Medium</span> - Moderate urgency</li>
                        <li><span class="badge bg-danger">High</span> - Urgent medical attention needed</li>
                        <li><span class="badge bg-danger">Critical</span> - Life-threatening emergency</li>
                    </ul>
                    
                    <hr>
                    
                    <h6>Please include:</h6>
                    <ul>
                        <li>Your symptoms</li>
                        <li>When they started</li>
                        <li>Any relevant medical history</li>
                        <li>Current medications</li>
                        <li>Emergency contact information</li>
                    </ul>
                    
                    <div class="alert alert-warning">
                        <strong>Important:</strong> For life-threatening emergencies, please call emergency services immediately.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 