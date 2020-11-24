<?php

namespace umbalaconmeogia\i18nui\models;

use batsg\models\BaseModel;
use umbalaconmeogia\i18nui\helpers\HModule;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "source_message".
 *
 * @property int $id
 * @property string|null $category
 * @property string|null $message
 *
 * @property Message[] $messages
 */
class SourceMessage extends BaseModel
{
    /**
     * Translation in languages.
     * Mapping between language and translation.
     * @var string[]
     */
    public $languages;

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        foreach (HModule::languages() as $language){
            $config['languages'][$language] = null;
        }
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'source_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message'], 'string'],
            [['category'], 'string', 'max' => 255],
            ['languages', 'safe'],
            ['category', 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18nui', 'ID'),
            'category' => Yii::t('i18nui', 'Category'),
            'message' => Yii::t('i18nui', 'Message'),
        ];
    }

    /**
     * Gets query for [[Messages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        // TODO: Don't know why it cause error if using HModule::modelMessageClass() here.
        return $this->hasMany(Message::class, ['id' => 'id']);
    }

    /**
     * Get translations of this SourceMessage, indexed by language.
     * @return string[]
     */
    public function getTranslations()
    {
        return ArrayHelper::map($this->messages, 'language', 'translation');
    }

    public function getTranslation($lang){
        return (isset($this->translations[$lang])) ? $this->translations[$lang] : null;
    }

    /**
     * Get all translation and set to $languages.
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        foreach ($this->languages as $language => $translation){
            $this->languages[$language] = $this->getTranslation($language);
        }
    }

    /**
     * Delete all relative translations before delete SourceMessage.
     * @inheritdoc
     */
    public function beforeDelete()
    {
        $result = parent::beforeDelete();

        if ($result) {
            HModule::modelMessageClass()::deleteAll(['id' => $this->id]);
        }
        return $result;
    }

    /**
     * Create/Update relative Message from $language.
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        foreach ($this->languages as $language => $translation) {
            $model = HModule::modelMessageClass()::findOneCreateNew([
                'id' => $this->id,
                'language' => $language,
            ]);
            $model->translation = empty($translation) ? NULL : $translation;
            $model->saveThrowError();
        }

        parent::afterSave($insert, $changedAttributes);

        // Clear cache.
        // We should clear application cache because we cannot clear only cache managed by SourceMessage
        Yii::$app->cache->flush();
    }
}
