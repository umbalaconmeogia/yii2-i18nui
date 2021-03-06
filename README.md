# GUI for editing i18n translation in database.

For more about internationalization in yii2, see [the guide](https://www.yiiframework.com/doc/guide/2.0/en/tutorial-i18n).

This is inspired by [wokster/yii2-translation-manage](https://github.com/wokster/yii2-translation-manager).

## Edit composer.json

Run
```shell
composer require umbalaconmeogia/yii2-i18nui
```

or add `"umbalaconmeogia/yii2-i18nui": "*"` to composer.json then run `composer update`

## Edit config

Add to modules in config

```php
'modules' => [
    'i18nui' => [
        'class' => 'umbalaconmeogia\i18nui\Module',
        'languages' => ['en', 'ja', 'vi'], // Any languages that you want to use
    ],
    // Other stuffs
],
```

Config i18n like:

```php
'components' => [
    'i18n' => [
        'translations' => [
            'yii*' => [
                'class' => 'yii\i18n\PhpMessageSource',
                'basePath' => '@vendor/yiisoft/yii2/messages',
                'sourceLanguage' => 'en'
            ],
            'app' => [
                'class' => 'yii\i18n\DbMessageSource',
                'forceTranslation' => true,
                //'enableCaching' => false,
                //'cachingDuration' => 3600,
            ],
        ],
    ],
    // Other stuffs
],
```

## Run yii2 migration

To add database tables to store i18n data, run migration

```shell
php yii migrate --migrationPath=@yii/i18n/migrations/
```

## Access to translation manager

```
https://<your domain>/?r=i18nui
```

## Select language

This module contains some function to set language for your yii2 application.

1. To change language, add the config below into your web config (*config/web.php* or *frontend/config/main.php*)
  ```php
    return [
        'bootstrap' => [
            [
                'class' => 'umbalaconmeogia\i18nui\components\LanguageSelector',
                'supportedLanguages' => ['vi', 'ja'],
            ],
            // Other stuffs.
        ],
    ],
  ```
2. To add link to change config, use */i18nui/default/set-language*.
  For example, add to menu
  ```php
    $languages = [
        'vi' => 'Tiếng Việt',
        'ja' => '日本語',
    ];
    $callbackUrl = Yii::$app->request->getAbsoluteUrl();

    foreach ($languages as $langCode => $langName) {
        $menuItems[] = [
            'label' => $langName,
            'url' => Url::to(['/i18nui/default/set-language',
            'language' => $langCode, 'callbackUrl' => $callbackUrl]),
        ];
    }
  ```
## Import message data from CSV

To import i18n data from CSV, run command
```shell
php yii i18nui/import/csv i18n.csv
```
while i18n.csv is created as bellow (the first row is the header, with the first key is *category*, the second key is *message*, follow by language codes)
| category | message | ja | vi |
|--|--|--|--|
| app | house | 家 | nhà |
| app | home | 家庭 | gia đình |

## Add language related menu items

### Add menu items as sub-menu

Add language related menu as sub menu items on menu bar.

![language sub menu](docs/material/languageSubMenu.png)

```php
    $languageMenuItems = [];
    $languageMenuItems[] = ['label' => Yii::t('app', 'Translation manager'), 'url' => ['/i18nui']];

    $languages = [
        'vi' => 'Tiếng Việt',
        'ja' => '日本語',
    ];
    $callbackUrl = Yii::$app->request->getAbsoluteUrl();

    foreach ($languages as $langCode => $langName) {
        $languageMenuItems[] = ['label' => $langName, 'url' => Url::to(['/i18nui/default/set-language', 'language' => $langCode, 'callbackUrl' => $callbackUrl])];
    }

    $menuItems[] = [
        'label' => Yii::t('app', 'Language'),
        'items' => $languageMenuItems,
    ];
```

### Add menu items as sub-sub-menu

Add language related menu as an item of sub menu on menu bar.

Use [kartik-v/yii2-nav-x](https://demos.krajee.com/nav-x) to display sub-sub-menu easily.

![language sub sub menu](docs/material/languageSubSubMenu.png)

```php
    $languageMenuItems = [];
    $languageMenuItems[] = ['label' => Yii::t('app', 'Translation manager'), 'url' => ['/i18nui']];

    $languages = [
        'vi' => 'Tiếng Việt',
        'ja' => '日本語',
    ];
    $callbackUrl = Yii::$app->request->getAbsoluteUrl();

    foreach ($languages as $langCode => $langName) {
        $languageMenuItems[] = ['label' => $langName, 'url' => Url::to(['/i18nui/default/set-language', 'language' => $langCode, 'callbackUrl' => $callbackUrl])];
    }
    $adminMenuItems[] = [
        'label' => Yii::t('app', 'Language'),
        'items' => $languageMenuItems,
    ];

    $menuItems[] = [
        'label' => Yii::t('app', 'Admin'),
        'items' => $adminMenuItems,
    ];
    // Other stuffs

    echo NavX::widget([
        // 'options' => ['class' => 'ml-3 navbar-nav navbar-left'],
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => FALSE,
        'items' => $menuItems,
    ]);
```

## Use another Message, SourceMessage classes

If you want to use your own Message, SourceMessage classes, define them and declared in configuration of i18nui.

For example.

Config
```php
    'modules' => [
        'i18nui' => [
            'class' => 'umbalaconmeogia\i18nui\Module',
            'languages' => ['ja', 'vi'], // Any languages that you want to use
            'modelMessageClass' => 'frontend\models\NewMessage',
        ],
    ],
```

Class `frontent\models\NewMessage`
```php
namespace frontend\models;
class NewMessage extends \umbalaconmeogia\i18nui\models\Message
{
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        \Yii::trace('New afterSave', __METHOD__);
    }
}
```