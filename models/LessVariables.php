<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\models;

use humhub\components\Theme;
use humhub\modules\themebuilder\helpers\LessHelper;
use humhub\modules\themebuilder\helpers\ThemeHelper;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\HttpException;


/**
 * Class LessVariables
 */
class LessVariables extends Model
{
    /**
     * @var Theme
     */
    public $theme;

    public $compiled;

    public $default = "#ededed";
    public $primary = "#708fa0";
    public $info = "#6fdbe8";
    public $success = "#97d271";
    public $warning = "#fdd198";
    public $danger = "#ff8989";
    public $link = "#6fdbe8";

    public $eeSidebarElementsColor;
    public $eeSidebarWidth;


    public function rules()
    {
        return [
            [['default', 'primary', 'info', 'success', 'warning', 'danger', 'link'], 'safe'],
            [['eeSidebarElementsColor'], 'safe'],
            [['eeSidebarWidth'], 'number'],
            [['compiled'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'eeSidebarWidth' => Yii::t('ThemeBuilderModule.base', 'Sidebar width'),
            'eeSidebarElementsColor' => Yii::t('ThemeBuilderModule.base', 'Sidebar elements')
        ];
    }

    public function loadVariables()
    {
        try {
            $variables = ThemeHelper::getAllVariables($this->theme);
        } catch (Exception $e) {
            Yii::error("Could not fetch theme vars! Error: " . $e->getMessage());
            return;
        }

        $this->default = $variables['default'];
        $this->primary = $variables['primary'];
        $this->info = $variables['info'];
        $this->success = $variables['success'];
        $this->warning = $variables['warning'];
        $this->danger = $variables['danger'];
        $this->link = $variables['link'];

        if (isset($variables['ee-sidebar-elements-color'])) {
            $this->eeSidebarElementsColor = $variables['ee-sidebar-elements-color'];
        }
        if (isset($variables['ee-sidebar-width'])) {
            $this->eeSidebarWidth = str_replace('px', '', $variables['ee-sidebar-width']);
        }
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     */
    public function save()
    {
        LessHelper::updateVariables([
            'default' => $this->default,
            'primary' => $this->primary,
            'info' => $this->info,
            'success' => $this->success,
            'warning' => $this->warning,
            'danger' => $this->danger,
            'link' => $this->link,

        ], LessHelper::getVariableFile($this->theme));

        if (!empty($this->eeSidebarWidth)) {
            LessHelper::updateVariables([
                'ee-sidebar-width' => $this->eeSidebarWidth . 'px',
                'ee-sidebar-elements-color' => $this->eeSidebarElementsColor
            ], LessHelper::getVariableFile($this->theme));
        }

        if (empty($this->compiled) && strlen($this->compiled) < 1000) {
            throw new HttpException(500, 'Compiled LESS invalid!');
        }

        file_put_contents($this->theme->getBasePath() . '/css/theme.css', $this->compiled);

        $tbInfo = $this->theme->getBasePath() . '/tb_last_compile';
        if (is_file($tbInfo)) {
            unlink($tbInfo);
        }
        file_put_contents($tbInfo, Yii::$app->version);

        return true;
    }

}