<?php

App::uses('Component', 'Controller');
App::uses('CakeSession', 'Model/Datasource');

if(!defined('IMAGE_ERROR_SUCCESS'))
{
	define( 'IMAGE_ERROR_SUCCESS', 1 );
}
/**
 *Generates a CAPTCHA image for form verification.
 * The image is generated by the  generate() method.
 *
 * Based on code form the following article:
 *  http://www.script-tutorials.com/how-to-create-captcha-in-php-using-gd-library/
 */
class CaptchaCreatorComponent extends Component
{

	public $name = 'CaptchaCreator';

	public $components = array('Session');

	public function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);

		$controller = $collection->getController();
		if ($controller && isset($controller->response)) {
			$this->_response = $controller->response;
		} else {
			$this->_response = new CakeResponse();
		}
	}


	private $isTest = false;

	public function setDebug()
	{
		$this->isTest = true;
	}

	/**
	 * Serve a png image which contains a word to reduce the
	 * incidents of submissions by robots.
	 *
	 * @param type $name - session variable in which to save the value
	 */
	public function generate( $name )
	{
		$textstr = $this->Session->read($name);
		$this->Session->write($name, $textstr);

		if ( $this->produceCaptchaImage( $textstr ) != IMAGE_ERROR_SUCCESS )
		{
			$this->sendHeaders();
			// output error image
			@readfile( $this->captchaErrorPath );
		}
	}

	private function sendHeaders()
	{
		// output header
		header( "Content-Type: image/png" );

		header( "Expires: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
		header( "Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . " GMT" );
		header( "Cache-Control: no-store, no-cache, must-revalidate" );
		header( "Cache-Control: post-check=0, pre-check=0",
		false );
		header( "Pragma: no-cache" );

	}

	private $captchaErrorPath = null;

	public function getErrorImage()
	{
		if(is_null($this->captchaErrorPath))
		{
			$this->captchaErrorPath = dirname(__FILE__).'/assets/background.png';

		}
		return $this->captchaErrorPath;
	}

	private $captchaBackgroundPath = null;

	public function getBackground()
	{
		if(is_null($this->captchaBackgroundPath))
		{
			$this->captchaBackgroundPath = dirname(__FILE__).'/assets/background.png';
		}
		return $this->captchaBackgroundPath;
	}

	public function setBackground($path)
	{
		$this->captchaBackgroundPath = $path;
	}

	private $captchaFontPath = null;

	public function getFontPath()
	{
		if(is_null($this->captchaFontPath))
		{
			$this->captchaFontPath = dirname(__FILE__).'/assets/GhostWriter.ttf';
		}
		return $this->captchaFontPath;
	}

	public function setFont($path)
	{
		$this->captchaFontPath = $path;
	}

	private $imageURL = null;

	public function getImageURL($name)
	{
		if(is_null($this->imageURL))
		{
			$this->imageURL = '/captcha/';
		}
		// if url is a printf style pattern do that pattern
		if(  strpos( $this->imageURL, '%s')>=0)
		{
			return sprintf($this->imageURL, $name);
		}
		return $this->imageURL.'?name='.$name;
	}

	public function setImageURL($path)
	{
		$this->imageURL = $path;
	}


	private function produceCaptchaImage( $text )
	{
		// constant values
		$sizeX = 200;
		$sizeY = 50;
		$fontFile = $this->getFontPath();
		$textLength = strlen( $text );

		$bgfile = $this->getBackground();

		$size = getimagesize( $bgfile );
		$backgroundSizeX = intval( $size[0] );
		$backgroundSizeY = intval( $size[1] );

		// generate random security values
		$backgroundOffsetX = rand( 0,
				$backgroundSizeX - $sizeX - 1 );
		$backgroundOffsetY = rand( 0,
				$backgroundSizeY - $sizeY - 1 );
		$angle = rand( -5,
				5 );
		$fontColorR = rand( 0,
				127 );
		$fontColorG = rand( 0,
				127 );
		$fontColorB = rand( 0,
				127 );

		$fontSize = rand( 19,
				28 );
		$textX = rand( 0,
				( int ) ($sizeX - 0.9 * $textLength * $fontSize) ); // these coefficients are empiric
		$textY = rand( ( int ) (1.25 * $fontSize),
				( int ) ($sizeY - 0.2 * $fontSize) ); // don't try to learn how they were taken out

		$gdInfoArray = gd_info();
		if ( !$gdInfoArray['PNG Support'] ) return IMAGE_ERROR_GD_TYPE_NOT_SUPPORTED;

		// create image with background
		$src_im = imagecreatefrompng( $bgfile );

		// this is more qualitative function, but it doesn't exist in old GD
		$dst_im = imagecreatetruecolor( $sizeX,
				$sizeY );
		// this is more qualitative function, but it doesn't exist in old GD
		$txt_im = imagecreatetruecolor( $sizeX,
				$sizeY );
		imagealphablending( $dst_im,
		true ); // setting alpha blending on
		imageSaveAlpha( $dst_im,
		true );

		$resizeResult = imagecopyresampled( $dst_im,
				$src_im,
				0,
				0,
				$backgroundOffsetX,
				$backgroundOffsetY,
				$sizeX,
				$sizeY,
				$sizeX,
				$sizeY );

		if ( !$resizeResult ) return IMAGE_ERROR_GD_RESIZE_ERROR;

		// write text on image
		if ( !function_exists( 'imagettftext' ) ) return IMAGE_ERROR_GD_TTF_NOT_SUPPORTED;
		$color = imagecolorallocatealpha( $dst_im,
				$fontColorR,
				$fontColorG,
				$fontColorB,
				30 );
		imagettftext( $txt_im,
		$fontSize,
		-$angle,
		$textX,
		$textY,
		-$color,
		$fontFile,
		$text );

		imagecopymerge($dst_im, $txt_im, 0, 0, 0, 0, $sizeX, $sizeY, 60);

		// output header
// 		header('Content-Type: image/png');
		$this->sendHeaders();

		// output image
		imagepng( $dst_im  ); //'/var/www/servers/default/captcha.png'

		// free memory
		imagedestroy( $src_im );
		imagedestroy( $dst_im );
		imagedestroy( $txt_im );

		return IMAGE_ERROR_SUCCESS;
	}


}

if ( $_SERVER['SCRIPT_FILENAME'] == __FILE__ )
{
	CaptchaCreator::generate( 'jason' );
}
?>