<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $editableThemes \humhub\components\Theme[] */
/* @var $views array */
?>


<?php $this->beginContent('@theme-builder/views/theme/_layout.php'); ?>
<p><?= Yii::t('ThemeBuilderModule.base', 'Themes extend default templates by overwriting existing view files.'); ?></p>
<br/>
<p><strong><?= Yii::t('ThemeBuilderModule.base', 'Overwritten views:'); ?></strong></p>
<table class="table table-hover">
    <?php foreach ($views as $file): ?>
        <tr>
            <td style="vertical-align: middle;"><?= $file; ?></td>
            <td style="width:40px"><?= Html::a('<i class="fa fa-times"></i>', ['views', 'name' => $this->context->theme->name, 'deleteFile' => $file], ['class' => 'btn btn-danger btn-sm', 'data-confirm' => Yii::t('ThemeBuilderModule.base', 'Are you really sure?')]); ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php $this->endContent(); ?>


