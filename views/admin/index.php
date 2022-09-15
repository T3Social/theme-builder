<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\file\libs\FileHelper;
use humhub\widgets\ActiveForm;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $editableThemes \humhub\components\Theme[] */
/* @var $module \humhub\modules\themebuilder\Module */

$baseThemeNames = [];
foreach ($module->getBaseThemes() as $theme) {
    $baseThemeNames[$theme->name] = $theme->name;
}

?>

<div class="panel">
    <div class="panel-heading">
        <?= Yii::t('ThemeBuilderModule.base', '<strong>Edit</strong> a theme'); ?>
    </div>
    <div class="panel-body">
        
        <div class="alert alert-danger"><strong>Important:</strong> To ensure the compatibility of the stylesheets with future HumHub versions, please save them again after each update.</div>
        

        <?php if (count($editableThemes) != 0): ?>
            <table class="table table-striped">
                <tr>
                    <th style="padding-left:6px"><?= Yii::t('ThemeBuilderModule.base', 'Name'); ?></th>
                    <th style="width:100px;"><?= Yii::t('ThemeBuilderModule.base', 'Views'); ?></th>
                    <th style="width:150px;"><?= Yii::t('ThemeBuilderModule.base', 'Last built'); ?></th>
                    <th style="width:100px;"></th>
                </tr>
                <?php foreach ($editableThemes as $theme): ?>
                    <?php
                    $viewCount = count(FileHelper::findFiles($theme->getBasePath() . '/views'));
                    $time = filemtime($theme->getBasePath() . '/css/theme.css');
                    ?>

                    <tr>
                        <td style="vertical-align:middle;padding-left:6px">
                            <?= Html::a('<strong>' . $theme->name . '</strong>', ['/theme-builder/theme', 'name' => $theme->name], ['data-pjax-prevent' => '', 'data-ui-loader' => '']); ?>
                        </td>
                        <td style="vertical-align:middle"><?= $viewCount; ?></td>
                        <td style="vertical-align:middle"><?= Yii::$app->formatter->asDatetime($time, 'short'); ?></td>
                        <td style="padding-bottom:4px;" class="text-right">
                            <?= Html::a(Yii::t('ThemeBuilderModule.base', 'Modify'), ['/theme-builder/theme', 'name' => $theme->name], ['data-pjax-prevent' => '1', 'class' => 'btn btn-success btn-sm', 'data-ui-loader' => '']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                <p><strong><?= Yii::t('ThemeBuilderModule.base', 'There are no custom themes created yet.'); ?></strong>
                </p>
                <p><?= Yii::t('ThemeBuilderModule.base', 'Use the form below to create a first theme.'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>


<div class="panel">
    <div class="panel-heading">
        <?= Yii::t('ThemeBuilderModule.base', '<strong>Create</strong> a new theme'); ?>
    </div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'name'); ?>
        <?= $form->field($model, 'baseTheme')->dropDownList($baseThemeNames); ?>
        <hr/>
        <?= Html::submitButton(Yii::t('ThemeBuilderModule.base', 'Create'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>