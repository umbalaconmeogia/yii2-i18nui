<?php
namespace umbalaconmeogia\i18nui;

use umbalaconmeogia\i18nui\models\Message;
use umbalaconmeogia\i18nui\models\SourceMessage;
use umbalaconmeogia\i18nui\models\SourceMessageSearch;
use Yii;
use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    /**
     * Translation languages.
     * @var string[]
     */
    public $languages = ['en', 'ja'];

    /**
     * Message categories.
     * @var string[]
     */
    public $messageCategories = ['app'];

    /**
     * Message category for translation of this module.
     * @var string
     */
    public $moduleCategory = 'i18nui';

    /**
     * Model class for Message.
     * @var string
     */
    public $modelMessageClass = Message::class;

    /**
     * Model class for SourceMessage.
     * @var string
     */
    public $modelSourceMessageClass = SourceMessage::class;

    /**
     * Model class for SourceMessageSearch.
     * @var string
     */
    public $modelSourceMessageSearchClass = SourceMessageSearch::class;

    /**
     * Add configuration for command line.
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();

        // Config for command line.
        if (Yii::$app instanceof \yii\console\Application) {
            $this->controllerNamespace = 'umbalaconmeogia\i18nui\commands';
        }
    }

    public function behaviors()
    {
        $behaviors = [];
        if (! Yii::$app instanceof \yii\console\Application) {
            $behaviors = [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['set-language'],
                            'roles' => ['?'],
                        ],
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Registeration translation files.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['i18nui'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'forceTranslation' => true,
            'basePath' => '@umbalaconmeogia/i18nui/messages',
            'fileMap' => [
                'i18nui' => 'i18nui.php',
            ],
        ];
    }
}