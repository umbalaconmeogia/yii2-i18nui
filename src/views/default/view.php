<?php

use umbalaconmeogia\i18nui\helpers\HModule;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\SourceMessage */

$this->title = $model->message;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18nui', 'Translation management'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="source-message-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('i18nui', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('i18nui', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('i18nui', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php
        $arr = [
            'category',
            'message:ntext',
        ];
        foreach ($model->languages as $language => $translation){
            $arr[] = [
                'label'=> $language,
                'format' => 'ntext',
                'value' => $translation,
            ];
        }
    ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => $arr,
    ]) ?>

</div>
