Captcha Plugin for CakePHP
==========================

The **Captcha** plugin allows you to add captcha checks on selected
forms.

1. Install the plugin in Plugins folder
2. Add the captcha input on your selected view (eg register.ctp)

eg:
	<fieldset>
	<?php echo $this->CaptchaInput->captcha_input(); ?>
	</fieldset>


3. Add the following to the controller method which handles the form:
(assuming your controller uses a User model)

	public function your_controller_method() {
		// enable check captcha for create
		$this->YourModelNameHere->Behaviors->load('CaptchaCreator.CaptchaCheck');

		if ($this->request->is('post')) {
			$this->YourModelNameHere->create(); // captcha will now be checked
			if ($this->YourModelNameHere->save($this->request->data)) {
				$this->Session->setFlash(__('The XYZ has been saved.'));
				return $this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The XYZ could not be saved. Please, try again.'));
			}
		}
	}


Other methods like admin screens can still create records without the captcha validation
getting in your way. The validation rule is only enabled when you request it.

Requirements
------------

* CakePHP 2.5+
* PHP 5.2.8+
* Lib GD

Documentation
-------------

For documentation, as well as tutorials, see the [Docs](Docs/Home.md) directory of this repository.

Support
-------

For bugs and feature requests, please use the [issues](https://github.com/CakeDC/migrations/issues) section of this repository.

Contributing
------------

This repository follows the [CakeDC Plugin Standard](http://cakedc.com/plugin-standard). If you'd like to contribute new features, enhancements or bug fixes to the plugin, please read our [Contribution Guidelines](http://cakedc.com/contribution-guidelines) for detailed instructions.

License
-------

Copyright 2007-2014 Cybernostics Pty. All rights reserved.

Licensed under the [MIT](http://www.opensource.org/licenses/mit-license.php) License. Redistributions of the source code included in this repository must retain the copyright notice found in each file.
