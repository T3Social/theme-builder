<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $theme \humhub\components\Theme */
/* @var $viewCount int */
/* @var $lastBuilt int */
/* @var $hasLoginBackground boolean */
/* @var $themeParents array */

?>

<?php $this->beginContent('@theme-builder/views/theme/_layout.php'); ?>
    <p>
        <?= Yii::t('ThemeBuilderModule.base', 'Theme name:'); ?>
        <strong style="font-size:16px"><?= $theme->name; ?></strong>
    </p>
    <br/>
    <p>
        <?= Yii::t('ThemeBuilderModule.base', 'Base Theme(s):'); ?>
        <strong>
            <?php if (count($themeParents) == 0): ?>
                -
            <?php else: ?>
                <?= implode("&nbsp;&gt;&nbsp;", $themeParents); ?>
            <?php endif; ?>
        </strong>
    </p>
    <p>
        <?= Yii::t('ThemeBuilderModule.base', 'View files:'); ?>
        <strong><?= $viewCount; ?></strong>
    </p>
    <p>
        <?= Yii::t('ThemeBuilderModule.base', 'Login wallpaper activated:'); ?>
        <strong><?= ($hasLoginBackground ? Yii::t('ThemeBuilderModule.base', 'Yes') : Yii::t('ThemeBuilderModule.base', 'No')); ?></strong>
    </p>
    <p>
        <?= Yii::t('ThemeBuilderModule.base', 'Last stylesheet update:'); ?>
        <strong><?= Yii::$app->formatter->asDatetime($lastBuilt, 'long'); ?></strong>
    </p>
<?php $this->endContent(); ?>