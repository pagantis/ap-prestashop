<?php
/**
 * This file is part of the official Afterpay module for PrestaShop.
 *
 * @author    Afterpay <integrations@afterpay.com>
 * @copyright 2020 Afterpay
 * @license   proprietary
 */

use Afterpay\SDK\HTTP\Request\CreateCheckout;
use Afterpay\SDK\MerchantAccount as AfterpayMerchantAccount;

require_once('AbstractController.php');

/**
 * Class AfterpayofficialPaymentModuleFrontController
 */
class AfterpayofficialPaymentModuleFrontController extends AbstractController
{
    /** @var string $language */
    protected $language;

    /**
     * Default API Version per region
     *
     * @var array
     */
    public $defaultApiVersionPerRegion = array(
        'AU' => 'v2',
        'CA' => 'v2',
        'ES' => 'v1',
        'GB' => 'v2',
        'NZ' => 'v2',
        'US' => 'v2',
    );

    /**
     * Default currency per region
     *
     * @var array
     */
    public $defaultLanguagePerCurrency = array(
        'AUD' => 'AU',
        'CAD' => 'CA',
        'NZD' => 'NZ',
        'USD' => 'US',
    );

    /**
     * @param $region
     * @return string
     */
    public function getApiVersionPerRegion($region = '')
    {
        if (isset($this->defaultApiVersionPerRegion[$region])) {
            return $this->defaultApiVersionPerRegion[$region];
        }
        return json_encode(array($region));
    }

    /**
     * @return mixed
     * @throws \Afterpay\SDK\Exception\InvalidArgumentException
     * @throws \Afterpay\SDK\Exception\NetworkException
     * @throws \Afterpay\SDK\Exception\ParsingException
     */
    public function postProcess()
    {
        $paymentObjData = array();
        $context = Context::getContext();
        $paymentObjData['currency'] = $context->currency->iso_code;
        $paymentObjData['region'] = Configuration::get('AFTERPAY_REGION');

        /** @var Cart $paymentObjData['cart'] */
        $paymentObjData['cart'] = $context->cart;
        $paymentObjData['shippingAddress'] = new Address($paymentObjData['cart']->id_address_delivery);
        $shippingCountryObj = new Country($paymentObjData['shippingAddress']->id_country);
        $paymentObjData['shippingCountryCode'] = $shippingCountryObj->iso_code;
        $shippingStateObj = new State($paymentObjData['shippingAddress']->id_state);
        $paymentObjData['shippingStateCode'] = '';
        if (!empty($paymentObjData['shippingAddress']->id_state)) {
            $paymentObjData['shippingStateCode'] = $shippingStateObj->iso_code;
        }

        $paymentObjData['billingAddress'] = new Address($paymentObjData['cart']->id_address_invoice);
        $paymentObjData['billingCountryCode'] = Country::getIsoById($paymentObjData['billingAddress']->id_country);
        $billingStateObj = new State($paymentObjData['billingAddress']->id_state);
        $paymentObjData['billingStateCode'] = '';
        if (!empty($paymentObjData['billingAddress']->id_state)) {
            $paymentObjData['billingStateCode'] = $billingStateObj->iso_code;
        }
        $paymentObjData['countryCode'] = $this->getCountryCode($paymentObjData);

        $paymentObjData['discountAmount'] = $paymentObjData['cart']->getOrderTotal(true, Cart::ONLY_DISCOUNTS);

        /** @var Carrier $paymentObjData['carrier'] */
        $paymentObjData['carrier'] = new Carrier($paymentObjData['cart']->id_carrier);

        /** @var Customer $paymentObjData['customer'] */
        $paymentObjData['customer'] = $context->customer;
        if (!$paymentObjData['cart']->id) {
            Tools::redirect('index.php?controller=order');
        }

        $paymentObjData['urlToken'] = Tools::strtoupper(md5(uniqid(rand(), true)));

        $paymentObjData['koUrl'] = $context->link->getPageLink(
            'order',
            null,
            null,
            array('step'=>3)
        );
        $paymentObjData['cancelUrl'] = (!empty(Configuration::get('AFTERPAY_URL_KO'))) ?
            Configuration::get('AFTERPAY_URL_KO') : $paymentObjData['koUrl'];
        $paymentObjData['publicKey'] = Configuration::get('AFTERPAY_PUBLIC_KEY');
        $paymentObjData['secretKey'] = Configuration::get('AFTERPAY_SECRET_KEY');
        $paymentObjData['environment'] = Configuration::get('AFTERPAY_ENVIRONMENT');

        $query = array(
            'id_cart' => $paymentObjData['cart']->id,
            'key' => $paymentObjData['cart']->secure_key,
        );
        $paymentObjData['okUrl'] = _PS_BASE_URL_SSL_.__PS_BASE_URI__
            .'index.php?canonical=true&fc=module&module='.Afterpayofficial::MODULE_NAME.'&controller=notify'
            .'&token='.$paymentObjData['urlToken'] . '&' . http_build_query($query)
        ;

        $url = $paymentObjData['cancelUrl'];
        try {
            \Afterpay\SDK\Model::setAutomaticValidationEnabled(true);
            $afterpayPaymentObj = new CreateCheckout();
            $afterpayMerchantAccount = new AfterpayMerchantAccount();
            $afterpayMerchantAccount
                ->setMerchantId($paymentObjData['publicKey'])
                ->setSecretKey($paymentObjData['secretKey'])
                ->setApiEnvironment($paymentObjData['environment'])
            ;
            if (isset($this->defaultLanguagePerCurrency[$paymentObjData['currency']])) {
                $afterpayMerchantAccount->setCountryCode($this->defaultLanguagePerCurrency[$paymentObjData['currency']]);
            }

            $afterpayPaymentObj
                ->setMerchant(array(
                    'redirectConfirmUrl' => $paymentObjData['okUrl'],
                    'redirectCancelUrl' => $paymentObjData['cancelUrl']
                ))
                ->setMerchantAccount($afterpayMerchantAccount)
                ->setAmount(
                    Afterpayofficial::parseAmount($paymentObjData['cart']->getOrderTotal(true, Cart::BOTH)),
                    $paymentObjData['currency']
                )
                ->setTaxAmount(
                    Afterpayofficial::parseAmount(
                        $paymentObjData['cart']->getOrderTotal(true, Cart::BOTH)
                        -
                        $paymentObjData['cart']->getOrderTotal(false, Cart::BOTH)
                    ),
                    $paymentObjData['currency']
                )
                ->setConsumer(array(
                    'phoneNumber' => $paymentObjData['billingAddress']->phone,
                    'givenNames' => $paymentObjData['customer']->firstname,
                    'surname' => $paymentObjData['customer']->lastname,
                    'email' => $paymentObjData['customer']->email
                ))
                ->setBilling(array(
                    'name' => $paymentObjData['billingAddress']->firstname . " " .
                        $paymentObjData['billingAddress']->lastname,
                    'line1' => $paymentObjData['billingAddress']->address1,
                    'line2' => $paymentObjData['billingAddress']->address2,
                    'suburb' => $paymentObjData['billingAddress']->city,
                    'area1' => $paymentObjData['billingAddress']->city,
                    'state' => $paymentObjData['billingStateCode'],
                    'region' => $paymentObjData['billingStateCode'],
                    'postcode' => $paymentObjData['billingAddress']->postcode,
                    'countryCode' => $paymentObjData['billingCountryCode'],
                    'phoneNumber' => $paymentObjData['billingAddress']->phone
                ))
                ->setShipping(array(
                    'name' => $paymentObjData['shippingAddress']->firstname . " " .
                        $paymentObjData['shippingAddress']->lastname,
                    'line1' => $paymentObjData['shippingAddress']->address1,
                    'line2' => $paymentObjData['shippingAddress']->address2,
                    'suburb' => $paymentObjData['shippingAddress']->city,
                    'area1' => $paymentObjData['shippingAddress']->city,
                    'state' => $paymentObjData['shippingStateCode'],
                    'region' => $paymentObjData['shippingStateCode'],
                    'postcode' => $paymentObjData['shippingAddress']->postcode,
                    'countryCode' => $paymentObjData['shippingCountryCode'],
                    'phoneNumber' => $paymentObjData['shippingAddress']->phone
                ))
                ->setShippingAmount(
                    Afterpayofficial::parseAmount($paymentObjData['cart']->getTotalShippingCost()),
                    $paymentObjData['currency']
                )
                ->setCourier(array(
                    'shippedAt' => '',
                    'name' => $paymentObjData['carrier']->name . '',
                    'tracking' => '',
                    'priority' => 'STANDARD'
                ));

            if (!empty($paymentObjData['discountAmount'])) {
                $afterpayPaymentObj->setDiscounts(array(
                    array(
                        'displayName' => 'Shop discount',
                        'amount' => array(
                            Afterpayofficial::parseAmount($paymentObjData['discountAmount']),
                            $paymentObjData['currency']
                        )
                    )
                ));
            }

            $items = $paymentObjData['cart']->getProducts();
            $products = array();
            foreach ($items as $item) {
                $products[] = array(
                    'name' => utf8_encode($item['name']),
                    'sku' => $item['reference'],
                    'quantity' => (int) $item['quantity'],
                    'price' => array(
                        'amount' => Afterpayofficial::parseAmount($item['price_wt']),
                        'currency' => $paymentObjData['currency']
                    )
                );
            }
            $afterpayPaymentObj->setItems($products);

            $apiVersion = $this->getApiVersionPerRegion($paymentObjData['region']);
            if ($apiVersion === 'v1') {
                $afterpayPaymentObj = $this->addPaymentV1Options($afterpayPaymentObj, $paymentObjData);
            } else {
                $afterpayPaymentObj = $this->addPaymentV2Options($afterpayPaymentObj, $paymentObjData);
            }

            $header = $this->module->name . '/' . $this->module->version
                . ' (Prestashop/'. _PS_VERSION_ . '; PHP/' . phpversion() . '; Merchant/' . $paymentObjData['publicKey']
                . ') ' . _PS_BASE_URL_SSL_.__PS_BASE_URI__;
            $afterpayPaymentObj->addHeader('User-Agent', $header);
            $afterpayPaymentObj->addHeader('Country', $paymentObjData['countryCode']);
        } catch (\Exception $exception) {
            $this->saveLog($exception->getMessage(), 3);
            return Tools::redirect($url);
        }

        if (!$afterpayPaymentObj->isValid()) {
            $this->saveLog($afterpayPaymentObj->getValidationErrors(), 2);
            return Tools::redirect($url);
        }

        $endPoint = '/' . $apiVersion . '/';
        $endPoint .= ($apiVersion === 'v2') ? "checkouts": "orders";
        $afterpayPaymentObj->setUri($endPoint);

        $afterpayPaymentObj->send();
        $errorMessage = 'empty response';
        if ($afterpayPaymentObj->getResponse()->getHttpStatusCode() >= 400
            || isset($afterpayPaymentObj->getResponse()->getParsedBody()->errorCode)
        ) {
            if (isset($afterpayPaymentObj->getResponse()->getParsedBody()->message)) {
                $errorMessage = $afterpayPaymentObj->getResponse()->getParsedBody()->message;
            }
            $errorMessage .= $this->l('. Status code: ')
                . $afterpayPaymentObj->getResponse()->getHttpStatusCode()
            ;
            $this->saveLog(
                $this->l('Error received when trying to create a order: ') .
                $errorMessage . '. URL: ' . $afterpayPaymentObj->getApiEnvironmentUrl().$afterpayPaymentObj->getUri(),
                2
            );

            return Tools::redirect($url);
        }

        try {
            $url = $afterpayPaymentObj->getResponse()->getParsedBody()->redirectCheckoutUrl;
            $orderId = $afterpayPaymentObj->getResponse()->getParsedBody()->token;
            $cartId = pSQL($paymentObjData['cart']->id);
            $orderId = pSQL($orderId);
            $urlToken = pSQL($paymentObjData['urlToken']);
            $countryCode = pSQL($paymentObjData['countryCode']);
            $sql = "INSERT INTO `" . _DB_PREFIX_ . "afterpay_order` (`id`, `order_id`, `token`, `country_code`) 
            VALUES ('$cartId','$orderId', '$urlToken', '$countryCode')";
            $result = Db::getInstance()->execute($sql);
            if (!$result) {
                throw new \Exception('Unable to save afterpay-order-id in database: '. $sql);
            }
        } catch (\Exception $exception) {
            $this->saveLog($exception->getMessage(), 3);
            $url = $paymentObjData['cancelUrl'];
        }

        return Tools::redirect($url);
    }

    /**
     * @param CreateCheckout $afterpayPaymentObj
     * @param array $paymentObjData
     * @return CreateCheckout
     */
    private function addPaymentV1Options(CreateCheckout $afterpayPaymentObj, $paymentObjData)
    {
        $afterpayPaymentObj->setTotalAmount(
            Afterpayofficial::parseAmount($paymentObjData['cart']->getOrderTotal(true, Cart::BOTH)),
            $paymentObjData['currency']
        );
        return $afterpayPaymentObj;
    }

    /**
     * @param CreateCheckout $afterpayPaymentObj
     * @param array $paymentObjData
     * @return CreateCheckout
     */
    private function addPaymentV2Options(CreateCheckout $afterpayPaymentObj, $paymentObjData)
    {
        $afterpayPaymentObj->setAmount(
            Afterpayofficial::parseAmount($paymentObjData['cart']->getOrderTotal(true, Cart::BOTH)),
            $paymentObjData['currency']
        );
        return $afterpayPaymentObj;
    }

    /**
     * @param array $paymentObjData
     * @return string|null
     */
    private function getCountryCode($paymentObjData)
    {
        $allowedCountries = json_decode(Configuration::get('AFTERPAY_ALLOWED_COUNTRIES'));
        $language = Tools::strtoupper(Configuration::get('PS_LOCALE_COUNTRY'));
        // Prevent null language detection
        if (in_array(Tools::strtoupper($language), $allowedCountries)) {
            return $language;
        }

        $shippingAddress = new Address($paymentObjData['cart']->id_address_delivery);
        if ($shippingAddress) {
            $language = Country::getIsoById($paymentObjData['shippingAddress']->id_country);
            if (in_array(Tools::strtoupper($language), $allowedCountries)) {
                return $language;
            }
        }
        $billingAddress = new Address($paymentObjData['cart']->id_address_invoice);
        if ($billingAddress) {
            $language = Country::getIsoById($paymentObjData['billingAddress']->id_country);
            if (in_array(Tools::strtoupper($language), $allowedCountries)) {
                return $language;
            }
        }
        return null;
    }
}
