<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * Issue model
 *
 * @property integer $id
 * @property integer $patient_id
 * @property string $title
 * @property string $description
 * @property string $priority
 * @property string $status
 * @property integer $assigned_doctor_id
 * @property integer $receptionist_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $patient
 * @property User $assignedDoctor
 * @property User $receptionist
 * @property Comment[] $comments
 * @property IssueLabel[] $labels
 */
class Issue extends ActiveRecord
{
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_CRITICAL = 'critical';

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_CLOSED = 'closed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'issues';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['patient_id', 'title', 'description'], 'required'],
            [['patient_id', 'assigned_doctor_id', 'receptionist_id'], 'integer'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['priority'], 'in', 'range' => [self::PRIORITY_LOW, self::PRIORITY_MEDIUM, self::PRIORITY_HIGH, self::PRIORITY_CRITICAL]],
            [['status'], 'in', 'range' => [self::STATUS_OPEN, self::STATUS_IN_PROGRESS, self::STATUS_CLOSED]],
            [['priority'], 'default', 'value' => self::PRIORITY_MEDIUM],
            [['status'], 'default', 'value' => self::STATUS_OPEN],
            [['patient_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['patient_id' => 'id']],
            [['assigned_doctor_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['assigned_doctor_id' => 'id']],
            [['receptionist_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['receptionist_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'patient_id' => 'Patient',
            'title' => 'Title',
            'description' => 'Description',
            'priority' => 'Priority',
            'status' => 'Status',
            'assigned_doctor_id' => 'Assigned Doctor',
            'receptionist_id' => 'Receptionist',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[patient]].
     */
    public function getPatient()
    {
        return $this->hasOne(User::class, ['id' => 'patient_id']);
    }

    /**
     * Gets query for [[assignedDoctor]].
     */
    public function getAssignedDoctor()
    {
        return $this->hasOne(User::class, ['id' => 'assigned_doctor_id']);
    }

    /**
     * Gets query for [[receptionist]].
     */
    public function getReceptionist()
    {
        return $this->hasOne(User::class, ['id' => 'receptionist_id']);
    }

    /**
     * Gets query for [[comments]].
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['issue_id' => 'id']);
    }

    /**
     * Gets query for [[labels]].
     */
    public function getLabels()
    {
        return $this->hasMany(IssueLabel::class, ['issue_id' => 'id']);
    }

    /**
     * Get priority options
     */
    public static function getPriorityOptions()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_CRITICAL => 'Critical',
        ];
    }

    /**
     * Get status options
     */
    public static function getStatusOptions()
    {
        return [
            self::STATUS_OPEN => 'Open',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_CLOSED => 'Closed',
        ];
    }
} 