<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * IssueLabel model
 *
 * @property integer $id
 * @property integer $issue_id
 * @property string $label
 * @property integer $created_at
 */
class IssueLabel extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'issue_labels';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['issue_id', 'label'], 'required'],
            [['issue_id'], 'integer'],
            [['label'], 'string', 'max' => 50],
            [['issue_id'], 'exist', 'skipOnError' => true, 'targetClass' => Issue::class, 'targetAttribute' => ['issue_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'issue_id' => 'Issue',
            'label' => 'Label',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Issue]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIssue()
    {
        return $this->hasOne(Issue::class, ['id' => 'issue_id']);
    }
} 