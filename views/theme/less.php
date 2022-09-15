<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2018 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

use humhub\libs\Html;
use humhub\modules\themebuilder\assets\Assets;
use kartik\widgets\ActiveForm;
use kartik\widgets\ColorInput;
use yii\helpers\Url;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $editableThemes \humhub\components\Theme[] */
/* @var $theme \humhub\components\Theme */

Assets::register($this);

$buildLessUrl = $theme->getBaseUrl() . '/less/build.less';

$buildLessFieldId = str_replace('/', '-', $buildLessUrl);
$buildLessFieldId = substr(str_replace('.less', '', $buildLessFieldId), 1);

$humhubThemePath = Yii::getAlias('@webroot/static/less');

$enterprisePath = '';
if (Yii::$app->hasModule('enterprise')) {
    $enterprisePath = Yii::$app->getModule('enterprise')->getBasePath();
}

if (Yii::$app->hasModule('enterprise-theme')) {
    $enterprisePath = Yii::$app->getModule('enterprise-theme')->getBasePath();
}

$humhubThemePath = str_replace('\\', '/', $humhubThemePath);
$enterprisePath = str_replace('\\', '/', $enterprisePath);

$this->registerJsVar('lessFileLoadUrl', Url::to(['load-file', 'file' => '-file-']));
?>

<?php $this->beginContent('@theme-builder/views/theme/_layout.php'); ?>

<div id="less-insert-container"></div>

<div id="blaLoader">
    <center><strong><?= \humhub\widgets\LoaderWidget::widget(); ?></strong></center>
</div>

<?php $form = ActiveForm::begin(['id' => 'variables-form', 'enableClientValidation' => false, 'enableClientScript' => false]); ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'primary')->widget(ColorInput::class, []); ?>
        <?= $form->field($model, 'default')->widget(ColorInput::class, []); ?>
        <?= $form->field($model, 'link')->widget(ColorInput::class, []); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'info')->widget(ColorInput::class, []); ?>
        <?= $form->field($model, 'success')->widget(ColorInput::class, []); ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'warning')->widget(ColorInput::class, []); ?>
        <?= $form->field($model, 'danger')->widget(ColorInput::class, []); ?>
    </div>
</div>
<?php if (!empty($model->eeSidebarWidth)): ?>
    <hr/>
    <strong>Enterprise Edition</strong><br/>
    <br/>
    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'eeSidebarWidth')->textInput(['type' => 'number']); ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'eeSidebarElementsColor')->widget(ColorInput::class, []); ?>
        </div>
        <div class="col-md-4">
        </div>
    </div>
<?php endif; ?>
<?= $form->field($model, 'compiled')->hiddenInput()->label(false); ?>
<br/>
<hr/>
<?= Html::submitButton('<i class="fa fa-refresh"></i>&nbsp;&nbsp;' . Yii::t('ThemeBuilderModule.base', 'Save and Recompile Stylesheet'), ['class' => 'btn btn-primary', 'data-ui-loader' => '']); ?>

<?php ActiveForm::end(); ?>

<?= Html::beginTag('script') ?>
    $('.spectrum-input').on('change', function () {
        updateLess();
    });

    $('#lessvariables-eesidebarwidth').on('input', function (e) {
        updateLess();
    });


    function finishedLoading() {
        $("#blaLoader").hide();
        $("#variables-form").show();

    }

    function updateLess() {
        var json = {};
        $('.spectrum-input').each(function () {
            name = $(this).attr('name').replace("LessVariables[", "");
            name = name.substr(0, name.length - 1);
            json["@" + camelCaseToDash(name)] = $(this).val();
        });
        json["@ee-sidebar-width"] = $('#lessvariables-eesidebarwidth').val() + "px";
        json["@ENTERPRISE"] = '"<?= $enterprisePath; ?>"';
        json["@HUMHUB"] = '"<?= $humhubThemePath; ?>"';
        console.log(json);
        less.modifyVars(json);
    }

    function getCompiledLess() {
        var lessCompiled = "";
        $("head").children("style").each(function() {
            if ($(this).attr("id") && $(this).attr("id").startsWith("less:")) {
                lessCompiled = $(this).text();
                return;
            }
        });
        if (lessCompiled == "") {
            alert("Could not find compiled LESS.");
        }

        return lessCompiled;
    }

    $("#variables-form").submit(function (event) {
        $('#lessvariables-compiled').val(getCompiledLess());
    });

    less = {
        env: "development",
        logLevel: 2,
        async: false,
        fileAsync: false,
        poll: 100000,
        relativeUrls: false,
    };

    $("#variables-form").hide();

    $("#chkActivate").on('click', function () {
    });

    initLessX();
    function initLessX() {
        if (typeof less.registerStylesheets === "function") {
            $(this).attr("disabled", true);
            $('<link rel="stylesheet/less" type="text/css" href="<?= $buildLessUrl; ?>"/>').appendTo('#less-insert-container');
            less.registerStylesheets();
            updateLess();
        }
        else {
            window.setTimeout(initLessX, 200);
        }
    }


    function camelCaseToDash(myStr) {
        return myStr.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
    }
<?= Html::endTag('script') ?>


<?php $this->endContent(); ?>

<div class="panel">
    <div class="panel-heading">
        <?= Yii::t('ThemeBuilderModule.base', 'Example elements'); ?>
    </div>

    <div class="panel-body">
        <!-- Standard button -->
        <button type="button" class="btn btn-default">Default</button>

        <!-- Provides extra visual weight and identifies the primary action in a set of buttons -->
        <button type="button" class="btn btn-primary">Primary</button>

        <!-- Indicates a successful or positive action -->
        <button type="button" class="btn btn-success">Success</button>

        <!-- Contextual button for informational alert messages -->
        <button type="button" class="btn btn-info">Info</button>

        <!-- Indicates caution should be taken with this action -->
        <button type="button" class="btn btn-warning">Warning</button>

        <!-- Indicates a dangerous or potentially negative action -->
        <button type="button" class="btn btn-danger">Danger</button>

        <!-- Deemphasize a button by making it look like a link while maintaining button behavior -->
        <button type="button" class="btn btn-link">Link</button>

        <br/>
        <br/>

        <span class="label label-default">Default</span>
        <span class="label label-primary">Primary</span>
        <span class="label label-success">Success</span>
        <span class="label label-info">Info</span>
        <span class="label label-warning">Warning</span>
        <span class="label label-danger">Danger</span>


    </div>
</div>