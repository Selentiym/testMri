<h4>Подгрузить цены google</h4>
<?php CustomFlash::showFlashes(); ?>
<?php
$time = $_GET["from"];
$form=$this->beginWidget('CActiveForm', array(
    'id'=>'uploadFileClient-form',
    'action' => Yii::app() -> baseUrl.'/data/googlePrice',
    // Please note: When you enable ajax validation, make sure the corresponding
    // controller action is handling ajax validation correctly.
    // There is a call to performAjaxValidation() commented in generated controller code.
    // See class documentation of CActiveForm for details on this.
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array('enctype'=>'multipart/form-data')
));?>
    <input type="hidden" name="MAX_FILE_SIZE" value="20971520" />
    <div>
        Файл для загрузки стоимости кликов за <?php echo date('Y-m-d', $time); ?> (календарь в левой части);
        <?php echo CHtml::fileField('ClientPhoneUpload'); ?>
        <input type="hidden" name="time" value="<?php echo $time; ?>">
    </div>

    <div class="buttons">
        <?php echo CHtml::submitButton(CHtml::encode('Загрузить гугл цены')); ?>
    </div>
<?php $this -> endWidget(); ?>