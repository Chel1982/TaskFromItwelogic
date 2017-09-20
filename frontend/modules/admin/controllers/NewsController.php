<?php

namespace frontend\modules\admin\controllers;

use Yii;
use common\models\News;
use common\models\search\NewsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if(\Yii::$app->request->post('news_'.$model->id) == 1 && News::find()->where(['id' => $model->id, 'status' => 0])->exists()){
                $model->status = 1;
                $model->save();
            }
            if(\Yii::$app->request->post('news_'.$model->id) == 0 && News::find()->where(['id' => $model->id, 'status' => 1])->exists()){
                $model->status = 0;
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $path = Yii::getAlias("@frontend/web/uploads/global/".$model->id);
        //var_dump(is_dir($path));die();
        if(is_dir($path)){

            $dir = opendir($path);
            $count = 0;
            while($file = readdir($dir)){
                if($file == '.' || $file == '..' || is_dir($path . $file)){
                    continue;
                }
                $count++;
            }
            $model->count_files = $count;
            $model->save();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            if(\Yii::$app->request->post('news_'.$model->id) == '1' && News::find()->where(['id' => $model->id, 'status' => 0])->exists()){
                $model->status = 1;
                $model->save();
            }
            if(\Yii::$app->request->post('news_'.$model->id) == '0' && News::find()->where(['id' => $model->id, 'status' => 1])->exists()){
                $model->status = 0;
                $model->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $path = Yii::getAlias("@frontend/web/uploads/global/".$model->id);
        $files = array_diff(scandir($path), array('.','..'));
        foreach ($files as $file) {
            (is_dir("$path/$file")) ? rmdir("$path/$file") : unlink("$path/$file");
        }
        rmdir($path);
        $this->findModel($id)->delete();


        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
