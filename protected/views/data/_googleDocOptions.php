<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.01.2017
 * Time: 16:55
 */
?>
<h2>GoogleDocApi</h2>
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'uploadFile-form',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data')
));?>

    <div class="buttons">
        <div>
        <?php echo Html::button('Загрузить гугл звонки',['submit' => Yii::app() -> createUrl('data/UploadGDCalls',['timestamp' => $_GET['from']])]); ?>
            Загрузка происходит за месяц, на который указывает календарь в левой части окна календарей.
            Сейчас это <?php echo date('m-Y',$_GET["from"]); ?>
        </div>
    </div>
<?php $this -> endWidget(); ?>