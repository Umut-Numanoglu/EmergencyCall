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
 * Doctor controller
 */
class DoctorController extends Controller
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
                            /** @var \common\models\User $user */
                            $user = Yii::$app->user->identity;
                            return $user && $user->isDoctor();
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
     * Lists all issues assigned to the current doctor.
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
     * Displays a single issue for doctor.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        
        $issue = $this->findModel($id);

        // TODO: taken out, we want to make sure that only assigned doctors can see the details
        // at the same time doctors should be able to get some details before taking over it
        // it needs better UX to provide such functionality, most likely rewriting the view is
        // necessary
        // Ensure the current user is the assigned doctor
        //if ($issue->assigned_doctor_id !== Yii::$app->user->id) {
        //    throw new NotFoundHttpException('The requested page does not exist.');
        //}

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
     * Updates an issue status (mark as closed).
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $issue = $this->findModel($id);
        
        // Ensure the current user is the assigned doctor
        if ($issue->assigned_doctor_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($issue->load(Yii::$app->request->post()) && $issue->save()) {
            Yii::$app->session->setFlash('success', 'Issue updated successfully.');
            return $this->redirect(['view', 'id' => $issue->id]);
        }

        return $this->render('update', [
            'issue' => $issue,
        ]);
    }

    /**
     * Marks an issue as closed.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClose($id)
    {
        $issue = $this->findModel($id);
        
        // Ensure the current user is the assigned doctor
        if ($issue->assigned_doctor_id !== Yii::$app->user->id) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $issue->status = Issue::STATUS_CLOSED;
        if ($issue->save()) {
            Yii::$app->session->setFlash('success', 'Issue marked as closed.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to close issue.');
        }

        return $this->redirect(['view', 'id' => $issue->id]);
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