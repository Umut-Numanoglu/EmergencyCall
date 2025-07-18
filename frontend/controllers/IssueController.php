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
use yii\data\ActiveDataProvider;

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
     * Lists all issues for the current user.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        if ( $user->isPatient() ) {
            $query = Issue::find()->where(['patient_id' => $user->id]);
        } elseif ( $user->isReception() ) {
            $query = Issue::find();
        } elseif ( $user->isDoctor() ) {
            $query = Issue::find()->where(['assigned_doctor_id' => $user->id]);
        } else {
            $query = Issue::find()->where('0=1'); // no results
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['created_at' => SORT_DESC]),
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
        $comment = new Comment();

        if ( $comment->load(Yii::$app->request->post()) ) {
            // Set required fields before validation
            $comment->issue_id = $id;
            $comment->user_id = Yii::$app->user->id;
            
            if ( $comment->validate() ) {
                if ( $comment->save() ) {
                    Yii::$app->session->setFlash('success', 'Comment added successfully.');
                    return $this->redirect(['view', 'id' => $id]);
                }
            }
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
        $user = Yii::$app->user->identity;

        if ( $model->load(Yii::$app->request->post()) ) {
            // Set patient_id before validation
            $model->patient_id = $user->id;
            // if the priority is not set, set it to low
            if ( empty($model->priority) ) {
                $model->priority = \common\models\Issue::PRIORITY_LOW;
            }
            
            if ( $model->validate() ) {
                if ( $model->save() ) {
                    Yii::$app->session->setFlash('success', 'Emergency call created successfully.');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        // Only allow updates if user is the patient or has admin privileges
        if ( !$user->isPatient() || $model->patient_id !== $user->id ) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ( $model->load(Yii::$app->request->post()) && $model->save() ) {
            Yii::$app->session->setFlash('success', 'Issue updated successfully.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing issue.
     *
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;

        // Only allow deletion if user is the patient
        if ( !$user->isPatient() || $model->patient_id !== $user->id ) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $model->delete();
        Yii::$app->session->setFlash('success', 'Issue deleted successfully.');

        return $this->redirect(['index']);
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
        /** @var \common\models\User $user */
        $user = Yii::$app->user->identity;
        $query = Issue::find()->where(['id' => $id]);

        // Filter based on user role
        if ( $user->isPatient() ) {
            $query->andWhere(['patient_id' => $user->id]);
        } elseif ( $user->isDoctor() ) {
            $query->andWhere(['assigned_doctor_id' => $user->id]);
        }
        // Reception can see all issues

        $model = $query->one();
        if ( $model === null ) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
} 