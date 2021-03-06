<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Animal;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AnimalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Animals';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="animal-index">
    <p>
        <?= Html::a('Insert calls to db', ['mass-insert'], ['class' => 'btn btn-warning']) ?>
    </p>

    <p>
        <?= Html::a('Insert 3 rows', ['insert-three-rows'], ['class' => 'btn btn-success']) ?>
    </p>

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Animal', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'attribute' => 'category_id',
                'label' => 'Category',
                'content'=>function($data){
                    return $data->category->title;
                },
            ],
            'name',
            'breed',
            'age',
            //'photo',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
