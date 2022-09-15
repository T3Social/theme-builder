<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\commands;

use yii\console\Controller;

class ThemeBuilderController extends Controller
{
    public function actionCompileLess($themeName, $pathToLessc = null)
    {
        CompileLess::runTheme($themeName, $pathToLessc);
    }

    public function actionCompileAllLess($pathToLessc = null)
    {
        CompileLess::runAllThemes($pathToLessc);
    }
}
