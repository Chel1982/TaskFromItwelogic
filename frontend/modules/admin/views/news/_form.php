<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use mihaildev\elfinder\InputFile;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(\mihaildev\ckeditor\CKEditor::className(),[

        'editorOptions' => \mihaildev\elfinder\ElFinder::ckeditorOptions(['elfinder'], ['preset' => 'full', 'inline' => false]),

    ]); ?>

    <?= InputFile::widget([
        'language'   => 'ru',
        'controller' => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
        'filter'     => ['application/pdf', 'application/zip','text/plain'],    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
        'name'       => 'myinput',
        'value'      => '',
    ]); ?>

    <?= $form->field($model, 'status')->checkbox(['name' => 'news_'.$model->id, 'checked ' => (\app\models\News::find()->where(['id' => $model->id, 'status' => 1])->exists()) ? true : false]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
