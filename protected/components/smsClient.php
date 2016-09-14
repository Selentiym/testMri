<?php
	require_once(Yii::getPathOfAlias('webroot.vendor'). DIRECTORY_SEPARATOR .'autoload.php');
	class smsClient extends CApplicationComponent {
		public $authId;
		protected $client;
		/**
		 * @return object[\Zelenin\SmsRu\Api] - the client that can send sms'
		 */
		public function giveClient() {
			if ((!$this -> client)&&($this -> authId)) {
				$this -> client = new \Zelenin\SmsRu\Api(new \Zelenin\SmsRu\Auth\ApiIdAuth($this -> authId));
			}
			return $this -> client;
		}
		/**
		 * @arg integer num - the telephone number of the receiver of the sms
		 * @arg string text - the text of the sms
		 * @return object[smsrespond] - respond object
		 */
		public function sendSms($num, $text){
			$sms = new \Zelenin\SmsRu\Entity\Sms($num, $text);
			//Вместо отправки выдадим извещение об отправке.
			new CustomFlash('success','User','smsSend'.$num,'Сообщение успешно отправлено на номер '.$num.' : <div>'.$text.'</div>',true);
			//return $this -> giveClient() -> smsSend($sms);
		}
		/**
		 * @retrun float - balance of the client
		 */
		public function balance(){
			$bal = $this -> giveClient() -> myBalance();
			return $bal -> balance;
		}
	}
?>