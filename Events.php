<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder;

use humhub\modules\admin\permissions\ManageModules;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\modules\themebuilder\commands\ThemeBuilderController;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\modules\ui\menu\MenuLink;
use Yii;

class Events
{
    public static function onLayoutAddonInit($event)
    {

    }


    public static function onAdminMenuInit($event)
    {
        if (!Yii::$app->user->can(ManageModules::class)) {
            return;
        }

        /** @var AdminMenu $adminMenu */
        $adminMenu = $event->sender;

        $entry = new MenuLink();

        $entry->setId('tb');
        $entry->setLabel(Yii::t('ThemeBuilderModule.base', 'Theme Builder'));
        $entry->setUrl(['/theme-builder/admin']);
        $entry->setIcon(new Icon(['name' => 'tachometer']));
        $entry->setSortOrder(1000);
        $entry->setIsActive((Yii::$app->controller->module && Yii::$app->controller->module->id === 'theme-builder'));

        $adminMenu->addEntry($entry);
    }

    public static function onConsoleApplicationInit($event)
    {
        $event->sender->controllerMap['theme-builder'] = ThemeBuilderController::class;
    }
}
