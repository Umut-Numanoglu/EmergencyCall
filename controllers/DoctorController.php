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
use app\models\Comment;

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
                            return Yii::$app->user->identity->isDoctor();
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
     * Lists all issues assigned to the current doctor.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $issues = Issue::find()
            ->where(['assigned_doctor_id' => Yii::$app->user->id])
            ->with(['patient', 'receptionist'])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

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
        $comment = new Comment();

        if ($comment->load(Yii::$app->request->post()) && $comment->validate()) {
            $comment->issue_id = $id;
            $comment->user_id = Yii::$app->user->id;
            if ($comment->save()) {
                Yii::$app->session->setFlash('success', 'Comment added successfully.');
                return $this->redirect(['view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'issue' => $issue,
            'comment' => $comment,
        ]);
    }

    /**
     * Updates an existing issue (doctors can update status and add comments).
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Issue updated successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Closes an issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionClose($id)
    {
        $model = $this->findModel($id);
        $model->status = Issue::STATUS_CLOSED;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Issue closed successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to close issue.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Reopens an issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReopen($id)
    {
        $model = $this->findModel($id);
        $model->status = Issue::STATUS_OPEN;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Issue reopened successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to reopen issue.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Sets issue status to in progress.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionStartProgress($id)
    {
        $model = $this->findModel($id);
        $model->status = Issue::STATUS_IN_PROGRESS;
        
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Issue marked as in progress.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to update issue status.');
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
        $model = Issue::find()
            ->where(['id' => $id, 'assigned_doctor_id' => Yii::$app->user->id])
            ->one();
            
        if ($model === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
} 