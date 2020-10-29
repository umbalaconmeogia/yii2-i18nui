<?php
namespace umbalaconmeogia\i18nui\actions;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Cookie;

/**
 * SetLanguageAction is to select language for i18n.
 */
class SetLanguageAction extends \yii\base\Action
{
    const PARAM_LANGUAGE = 'language';
    const PARAM_CALLBACK_URL = 'callbackUrl';

    /**
     * Set language then return back to $callbackUrl
     */
    public function run()
    {
        // Redirect to callbackUrl.
        $callbackUrl = Yii::$app->request->get(self::PARAM_CALLBACK_URL);
        $callbackHost = parse_url($callbackUrl, PHP_URL_HOST);
        if ($callbackHost != Yii::$app->request->hostName) {
            throw new BadRequestHttpException("Invalid " . self::PARAM_CALLBACK_URL);
        }

        $language = Yii::$app->request->get(self::PARAM_LANGUAGE);
        Yii::$app->language = $language;

        $languageCookie = new Cookie([
            'name' => 'language',
            'value' => $language,
            'expire' => time() + 60 * 60 * 24 * 30, // 30 days
        ]);
        Yii::$app->response->cookies->add($languageCookie);

        return $callbackUrl ? $this->controller->redirect($callbackUrl) : "Language is set to {$language}";
    }
}
