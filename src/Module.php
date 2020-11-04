<?php
namespace umbalaconmeogia\i18nui;

use Yii;

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