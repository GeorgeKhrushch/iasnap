<?php
if (!isset($storagemodel)) $storagemodel=FFStorage::model()->findByPk($idstorage);
//$this->breadcrumbs=array(
//    "Головна"=>array("/"),
//    "Админка"=>array("/admin"),
//    $this->module->label => array("/".$this->module->id),
//    $this->label => array("/".$this->module->id."/".$this->id),
//    $storagemodel->name=>$this->createUrl("indexstorage",array("id"=>$storagemodel->id)),
//);
?> 
<h3>
<p><b>Сховище:</b> <?= $storagemodel->name ?></p>
<p><i><?= $storagemodel->description ?></i></p>
</h3>
Доступні вільні форми:
<table>
    <?php
    $registryItems=$storagemodel->registryItems;
    if (count($registryItems)==0) {
        echo "До сховища не прикріпленно жодної вільної форми";
        return;
    }
    $attaching=0;
    foreach ($registryItems as $registrymodel) {
        $attaching=$attaching+$registrymodel->attaching;
        $urlparam=array("idregistry"=>$registrymodel->id,"idstorage"=>$storagemodel->id, "scenario"=>"insert");
        $thisrender=base64_encode("mff.views.formview.indexstorage");        
        ?>
    <tr>
        <td><?= ($registrymodel->attaching==0)?CHtml::link("Зареєструвати!",
                $this->createUrl("save",array_merge($urlparam, array("thisrender"=>$thisrender)))
                ):"Зовнішня таблица" ?></td>
        <td><?= $registrymodel->tablename."(".$registrymodel->description.")" ?></td>
    </tr>
    <?php
    }
    if (($attaching>0 && $attaching<count($registryItems))) {
        echo "Не відображаєме сховище";
        return;
    }
    ?>
</table>
<?php
$criteria=new CDbCriteria();
if ($attaching==0){
    $criteria->params[":storage"] = $storagemodel->id;
    $criteria->addCondition("storage = :storage");
}
$dataProvider=new CActiveDataProvider("FFModel", array(
        'criteria' => $criteria,
        'pagination' => array(
            'pageSize' => 30,
        )
    )
);

$registrylist=array();
foreach ($storagemodel->registryItems as $registryItem) {
    $registrylist= array_merge($registrylist,array($registryItem->id));
}
$vidregistry=FFModel::commonParent($registrylist); 
// Необходимые операции для внешних таблиц
$dataProvider->model->registry=$vidregistry;
$dataProvider->model->refresh();
$columns="";
$columnnames=array();
if ($vidregistry!=null){
    if ($registrymodel->attaching==0){
        $fields=FFField::model()->findAll("`formid`=$vidregistry and `type` in (1,2,3,4,5,6,7,12,15,16,17,18) and `order`>0 ");  
        foreach ($fields as $field){
            $columns.="<th>$field->description</th>";
            $columnnames=  array_merge($columnnames , array($field->name));
        }
    } else {
        $md=$dataProvider->model->getMetaData();
        foreach ($md->columns as $key => $value) {
            if ($key=="id") continue;
            $columns.="<th>$key</th>";
            $columnnames=  array_merge($columnnames , array($key));
        }
    }
    if (isset($addons) && $addons!=null && $addons!="") {
        try {
            $currentpage=  unserialize(base64_decode($addons))["currentpage"];
            $dataProvider->pagination->setCurrentPage($currentpage);           
        } catch (Exception $ex) {

        }
    }
    $dataProvider->getData();
    $this->widget("zii.widgets.CListView", array(
        'dataProvider'=>$dataProvider,
        'itemView'=>'_indexstorage',
        'enablePagination'=>TRUE,
        'viewData'=>array(
            "columnnames"=>$columnnames,
            "attaching"=>$registrymodel->attaching,
            "currentpage"=>$dataProvider->pagination->currentPage,
            "idstorage"=>$storagemodel->id,
            ),
        'tagName'=>'table',
        'template'=>'<caption>{summary}</caption><thead><th>ID</th>'.$columns.
        '<th>Дії</th></thead><tfoot><tr><td colspan="100">{pager}<td><tr></tfoot>{items}',
        )
    );
}

if ($this->action->id=="save") { 
    $urlparam=array("idregistry"=>$idregistry,"idstorage"=>$idstorage);
    if(isset($scenario)) {
        $urlparam=array_merge($urlparam,array("scenario"=>$scenario));
    }
    if(isset($idform)) {
        $urlparam=array_merge($urlparam,array("idform"=>$idform));
    }
    if(isset($addons)) $urlparam=array_merge($urlparam, array("backurl"=>  base64_encode($this->createUrl("indexstorage",array("id"=>$idstorage,"addons"=>$addons)))));
    else $urlparam=array_merge($urlparam, array("backurl"=>  base64_encode ($this->createUrl("indexstorage",array("id"=>$idstorage)))));
    $this->renderPartial("_ff",$urlparam);
}
