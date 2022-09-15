<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\models;

use humhub\components\Theme;
use humhub\modules\file\libs\FileHelper;
use Yii;
use yii\base\Model;


class LoginForm extends Model
{
    /**
     * @var Theme
     */
    public $theme;

    public $backgroundFile;

    public function rules()
    {
        return [
            [['backgroundFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'backgroundFile' => 'Choose Image'
        ];
    }

    public function delete()
    {
        $this->deleteOldBackgroundImage();

        $themeFileDir = $this->theme->getBasePath() . '/views/user/layouts';
        file_put_contents($themeFileDir . '/background-image.php', '');
        return true;
    }

    public function getBackgroundFileName()
    {
        $imgPath = $this->theme->getBasePath() . '/img/';
        if (is_file($imgPath . '/login-bg.jpg')) {
            return 'login-bg.jpg';
        } elseif (is_file($imgPath . '/login-bg.png')) {
            return 'login-bg.png';
        }

        return null;
    }

    private function deleteOldBackgroundImage()
    {
        // Delete old file
        $oldFileName = $this->getBackgroundFileName();
        if ($oldFileName !== null) {
            unlink($this->theme->getBasePath() . '/img/' . $oldFileName);
        }

    }

    public function save()
    {
        $this->deleteOldBackgroundImage();

        $backgroundFile = 'login-bg.' . $this->backgroundFile->extension;
        $backgroundPath = $this->theme->getBasePath() . '/img/' . $backgroundFile;

        // Copy background to theme
        $this->backgroundFile->saveAs($backgroundPath);

        // Copy original theme view
        $themeFileDir = $this->theme->getBasePath() . '/views/user/layouts';
        if (!file_exists($themeFileDir . '/main.php')) {
            $originalViewDir = Yii::getAlias('@user/views/layouts');
            FileHelper::createDirectory($themeFileDir);
            copy($originalViewDir . '/main.php', $themeFileDir . '/main.php');
        }

        // Add login image include
        $mainContent = file_get_contents($themeFileDir . '/main.php');
        if (strpos($mainContent, 'background-image') === false) {
            $mainContent = str_replace(
                "</head>",
                '<' . '?= $this->render("background-image"); ?' . '></head>',
                $mainContent
            );
            file_put_contents($themeFileDir . '/main.php', $mainContent);
        }

        copy(
            Yii::getAlias('@theme-builder/resources/view-background-image.php'),
            $themeFileDir . '/background-image.php'
        );

        file_put_contents($themeFileDir . '/background-image.php',
            str_replace(
                '%filename%',
                $backgroundFile,
                file_get_contents($themeFileDir . '/background-image.php')
            )
        );

        return true;
    }

}