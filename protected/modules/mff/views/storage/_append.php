<?php
$modelstorage=new FFStorage;
CActiveForm::validate($modelstorage);
$this->beginWidget("zii.widgets.jui.CJuiDialog",        
        array( 
            "id"=>"dialogappendstorage",
            'options' => 
            array(
                'title' => 'Додавання сховища',
                'modal' => true,
                'resizable'=> true,
                'autoOpen'=>false,
                'width'=>"65%",
                'buttons' => array(
                    array('text'=>'Зберіги','type' => 'submit','click'=>'js:function(){formappendstorage.submit();}'), 
                    array('text'=>'Відмінити','click'=> 'js:function(){$(this).dialog("close");}'),
                )
            ),
         )
);
$form=$this->beginWidget("CActiveForm", array(
    'id'=>'formappendstorage',
    'enableAjaxValidation' => true,
    'action'=>$this->createUrl($this->id.'/insert'),
    'clientOptions' => array(
        'validateOnChange'=>true,           
        ),
    )
);
?>
<table>
    <tr>
        <td style="width:30%"><?= $form->labelEx($modelstorage,"name") ?></td>
        <td><?= $form->textField($modelstorage,"name",array("style"=>"width:100%")) ?></td>
    <tr>
        <td colspan="2"><?= $form->error($modelstorage,"name") ?></td>
    </tr>
    <tr>
        <td style="width:30%"><?= $form->labelEx($modelstorage,"description") ?></td>
        <td><?= $form->textArea($modelstorage,"description",array("style"=>"width:100%")) ?></td>
    <tr>
        <td colspan="2"><?= $form->error($modelstorage,"description") ?></td>
    </tr>
    <tr>
        <td style="width:30%"><?= $form->labelEx($modelstorage,"subtype") ?></td>
        <td><?= $form->dropDownList($modelstorage,"subtype",
                array("",
                    "Список, що випадає",
                    "Лінійний список",
                    "Вбудований довідник",
                    "Перемикач",
                    "Список декількох значень",
                    )
                ) 
        ?></td>
    <tr>
        <td colspan="2"><?= $form->error($modelstorage,"multiselect") ?></td>
    </tr>
    <tr>
        <td style="width:30%"><?= $form->labelEx($modelstorage,"fields") ?></td>
        <td><?= $form->textArea($modelstorage,"fields",array("style"=>"width:100%")) ?></td>
    <tr>
        <td colspan="2"><?= $form->error($modelstorage,"fields") ?></td>
    </tr>
</table>
<?php
$this->endWidget("CActiveForm"); 
$this->endWidget("zii.widgets.jui.CJuiDialog");