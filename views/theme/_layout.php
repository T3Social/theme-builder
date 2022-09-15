<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\themebuilder\widgets\ThemeMenu;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $editableThemes \humhub\components\Theme[] */
/* @var $content string */

?>

<div class="panel">
    <div class="panel-heading">
        <strong><?= Yii::t('ThemeBuilderModule.base', 'Edit Theme: '); ?></strong> <?= $this->context->theme->name; ?>
    </div>
    <div class="panel-body">
        <?= Html::a('<i class="fa fa-backward"></i> ' . Yii::t('ThemeBuilderModule.base', 'Back to overview'), ['/theme-builder/admin'], ['class' => 'btn btn-default btn-sm pull-right', 'data-pjax-prevent' => '', 'data-ui-loader' => '']); ?>
        <?= ThemeMenu::widget(['theme' => $this->context->theme]); ?>
        <br/>
        <?= $content; ?>
        <br/>
    </div>
</div>

<?= Html::beginTag('script') ?>
    $(document).on("pjax:beforeSend", function (e, xhr, settings) {
        location.href = settings.url;
        return false;
    });
<?= Html::endTag('script') ?>