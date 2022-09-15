<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\components\console\Application;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\LayoutAddons;


/** @noinspection MissedFieldInspection */
return [
    'id' => 'theme-builder',
    'class' => humhub\modules\themebuilder\Module::class,
    'namespace' => 'humhub\modules\themebuilder',
    'events' => [
        [LayoutAddons::class, LayoutAddons::EVENT_INIT, ['humhub\modules\themebuilder\Events', 'onLayoutAddonInit']],
        [AdminMenu::class, AdminMenu::EVENT_INIT, ['humhub\modules\themebuilder\Events', 'onAdminMenuInit']],
        [Application::class, Application::EVENT_ON_INIT, ['humhub\modules\themebuilder\Events', 'onConsoleApplicationInit']],
    ]
];
