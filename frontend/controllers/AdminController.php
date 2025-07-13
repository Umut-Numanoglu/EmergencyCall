<?php

namespace frontend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use common\models\Issue;
use common\models\IssueLabel;
use common\models\User;

/**
 * Admin controller for receptionists
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
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all issues for receptionists to manage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Issue::find()
            ->with(['patient', 'assignedDoctor', 'receptionist', 'comments', 'issueLabels'])
            ->orderBy(['priority' => SORT_DESC, 'created_at' => SORT_DESC]);

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single issue for receptionist.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $issue = $this->findModel($id);

        if ($issue->load(Yii::$app->request->post()) && $issue->save()) {
            Yii::$app->session->setFlash('success', 'Issue updated successfully.');
            return $this->refresh();
        }

        return $this->render('view', [
            'issue' => $issue,
        ]);
    }

    /**
     * Updates an issue priority and assigns labels.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $issue = $this->findModel($id);

        if ($issue->load(Yii::$app->request->post())) {
            $issue->receptionist_id = Yii::$app->user->id;
            
            if ($issue->save()) {
                // Handle labels
                $labels = Yii::$app->request->post('labels', []);
                
                // Remove existing labels
                IssueLabel::deleteAll(['issue_id' => $issue->id]);
                
                // Add new labels
                foreach ($labels as $label) {
                    if (!empty(trim($label))) {
                        $issueLabel = new IssueLabel();
                        $issueLabel->issue_id = $issue->id;
                        $issueLabel->label = trim($label);
                        $issueLabel->save();
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Issue updated successfully.');
                return $this->redirect(['view', 'id' => $issue->id]);
            }
        }

        return $this->render('update', [
            'issue' => $issue,
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
        $issue = $this->findModel($id);
        $doctors = User::find()->where(['role' => User::ROLE_DOCTOR])->all();

        if (Yii::$app->request->isPost) {
            $doctorId = Yii::$app->request->post('doctor_id');
            if ($doctorId) {
                $issue->assigned_doctor_id = $doctorId;
                $issue->status = Issue::STATUS_IN_PROGRESS;
                if ($issue->save()) {
                    Yii::$app->session->setFlash('success', 'Doctor assigned successfully.');
                    return $this->redirect(['view', 'id' => $issue->id]);
                }
            }
        }

        return $this->render('assign-doctor', [
            'issue' => $issue,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Finds the Issue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Issue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Issue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
} 