<?php

namespace Test;

use Httpful\Request;
use Httpful\Mime;

/**
 * Class ControllerTest
 * @package Test
 *
 * @group prestashop17controller
 */
class AfterpayPs17ControllerTest extends AfterpayPrestashopTest
{
    /**
     * log route
     */
    const LOG_FOLDER = '/index.php?fc=module&module=afterpay&controller=log&product=AFTERPAY';

    /**
     * config route
     */
    const CONFIG_FOLDER = '/index.php?fc=module&module=afterpay&controller=config&product=AFTERPAY';

    protected $configs = array(
        "TITLE",
        "URL_OK",
        "URL_KO",
    );

    /**
     * Test testLogDownload
     */
    public function testLogDownload()
    {
        $logUrl = self::PS17URL.self::LOG_FOLDER.'&secret=c580df9e0b7b40c3';
        $response = Request::get($logUrl)->expects('json')->send();
        $this->assertGreaterThan(0, count($response->body));
        $this->quit();
    }

    /**
     * Test testSetConfig
     */
    public function testSetConfig()
    {
        $notifyUrl = self::PS17URL.self::CONFIG_FOLDER.'&secret=c580df9e0b7b40c3';
        $body = array('TITLE' => 'changed');
        $response = Request::post($notifyUrl)
            ->body($body, Mime::FORM)
            ->expectsJSON()
            ->send();
        $this->assertEquals('changed', $response->body->TITLE);
        $this->quit();
    }

    /**
     * Test testGetConfig
     */
    public function testGetConfigs()
    {
        $notifyUrl = self::PS17URL.self::CONFIG_FOLDER.'&secret=c580df9e0b7b40c3';
        $response = Request::get($notifyUrl)->expects('json')->send();

        foreach ($this->configs as $config) {
            $this->assertArrayHasKey($config, (array) $response->body);
        }
        $this->quit();
    }
}
