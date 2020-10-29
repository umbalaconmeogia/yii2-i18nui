<?php

namespace umbalaconmeogia\i18nui\helpers;

use umbalaconmeogia\i18nui\Module;

class HModule
{
    /**
     * Get the module instance.
     * @return Module
     */
    public static function instance()
    {
        return Module::getInstance();
    }

    public static function languages()
    {
        return self::instance()->languages;
    }

    /**
     * Generate array from $messageCategories, so that key is value.
     * @return string[]
     */
    public static function messageCategoryOptionArr()
    {
        $categories = self::instance()->messageCategories;
        return array_combine($categories, $categories);
    }

}
