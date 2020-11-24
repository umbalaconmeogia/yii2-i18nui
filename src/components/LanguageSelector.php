<?php
namespace umbalaconmeogia\i18nui\components;

use yii\base\BootstrapInterface;

/**
 * Read language setting from cookie and set to application language.
 * To use this bootstrap, set it in configuration file.
 * ```php
 *  'bootstrap' => [
 *      'log',
 *      [
 *          'class' => 'umbalaconmeogia\i18nui\components\LanguageSelector',
 *          'supportedLanguages' => ['vi', 'ja'],
 *      ],
 *  ],
 * ```
 */
class LanguageSelector implements BootstrapInterface
{
    public $supportedLanguages = [];

    public function bootstrap($app)
    {
        $preferredLanguage = isset($app->request->cookies['language']) ? (string)$app->request->cookies['language'] : null;

        if (empty($preferredLanguage)) {
            $preferredLanguage = $app->request->getPreferredLanguage($this->supportedLanguages);
        }

        $app->language = $preferredLanguage;
    }
}