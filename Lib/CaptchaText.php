<?php

App::uses('CakeSession', 'Model/Datasource');

class CaptchaText
{
	public static $isTest=false;
	
	public static function getCaptchaData(){
	    $name = uniqid();
	    $salt = Configure::read('Security.salt');
	    $text = CaptchaText::createCaptchaText($name);
	    $url = Router::url(array(
	                            'plugin' => 'CaptchaCreator',
	                            'controller' => 'Images',
	                            'action' => 'image',
	                            $name,
	                            false
	                    ));
	    return array(
	            'name' => $name,
	            'value' => self::hashIt($text),
	            'url' => $url
	    );
	}

	public static function createCaptchaText($name = 'captcha')
	{
		$textstr = self::createText();
		CakeSession::write($name, $textstr);

		return $textstr;
	}

	public static function hashIt($text)
	{
		$salt = Configure::read('Security.salt');
		return sha1($salt.$text);
	}

	public static function validateHash($typedText, $hashText)
	{
		CakeLog::write('typedText', $typedText);

		CakeLog::write('hashText from input', $hashText);

		$hash = self::hashIt($typedText);
		
		CakeLog::write('hashit on typed text', $hash);

		return $hashText===$hash;
	}

	private static function createText($maxLength=5)
	{
		if(self::$isTest)
		{
			return 'test1';
		}
		$chars = array( "a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J",
				"k", "K", "L", "m", "M", "n", "N", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T",
				"u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z", "2", "3", "4", "5", "6", "7", "8", "9" );

		$textstr = '';
		for ( $i = 0, $length = $maxLength; $i < $length; ++$i )
		{
			$textstr .= $chars[rand( 0,
					count( $chars ) - 1 )];
		}
		return $textstr;
	}




}