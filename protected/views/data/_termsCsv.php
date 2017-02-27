<?php
/**
 *
 */
echo CHtml::link('Файл с фразами',Yii::app() -> createUrl('data/GiveUtmData',['from' => $_GET['from'],'to'=>$_GET['to']]));