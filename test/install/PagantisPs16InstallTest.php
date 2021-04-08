<?php

namespace Test\Install;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Test\Common\AbstractPs16Selenium;

/**
 * @requires prestashop16basic
 * @group prestashop16install
 */
class AfterpayPs16InstallTest extends AbstractPs16Selenium
{
    /**
     * @throws \Exception
     */
    public function testInstallAndConfigureAfterpayInPrestashop16()
    {
        $this->loginToBackOffice();
        $this->uploadAfterpay();
        $this->configureAfterpay();
        $this->configureLanguagePack('72', 'Español (Spanish)');
        $this->quit();
    }

    /**
     * @throws \Exception
     */
    public function configureAfterpay()
    {
        $this->findByLinkText('Modules and Services')->click();
        $this->findById('moduleQuicksearch')->clear()->sendKeys('Afterpay');
        $this->installOrConfigureModule();

        // new prompt in module installation no thusted
        try {
            sleep(3);
            $this->findByCss('#moduleNotTrusted #proceed-install-anyway')->click();
        } catch (\Exception $exception) {
            // do nothing, no prompt
        };

        $this->findByCss('#AFTERPAY_IS_ENABLED_on + label')->click();
        $this->findById('AFTERPAY_PUBLIC_KEY')->clear()->sendKeys('tk_8517351ec6ae44b29f5dca6e');
        $this->findById('afterpay_private_key')->clear()->sendKeys('c580df9e0b7b40c3');
        $this->findByCss('#AFTERPAY_ENVIRONMENT_sandbox + label')->click();
        $this->findById('module_form_submit_btn')->click();
        $confirmationSearch = WebDriverBy::className('module_confirmation');
        $condition = WebDriverExpectedCondition::textToBePresentInElement(
            $confirmationSearch,
            'All changes have been saved'
        );
        $this->webDriver->wait($condition);
        $this->assertTrue((bool) $condition);
    }

    public function installOrConfigureModule()
    {
        try {
            $afterpayAnchor = $this->findById('anchorAfterpay');
            $afterpayAnchorParent = $this->getParent($afterpayAnchor);
            $afterpayAnchorGrandParent = $this->getParent($afterpayAnchorParent);
            $this->moveToElementAndClick($afterpayAnchorGrandParent->findElement(
                WebDriverBy::partialLinkText('Install')
            ));
        } catch (\Exception $exception) {
            $afterpayAnchor = $this->findById('anchorAfterpay');
            $afterpayAnchorParent = $this->getParent($afterpayAnchor);
            $afterpayAnchorGrandParent = $this->getParent($afterpayAnchorParent);
            $this->moveToElementAndClick($afterpayAnchorGrandParent->findElement(
                WebDriverBy::partialLinkText('Configure')
            ));
        }
    }
}
