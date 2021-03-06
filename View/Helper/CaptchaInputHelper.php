<?php

App::uses('AppHelper', 'View/Helper');
App::uses('CaptchaCreatorComponent', 'CaptchaCreator.Controller/Component');
App::uses('CaptchaText', 'CaptchaCreator.Lib');

/**
 * CaptchaInputHelper - use in your view to create the captcha form element
 *
 * @package       Cake.View.Helper
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/cache.html
*/
class CaptchaInputHelper extends AppHelper {

	public $helpers = array('Form','Html');

	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
	}

	public function captcha_input()
	{
		$name = uniqid();
		$salt = Configure::read('Security.salt');
		$text = CaptchaText::createCaptchaText($name);
		$img = $this->Html->image($this->url(array('plugin'=>'CaptchaCreator',
				'controller'=>'Images',
				'action'=>'image',
				$name
		), false), array('alt' => 'Captcha')).
		$hiddenInput =  $this->Form->hidden('captcha_id', array(
				'value' =>  CaptchaText::hashIt($text)
		));
		return $this->Form->input('human_check',
				array('between'=>'<br/>'.$img.$hiddenInput,
						'value'=>'',
						'label'=>'Human Check'));
	}
}
