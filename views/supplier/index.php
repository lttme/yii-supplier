<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\CheckboxColumn;
use yii\bootstrap4\Modal;
use yii\widgets\ActiveForm;
use yii\bootstrap4\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SupplierSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var  $pages       \yii\data\Pagination*/

$this->title = 'Suppliers List';
?>
<?php
$GridViewJs = <<<JS
  var flag =false;
  $("#select-match").on("click", function () {
     $("#match-tab").css("display","none")
     $("#match-tab2").css("display","block")
     $("#select_all").val(1);
  })
  
  $("#clear-match").on("click", function () {
     $("#match-tab").css("display","block")
     $("#match-tab2").css("display","none")
     $("#select_all").val(0);
  })
  
  $(".export-gridview").on("click", function () {
   var select_ids_length = $("#grid").yiiGridView("getSelectedRows").length;
   if(select_ids_length >0){
      $(".doExport").removeClass("disabled")
   }else {
       $(".doExport").addClass("disabled")
   }
  })
$(".select-on-check-all").on("click", function () {
    flag =!flag;
     $("#select_all").val(0);
    if(flag){
       $("#match-tab").css("display","block")
    }
});
JS;
$this->registerJs($GridViewJs);
?>
<div class="supplier-index">

    <h3 style="text-align: center"><?= Html::encode($this->title) ?></h3>
    <div id="match-tab" style="display: none">All <?= $dataProvider->count ?>
        suppliers on this pages selected &nbsp;
        <span class="ha" id="select-match">Select all suppliers that match this search</span>
    </div>
    <div id="match-tab2" style="display:none">All suppliers in this search have been selected .&nbsp;
          <span class="ha" id="clear-match">clear selection</span>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        "options" => [
            "id" => "grid",
        ],
        'layout' =>'{items}',
        'columns' => [
            [
                'class'=>CheckboxColumn::class,
                'name'=>'id',
                'footer' => Html::submitButton('Export', ['class' => 'btn btn-primary export-gridview',    'data-toggle' => 'modal',
                    'data-target' => '#create-modal',]),
                'footerOptions' => ['colspan' => 5],
            ],
            ['attribute' => 'id','footerOptions' => ['style' =>'display:none']],

            ['attribute' => 'name', 'footerOptions' => ['style' =>'display:none']],
            ['attribute' => 'code', 'footerOptions' => ['style' =>'display:none']],
            [
                'attribute' => 't_status',
                'filter' => [
                    'ok' => 'ok',
                    'hold' => 'hold',
                ],
                'label' => 'Status',
                'footerOptions' => ['style' =>'display:none']
            ],
        ],
    ]); ?>
    <?= LinkPager::widget([
        'pagination' => $pages,
        'options' => ['class' => 'm-pagination'],
    ]); ?>


</div>
<?php
Modal::begin([
    'id' => 'create-modal',
    'title' =>'Which columns do you want?',
    'footer' => '<a href="#" class="btn" data-dismiss="modal">Close</a>
                <a href="#" class="btn btn-primary doExport disabled" data-dismiss="modal">Submit</a>',
]);
?>
<?php $form = ActiveForm::begin([
    'action' => ['export'],
    'method' => 'get',
    'id'  =>'model-form'
]);
$modalJs = <<<JS
   $(".doExport").on("click", function () {
    var select_ids = $("#grid").yiiGridView("getSelectedRows");
    var chk_value=[];
　　$('input[name="fields"]:checked').each(function(){
         chk_value +=$(this).val() +',';
　　});
    $("#ids").val(select_ids)
    $("#chk_fields").val(chk_value)
    $("#model-form").submit()
});
JS;


$this->registerJs($modalJs);
?>
<div>
    <div>
        <input type="checkbox" value="id" name="fields"  checked="checked"   disabled>ID
        <input type="checkbox" value="name" name="fields">Name
        <input type="checkbox" value="code" name="fields">Code
        <input type="checkbox" value="t_status" name="fields">Status
        <input type="hidden" name="ids" value="" id="ids" />
        <input type="hidden" name="chk_fields" value="" id="chk_fields" />
        <input type="hidden" value="0" name="select_all" id="select_all" />

    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
Modal::end()
?>
