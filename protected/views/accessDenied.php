<?php
	echo "У вас не достаточно прав для просмотра выбранной страницы.";
	echo ' Вы можете '.CHtml::link('вернуться в свой личный кабинет', Yii::app() -> baseUrl.'/cabinet'). ' или '.CHtml::link('выйти и зайти в другую учетную запись.', Yii::app() -> baseUrl.'/logout') ;
?>