<?php

use umbalaconmeogia\i18nui\helpers\HModule;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18nui', 'Translation management');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    ['class' => 'yii\grid\SerialColumn'],
    [
        'attribute' => 'category',
        'filter' => HModule::messageCategoryOptionArr(),
    ],
    'message:ntext',
];

foreach (HModule::languages() as $language){
    $columns[] = [
        'label' => $language,
        'value' => "languages.$language",
        'format' => 'ntext',
        'filter' => '<input type="text" class="form-control" name="SourceMessageSearch[languages][' . $language . ']">',
    ];
}

$columns[] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view} {update}',
    'contentOptions' => ['class' => 'text-nowrap'],
    'buttonOptions' => ['target' => "_blank"],
];

?>
<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('i18nui', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $columns,
    ]); ?>

</div>
