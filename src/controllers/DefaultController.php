<?php
namespace umbalaconmeogia\i18nui\controllers;

use umbalaconmeogia\i18nui\actions\SetLanguageAction;
use umbalaconmeogia\i18nui\helpers\HModule;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for SourceMessage model.
 */
class DefaultController extends Controller
{
    /**
     * @var string
     */
    private $sourceMessageSearchClass;

    /**
     * @var string
     */
    private $sourceMessageClass;

    public function init()
    {
        parent::init();

        $this->sourceMessageSearchClass = HModule::modelSourceMessageSearchClass();
        $this->sourceMessageClass = HModule::modelSourceMessageClass();
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'set-language' => [
                'class' => SetLanguageAction::classname(),
            ],
        ];
    }

    /**
     * Lists all SourceMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new $this->sourceMessageSearchClass;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SourceMessage model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SourceMessage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new $this->sourceMessageClass;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing SourceMessage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing SourceMessage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SourceMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SourceMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = $this->sourceMessageClass::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
