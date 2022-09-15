<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\widgets\ActiveForm;

/* @var $this \humhub\modules\ui\view\components\View */

?>

<?php $this->beginContent('@theme-builder/views/theme/_layout.php'); ?>

<?php $form = ActiveForm::begin(['id' => 'icon-form', 'enableClientValidation' => false, 'enableClientScript' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>

<p><?= Yii::t('ThemeBuilderModule.base', 'Upload and generate icons for Web, Android, Microsoft, and iOS (iPhone and iPad) Apps for your HumHub Theme.'); ?></p>
<br/>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'iconFile')->fileInput(); ?>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-5 text-right">
        <?= Html::img($this->theme->getBaseUrl() . '/ico/favicon-96x96.png?t=' . time()); ?>
    </div>
</div>
<br/>
<hr/>
<?= Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;' . Yii::t('ThemeBuilderModule.base', 'Upload and save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>
<?= Html::a(Yii::t('ThemeBuilderModule.base', 'Delete and revert to default'), ['icon', 'name' => $this->theme->name, 'delete' => true], ['class' => 'btn btn-danger pull-right btn-sm', 'data-confirm' => Yii::t('ThemeBuilderModule.base', 'Are you really sure?')]); ?>

<?php ActiveForm::end(); ?>

<?php $this->endContent(); ?>
