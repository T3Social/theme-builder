<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\models;

use humhub\modules\file\libs\FileHelper;
use humhub\modules\themebuilder\helpers\LessHelper;
use humhub\modules\themebuilder\Module;
use Yii;
use yii\base\Model;


/**
 * Class LessVariables
 */
class CreateTheme extends Model
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $baseTheme;

    public function attributeLabels()
    {
        return [
            'name' => Yii::t('ThemeBuilderModule.base', 'Name'),
            'baseTheme' => Yii::t('ThemeBuilderModule.base', 'Base theme'),
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('theme-builder');

        return [
            [['baseTheme', 'name'], 'required'],
            [['name'], 'string', 'min' => 3, 'max' => 16],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' => Yii::t('ThemeBuilderModule.base', 'Name can only contain alphanumeric characters, underscores and dashes.')],
            [['baseTheme'], 'in', 'range' => array_keys($module->getBaseThemes())],
            [['name'], 'in', 'range' => array_keys($module->getBaseThemes()), 'not' => true]
        ];
    }

    public function save()
    {
        /** @var Module $module */
        $module = Yii::$app->getModule('theme-builder');

        $themes = $module->getBaseThemes();

        $fromDir = $themes[$this->baseTheme]->getBasePath();
        $toDir = $module->getThemesDir() . '/' . $this->name;

        FileHelper::copyDirectory($fromDir, $toDir, [
            'recursive' => true,
            'copyEmptyDirectories' => true,
            'except' => ['/views/']
        ]);
        mkdir($toDir.'/views');

        LessHelper::updateVariables(['baseTheme' => $this->baseTheme], $toDir . '/less/variables.less');


        return true;
    }

}