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
use common\models\Comment;
use common\models\User;

/**
 * Issue controller
 */
class IssueController extends Controller
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
                            return Yii::$app->user->identity->isPatient();
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
     * Lists all issues for the current patient.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Issue::find()
            ->where(['patient_id' => Yii::$app->user->id])
            ->with(['patient', 'assignedDoctor', 'receptionist', 'comments', 'issueLabels'])
            ->orderBy(['created_at' => SORT_DESC]);

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
     * Displays a single issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $issue = $this->findModel($id);
        
        // Ensure the current user is the patient who created this issue
        if ($issue->patient_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $comment = new Comment();
        $comment->issue_id = $id;
        $comment->user_id = Yii::$app->user->id;

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            Yii::$app->session->setFlash('success', 'Comment added successfully.');
            return $this->refresh();
        }

        return $this->render('view', [
            'issue' => $issue,
            'comment' => $comment,
        ]);
    }

    /**
     * Creates a new issue.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Issue();
        $model->patient_id = Yii::$app->user->id;
        $model->priority = Issue::PRIORITY_MEDIUM;
        $model->status = Issue::STATUS_OPEN;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Issue created successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
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