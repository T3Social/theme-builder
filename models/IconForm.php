<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\themebuilder\models;

use humhub\components\Theme;
use humhub\modules\file\libs\FileHelper;
use Imagine\Image\Box;
use PHP_ICO;
use Yii;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;


class IconForm extends Model
{
    /**
     * @var Theme
     */
    public $theme;

    /**
     * @var UploadedFile
     */
    public $iconFile;

    public function rules()
    {
        return [
            [['iconFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, gif'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'iconFile' => 'New icon image'
        ];
    }

    public function delete()
    {
        FileHelper::copyDirectory(
            Yii::getAlias('@webroot/themes/HumHub/ico'),
            $this->theme->getBasePath() . '/ico');
    }

    public function save()
    {

        $iconDir = $this->theme->getBasePath() . '/ico';
        $originalFile = $iconDir . '/icon-original.png';
        Image::getImagine()->open($this->iconFile->tempName)->save($originalFile);

        $originalSquareFile = $iconDir . '/icon-original-square.png';
        Image::getImagine()
            ->open($originalFile)
            ->resize(new Box(150, 150))
            ->save($originalSquareFile);

        $files = [
            'android-icon-36x36.png' => new Box(36, 36),
            'android-icon-48x48.png' => new Box(48, 48),
            'android-icon-72x72.png' => new Box(72, 72),
            'android-icon-96x96.png' => new Box(96, 96),
            'android-icon-144x144.png' => new Box(144, 144),
            'android-icon-192x192.png' => new Box(192, 192),
            'apple-icon.png' => new Box(192, 192),
            'apple-icon-57x57.png' => new Box(57, 57),
            'apple-icon-60x60.png' => new Box(60, 60),
            'apple-icon-72x72.png' => new Box(72, 72),
            'apple-icon-76x76.png' => new Box(76, 76),
            'apple-icon-114x114.png' => new Box(114, 114),
            'apple-icon-120x120.png' => new Box(120, 120),
            'apple-icon-144x144.png' => new Box(144, 144),
            'apple-icon-152x152.png' => new Box(152, 152),
            'apple-icon-180x180.png' => new Box(180, 180),
            'apple-icon-precomposed.png' => new Box(192, 192),
            'favicon-16x16.png' => new Box(16, 16),
            'favicon-32x32.png' => new Box(32, 32),
            'favicon-96x96.png' => new Box(96, 96),
            'ms-icon-70x70.png' => new Box(70, 70),
            'ms-icon-144x144.png' => new Box(144, 144),
            'ms-icon-150x150.png' => new Box(150, 150),
            'ms-icon-310x310.png' => new Box(310, 310),
        ];

        foreach ($files as $fileName => $box) {
            Image::getImagine()
                ->open($originalSquareFile)
                ->resize($box)
                ->save($iconDir . '/' . $fileName);
        }

        $ico_lib = new PHP_ICO($originalSquareFile);
        $ico_lib->save_ico($iconDir . '/favicon.ico');


        return true;
    }

}