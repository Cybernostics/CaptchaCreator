<?php


App::uses('AppModel', 'Model');


class CaptchaCheckBehavior extends ModelBehavior
{
	private $model =null;

	public function setup(Model $model, $config = array()) {

		$this->model = $model;
	}

	function matchesHash($model,$check) {

		// get name of field
		$typedValue = '';
		foreach ( $check as $key => $value ) {
			$fname = $key;
			$typedValue = $value;
			break;
		}
		return CaptchaText::validateHash($typedValue, $model->data [$model->name] ['captcha_id']);
	}

	public function beforeValidate(Model $model, $options = array()) {

		$model->validate['human_check']=array (
						'correctCaptchaText' =>
						array (
								'rule' => array('matchesHash'),
								'message' => 'You must type the characters displayed in the image',
								// 'allowEmpty' => false,
								// 'required' => true,
								// 'last' => false, // Stop validation after this rule
								'on' => 'create'  // Limit validation to 'create' or 'update' operations
						),
				);
		return true;
	}

	/**
	 * afterValidate is called just after model data was validated, you can use this callback
	 * to perform any data cleanup or preparation if needed
	 *
	 * @param Model $model Model using this behavior
	 * @return mixed False will stop this event from being passed to other behaviors
	 */
	public function afterValidate(Model $model) {
		return true;
	}



}