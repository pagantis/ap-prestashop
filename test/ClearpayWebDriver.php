<?php

namespace Test;

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverWait;

/**
 * Class AfterpayWebDriver
 *
 * @package Test
 */
class AfterpayWebDriver extends RemoteWebDriver
{
    /**
     * Override method to increase the default timeouts
     *
     * @param int $timeout_in_second
     * @param int $interval_in_millisecond
     *
     * @return WebDriverWait
     */
    public function wait($timeout_in_second = 20, $interval_in_millisecond = 1000)
    {
        return new WebDriverWait(
            $this,
            $timeout_in_second,
            $interval_in_millisecond
        );
    }
}
