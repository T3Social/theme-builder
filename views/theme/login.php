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

<?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => false, 'enableClientScript' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>

<p><?= Yii::t('ThemeBuilderModule.base', 'Upload a wallpaper for your login page.'); ?></p>
<br/>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'backgroundFile')->fileInput(); ?>
    </div>
    <div class="col-md-1"></div>
    <div class="col-md-5">
        <?php
        $fileName = $model->getBackgroundFileName();
        ?>
        <?php if ($fileName !== null): ?>
            <?= Html::img($this->theme->getBaseUrl() . '/img/' . $fileName . '?t=' . time(), ['class' => 'col-md-12']); ?>
        <?php endif; ?>
    </div>
</div>
<br/>
<hr/>
<?= Html::submitButton('<i class="fa fa-floppy-o"></i>&nbsp;&nbsp;' . Yii::t('ThemeBuilderModule.base', 'Upload and save'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>
<?= Html::a(Yii::t('ThemeBuilderModule.base', 'Delete and revert to default'), ['login', 'name' => $this->theme->name, 'delete' => true], ['class' => 'btn btn-danger pull-right btn-sm', 'data-confirm' => Yii::t('ThemeBuilderModule.base', 'Are you really sure?')]); ?>

<?php ActiveForm::end(); ?>

<?php $this->endContent(); ?>
