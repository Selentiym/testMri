<?php
	class CHtml extends Html {
		/**
		 * @arg string[] $index_arr
		 * @arg array $arr
		 * Структура массива $arr должна быть такова:
		 * array(
		 * $index_arr[0] => array(val_01, val_02,..),
		 * $index_arr[1] => array(val_11,val_12),
		 * ...
		 * )
		 * Вернется массив 
		 * array(
		 * array($index_arr[0] => val_01, $index_arr[1] => val_11, ..)
		 * array($index_arr[0] => val_02, $index_arr[1] => val_12, ..)
		 * )
		 */
		public static function mergeArraysInsideArrayByIndex($index_arr, $arr){
			foreach($arr[current($index_arr)] as $key => $val){
				$temp = array();
				foreach($index_arr as $ind){
					$temp[$ind]=$arr[$ind][$key];
				}
				$rez[] = $temp;
			}
			return $rez;
		}
		/**
		 * На входе массив индексов и массив массивов (олинаковой длины!!)
		 * На выходе - массив, содержащий массивы типа array(i => array(inds[0] => $arrs[i][0], inds[1] => $arrs[i][1], ....))
		 */
		public static function mergeArraysByIndex($inds, $arrs) {
			$rez = array();
			foreach(current($arrs) as $key => $v){
				$temp = array();
				reset($arrs);
				foreach ($inds as $ind) {
					$cur = current($arrs);
					$temp[$ind] = $cur[$key];
					next($arrs);
				}
				$rez[$key] = $temp;
			}
			return $rez;
		}
		public static function activeDropDownListChosen2($model,$attribute,$data,$htmlOptions=array(),$selected_ids=array(),$select2Options='') {
			$selected = array();
			$selected_ids = array_filter($selected_ids);
			if (is_array($data)) {
				asort($data);
			}
			if (count($selected_ids) > 0) {
				foreach($selected_ids as $id){
					$selected[$id] = array('selected' => 'selected');
				}
			}
			$extra_htmlOptions = array('options' => $selected);
			if (($htmlOptions['multiple'] != 'multiple')&&(count($selected) > 1)){
				$htmlOptions['multiple'] = 'multiple';
			}
			parent::resolveNameID($model,$attribute,$htmlOptions);
			if ($htmlOptions['empty_line']) {
				//print_r($data);
				//array_unshift($data, '');
				$data[''] = '' ;
				unset($htmlOptions['empty_line']);
			}
			$htmlOptions = array_merge_recursive($htmlOptions, $extra_htmlOptions);
			echo parent::activeDropDownList($model,$attribute,$data,$htmlOptions);
			Yii::app()->clientScript->registerScript('chosen_'.$htmlOptions['id'], "
				$('#".$htmlOptions['id']."').select2(".$select2Options.");
			",CClientScript::POS_END);
		}
		public static function giveAttributeArray($models, $attribute){
			$rez = array();
			if (is_array($models)&&(!empty($models))) {
				foreach($models as $model) {
					$rez [] = $model -> $attribute;
				}
			}
			return array_filter($rez);
		}
		/**
		 * @arg string table_id - a string that contains html id of the table to be sorted
		 * @arg string properties - a string, containing a json object to configure the tablesorter plugin
		 */
		/* table $("#$table_id") MUST HAVE a THEAD tag in it. Cells within which will be used to sort. */
		public static function setTableSorting($table_id, $properties = ''){
			Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.tablesorter.min.js');
			Yii::app()->getClientScript()->registerScript('tableSorter_'.$table_id,"
				$('#".$table_id."').tablesorter($properties);
				//$('#".$table_id."').html($properties);
			",CClientScript::POS_READY);
		}
		public static function setDatePicker($cont_id,$callback){
			Yii::app()->getClientScript()->registerCssFile(Yii::app()->baseUrl.'/css/datePicker/css/jquery-ui-1.10.4.custom.min.css');
			Yii::app()->getClientScript()->registerScriptFile(Yii::app()->baseUrl.'/js/jquery-ui-1.10.4.custom.min.js');
			Yii::app()->getClientScript()->registerScript('DatePicker'.$cont_id,"
				$(function () {

					$('#".$cont_id."').datepicker({
						format: 'DD.MM.YYYY',
						//maxDate: moment(),
						dateLimit: { months: 3 },
						/*ranges: {
							'Сегодня': [moment()],
							'Вчера': [moment().subtract(1, 'days')],
							'Неделю назад': [moment().subtract(6, 'days')],
							'Месяц назад': [moment().subtract(29, 'days')],
						},*/
						buttonClasses: ['btn', 'btn-sm'],
						applyClass: 'btn-primary',
						cancelClass: 'btn-default',
					}, function (date, label) {
						//end.subtract(1, 'days');
						alert(date.unix());
						
					});

				});
			", CClientScript::POS_END);
		}
	}
?>