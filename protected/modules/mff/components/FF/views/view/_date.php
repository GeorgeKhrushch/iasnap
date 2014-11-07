<?php
if ($scenario=="view") echo CHtml::label($modelff->getAttribute(strtolower($data->name)),"") ;
else
    $this->widget('zii.widgets.jui.CJuiDatePicker',array(
        'name'=>$data->name,
        'attribute'=>$data->name,
        'model'=>$modelff,
        'language'=>Yii::app()->getLanguage(),
        'options'=>array(
            'showAnim'=>'fold',
            'dateFormat'=>'yy-mm-dd',
    //        'dateFormat'=>'dd.mm.yy',
        ),
        'htmlOptions'=>array(
            'style'=>'height:20px;'
        ),
    ));