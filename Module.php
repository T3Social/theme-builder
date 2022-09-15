<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder;

use humhub\components\Theme;
use humhub\libs\ThemeHelper;
use Yii;
use yii\helpers\Url;

class Module extends \humhub\components\Module
{

    public $resourcesPath = 'resources';

    /**
     * {@inheritdoc}
     */
    public function getConfigUrl()
    {
        return Url::to(['/theme-builder/admin']);
    }


    public function getEditableThemes()
    {
        $themes = [];
        foreach (ThemeHelper::getThemesByPath($this->getThemesDir()) as $k => $theme) {
            if (in_array($theme->name, ['HumHub'])) {
                continue;
            }
            $themes[$theme->name] = $theme;
        }
        return $themes;
    }

    /**
     * @return Theme[]
     */
    public function getBaseThemes()
    {
        $themes = [];
        foreach (ThemeHelper::getThemes() as $theme) {
            $themes[$theme->name] = $theme;
        }
        return $themes;
    }


    public function getThemesDir()
    {
        return Yii::getAlias('@webroot/themes');

    }

}
