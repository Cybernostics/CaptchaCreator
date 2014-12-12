<?php


Router::connect('/captcha/*', array('plugin' => 'CaptchaCreator', 'controller' => 'Images', 'action' => 'image'));

