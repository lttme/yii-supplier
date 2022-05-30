<?php

namespace app\controllers;

use app\models\Supplier;
use app\models\SupplierSearch;
use yii\data\Pagination;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SupplierController implements the CRUD actions for Supplier model.
 */
class SupplierController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Supplier models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SupplierSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $pages = new Pagination(['totalCount' => $dataProvider->totalCount]);

        return $this->render('index', [
            'pages' =>$pages,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Supplier model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Supplier model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Supplier();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Supplier model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Supplier model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     *Export User Selected Supplier
     */
    public function actionExport(){
        $selectIds = $this->request->get('ids');
        $chkFields = $this->request->get('chk_fields');
        $selectAll = $this->request->get('select_all',0);
        print_r($selectAll);

        if($selectAll){
            $searchModel = new SupplierSearch();
            $dataArr = $searchModel->searchAllSupplier($this->request->queryParams);
        } else {
            $dataArr = Supplier::find()->andFilterWhere(['in', 'id', explode(',', $selectIds)])->asArray()->all();
        }
        $now = date('Y-m-d-H:i:s');
        $fileName ="supplier{$now}.csv";
        $chkFields = substr($chkFields,0,-1);
        //TODO check $chkFields in mysql fields

        $title =$chkFields."\r\n";
        $arrayFields = explode(',',$chkFields);
        if(!empty($dataArr)){
            $wrstr = '';
            foreach ($dataArr as $key =>$val){
                foreach ($arrayFields as $fd){
                    $wrstr .=$val[$fd].',';
                }
                $wrstr .="\n";
            }
            $this->Csvexport($fileName,$title,$wrstr);
        }
    }

    /**
     * Down data in a csv file
     * @param string $file
     * @param $title
     * @param $data
     */
    protected function Csvexport($file='',$title,$data){
        header("Content-Disposition:attachment;filename=".$file);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');

        $wrstr = $title;
        $wrstr .=$data;
        $wrstr = iconv("utf-8","GBK//ignore",$wrstr);
        echo $wrstr;
    }

    /**
     * Finds the Supplier model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Supplier the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Supplier::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
