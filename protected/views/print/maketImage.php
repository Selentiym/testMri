<div id="wrapper">
<?php

	$min = false;
	$max = false;
	$range = end($user -> ranges);
	$min = $range -> jMin;
	$max = $range -> jMax;
	//В случае, если через интервалы направление не найдено, используем старый вариант.
	if (!($min && $max)) {
		if (($user -> jMin)&&($user -> jMax)&&($user -> jMax >= $user -> jMin)) {
			$min = $user -> jMin;
			$max = $user -> jMax;
		}
		if (($user -> jMin_add)&&($user -> jMax_add)&&($user -> jMax_add >= $user -> jMin_add)) {
			$min = $user -> jMin_add;
			$max = $user -> jMax_add;
		}
	}
	if ($min && $max) {
		$tel = '407 29 81';
		$num = mt_rand($min, $max);
		$this -> renderPartial('//print/oneDirectionImage', array('fio' => $user -> fio, 'number' => $num, 'tel' => $tel));
		echo "<div class='separator'></div>";
		$this -> renderPartial('//print/oneDirectionImage', array('fio' => $user -> fio, 'number' => $num, 'tel' => $tel));
		echo "<div class='separator'></div>";
		$this -> renderPartial('//print/oneDirectionImage', array('fio' => $user -> fio, 'number' => $num, 'tel' => $tel));
	} else {
		echo "Проблемы с интервалом направлений доктора.";
	}
	
?>
</div>