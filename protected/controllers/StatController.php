<?php

class StatController extends Controller {
	// Uncomment the following methods and override them if needed
	/*public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}*/

	public function actions() {
		// return external action classes, e.g.:
		return array(
            'full' => array(
                'class' => 'application.controllers.site.ModelViewAction',
                'modelClass' => 'User',
                'view' => '//stat/full'
            ),
		);
	}
}