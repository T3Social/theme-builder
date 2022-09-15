<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\widgets;

use Yii;
use yii\helpers\Url;

class ThemeMenu extends \humhub\widgets\BaseMenu
{

    public $template = "@humhub/widgets/views/tabMenu";
    public $theme;


    public function init()
    {

        $this->addItem([
            'label' => Yii::t('ThemeBuilderModule.base', 'Overview'),
            'url' => Url::to(['/theme-builder/theme', 'name' => $this->theme->name]),
            'sortOrder' => 50,
            'isActive' => (Yii::$app->controller->action->id === 'index'),
        ]);

        $this->addItem([
            'label' => Yii::t('ThemeBuilderModule.base', 'Stylesheet'),
            'url' => Url::to(['/theme-builder/theme/less', 'name' => $this->theme->name]),
            'sortOrder' => 100,
            'isActive' => (Yii::$app->controller->action->id === 'less'),
        ]);


        if (version_compare(Yii::$app->version, 1.4, '<')) {
            $this->addItem([
                'label' => Yii::t('ThemeBuilderModule.base', 'Icon'),
                'url' => Url::to(['/theme-builder/theme/icon', 'name' => $this->theme->name]),
                'sortOrder' => 200,
                'isActive' => (Yii::$app->controller->action->id === 'icon'),
            ]);

        }

        $this->addItem([
            'label' => Yii::t('ThemeBuilderModule.base', 'Login'),
            'url' => Url::to(['/theme-builder/theme/login', 'name' => $this->theme->name]),
            'sortOrder' => 300,
            'isActive' => (Yii::$app->controller->action->id === 'login'),
        ]);

        $this->addItem([
            'label' => Yii::t('ThemeBuilderModule.base', 'Views'),
            'url' => Url::to(['/theme-builder/theme/views', 'name' => $this->theme->name]),
            'sortOrder' => 400,
            'isActive' => (in_array(Yii::$app->controller->action->id, ['views', 'edit-view'])),
        ]);

        parent::init();
    }

}
