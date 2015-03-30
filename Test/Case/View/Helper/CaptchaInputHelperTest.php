<?php 
// app/Test/Case/View/Helper/CurrencyRendererHelperTest.php

App::uses('Controller', 'Controller');
App::uses('View', 'View');
App::uses('CaptchaInputHelper', 'View/Helper');

class CaptchaInputHelperTest extends CakeTestCase {
    /*
     * @var CaptchaInputHelper 
     */
    public $CaptchaInputHelper = null;

    // Here we instantiate our helper
    public function setUp() {
        parent::setUp();
        $Controller = new Controller();
        $View = new View($Controller);
        $this->CaptchaInputHelper = new CurrencyRendererHelper($View);
    }

    // Testing the usd() function
    public function testUsd() {
        $this->assertEquals('hello', CaptchaText::getCaptchaData());

//         // We should always have 2 decimal digits
//         $this->assertEquals('USD 1.00', $this->CurrencyRenderer->usd(1));
//         $this->assertEquals('USD 2.05', $this->CurrencyRenderer->usd(2.05));

//         // Testing the thousands separator
//         $this->assertEquals(
//           'USD 12,000.70',
//           $this->CurrencyRenderer->usd(12000.70)
//         );
    }
}