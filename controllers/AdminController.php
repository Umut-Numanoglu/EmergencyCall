<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\models\Issue;
use app\models\User;
use app\models\IssueLabel;

/**
 * Admin controller for reception staff
 */
class AdminController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->isReception();
                        }
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all issues for reception staff.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $issues = Issue::find()->with(['patient', 'assignedDoctor', 'receptionist'])->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('index', [
            'issues' => $issues,
        ]);
    }

    /**
     * Displays a single issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $issue = $this->findModel($id);

        return $this->render('view', [
            'issue' => $issue,
        ]);
    }

    /**
     * Updates an existing issue (reception can assign doctors and update status).
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $doctors = User::find()->where(['role' => User::ROLE_DOCTOR])->all();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Handle doctor assignment
            $doctorId = Yii::$app->request->post('assigned_doctor_id');
            if ($doctorId) {
                $model->assigned_doctor_id = $doctorId;
                $model->receptionist_id = Yii::$app->user->id;
                $model->save();
            }
            
            // Handle labels
            $labelsInput = Yii::$app->request->post('labels', []);
            if (is_array($labelsInput) && !empty($labelsInput[0])) {
                // Remove existing labels
                IssueLabel::deleteAll(['issue_id' => $model->id]);
                
                // Parse and save new labels
                $labels = array_map('trim', explode(',', $labelsInput[0]));
                foreach ($labels as $labelText) {
                    if (!empty($labelText)) {
                        $label = new IssueLabel();
                        $label->issue_id = $model->id;
                        $label->label = $labelText;
                        $label->save();
                    }
                }
            }
            
            Yii::$app->session->setFlash('success', 'Issue updated successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Assigns a doctor to an issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAssignDoctor($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $doctorId = Yii::$app->request->post('doctor_id');
            $model->assigned_doctor_id = $doctorId;
            $model->receptionist_id = Yii::$app->user->id;
            
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Doctor assigned successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to assign doctor.');
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Adds a label to an issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionAddLabel($id)
    {
        $issue = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $label = new IssueLabel();
            $label->issue_id = $id;
            $label->label = Yii::$app->request->post('label');
            
            if ($label->save()) {
                Yii::$app->session->setFlash('success', 'Label added successfully.');
            } else {
                Yii::$app->session->setFlash('error', 'Failed to add label.');
            }
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Removes a label from an issue.
     *
     * @param integer $id
     * @param integer $labelId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRemoveLabel($id, $labelId)
    {
        $issue = $this->findModel($id);
        $label = IssueLabel::findOne($labelId);
        
        if ($label && $label->issue_id == $id) {
            $label->delete();
            Yii::$app->session->setFlash('success', 'Label removed successfully.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Finds the Issue model based on its primary key value.
     *
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Issue::find()->where(['id' => $id])->one();
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
} 