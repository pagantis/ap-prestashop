<?php

namespace Test\Advanced;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Test\Common\AbstractPs16Selenium;

/**
 * @requires prestashop16install
 * @requires prestashop16register
 *
 * @group prestashop16advanced
 */
class AfterpayPs16InstallTest extends AbstractPs16Selenium
{
    /**
     * @REQ5 BackOffice should have 2 inputs for setting the public and private API key
     * @REQ6 BackOffice inputs for API keys should be mandatory upon save of the form.
     *
     * @throws  \Exception
     */
    public function testPublicAndPrivateKeysInputs()
    {
        $this->loginToBackOffice();
        $this->getAfterpayBackOffice();

        //2 elements exist:
        $validatorSearch = WebDriverBy::id('AFTERPAY_PUBLIC_KEY');
        $condition = WebDriverExpectedCondition::visibilityOfElementLocated($validatorSearch);
        $this->waitUntil($condition);
        $this->assertTrue((bool) $condition);
        $validatorSearch = WebDriverBy::id('afterpay_private_key');
        $condition = WebDriverExpectedCondition::visibilityOfElementLocated($validatorSearch);
        $this->waitUntil($condition);
        $this->assertTrue((bool) $condition);

        /* no longer checked in multiproduct
        //save with empty public Key
        $this->findById('AFTERPAY_PUBLIC_KEY')->clear();
        $this->findById('module_form_submit_btn')->click();
        $validatorSearch = WebDriverBy::className('module_error');
        $condition = WebDriverExpectedCondition::visibilityOfElementLocated($validatorSearch);
        $this->waitUntil($condition);
        $this->assertTrue((bool) $condition);
        $this->assertContains('Please add a Afterpay API Public Key', $this->webDriver->getPageSource());
        $this->findById('AFTERPAY_PUBLIC_KEY')->clear()->sendKeys($this->configuration['publicKey']);

        //save with empty private Key
        $this->findById('afterpay_private_key')->clear();
        $this->findById('module_form_submit_btn')->click();
        $validatorSearch = WebDriverBy::className('module_error');
        $condition = WebDriverExpectedCondition::visibilityOfElementLocated($validatorSearch);
        $this->waitUntil($condition);
        $this->assertTrue((bool) $condition);
        $this->assertContains('Please add a Afterpay API Private Key', $this->webDriver->getPageSource());
        $this->findById('afterpay_private_key')->clear()->sendKeys($this->configuration['secretKey']);
        */

        $this->quit();
    }

    /**
     * @REQ17 BackOffice Panel should have visible Logo and links
     *
     * @throws \Exception
     */
    public function testBackOfficeHasLogoAndLinkToAfterpay()
    {
        //Change Title
        $this->loginToBackOffice();
        $this->getAfterpayBackOffice();
        $html = $this->webDriver->getPageSource();
        $this->assertContains('pg.png', $html);
        $this->assertContains('Login Afterpay', $html);
        $this->assertContains('https://bo.afterpay.com', $html);
        $this->quit();
    }
}
