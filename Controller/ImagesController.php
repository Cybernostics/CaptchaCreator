<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 */

App::uses('AppController', 'Controller');

/**
 * Image Content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class ImagesController extends AppController {

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array();

	public $components = array (
			'CaptchaCreator.CaptchaCreator','Auth');
	public function beforeFilter() {
		parent::beforeFilter ();

		// For CakePHP 2.1 and up
		$this->Auth->allow ();
	}

	public function image($captchaSessionKey) {
		$this->autoRender = false;
		$this->CaptchaCreator->generate( $captchaSessionKey);
	}

}
