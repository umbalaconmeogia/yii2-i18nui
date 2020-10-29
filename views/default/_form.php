<?php

use umbalaconmeogia\i18nui\helpers\HModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SourceMessage */
/* @var $form yii\widgets\ActiveForm */

$module = Yii::$app->controller->module;
?>

<div class="source-message-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category')->radioList(HModule::messageCategoryOptionArr()) ?>

    <?= $form->field($model, 'message')->textarea() ?>

    <?php foreach (HModule::languages() as $language): ?>
        <div class="row">
            <div class="col-xs-12">
                <?= $form->field($model, 'languages['.$language.']')->label($language)->textarea() ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('i18nui', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
