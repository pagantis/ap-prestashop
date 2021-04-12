<?php
/**
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 */

/**
 * Class AbstractController
 */
abstract class AbstractController extends ModuleFrontController
{
    /**
     * CODE
     */
    const CODE = 'afterpayofficial';

    /**
     * @var array $headers
     */
    protected $headers;

    /**
     * Configure redirection
     *
     * @param string $url
     * @param array  $parameters
     */
    public function redirect($url = '', $parameters = array())
    {
        $parsedUrl = parse_url($url);
        $separator = '&';
        if (!isset($parsedUrl['query']) || $parsedUrl['query'] == null) {
            $separator = '?';
        }
        $redirectUrl = $url. $separator . http_build_query($parameters);
        Tools::redirect($redirectUrl);
    }

    /**
     * Save log on PS log
     *
     * @param mixed $message
     * @param int   $severity
     */
    public function saveLog($message, $severity = 1)
    {
        try {
            if (is_array($message)) {
                $message = json_encode($message);
            }
            if (Configuration::get('AFTERPAY_LOGS') == 'on' || $severity >= 3) {
                PrestaShopLogger::addLog($message, $severity, null, "Afterpay", 1);
            }
        } catch (\Exception $error) {
            PrestaShopLogger::addLog($error->getMessage(), $severity, null, "Afterpay", 2);
        }
    }
}
