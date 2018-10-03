<?php

/**
 * Class Gracious_Interconnect_Model_CustomerRegisterSuccessEventObserver
 */
class Gracious_Interconnect_Observer_CustomerRegisterSuccessEventObserver {

    /**
     * @param Varien_Event_Observer $observer
     */
    public function execute(Varien_Event_Observer $observer) {
        if (!Mage::helper('interconnect/config')->isComplete()) {
            Mage::helper('interconnect/log')->alert(__METHOD__ . '=> Unable to send data; the module\'s config values are not configured in the backend. Aborting....');

            return;
        }

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = $observer->getEvent()->getData('customer');
        $customerDataFactory = new Gracious_Interconnect_Http_Request_Data_Customer_Factory();

        // Try/catch because we don't want to disturb critical processes such as the checkout
        try {
            $requestData = $customerDataFactory->setupNewCustomerData($customer);
            $client = new Gracious_Interconnect_Http_Request_Client();
            $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_CUSTOMER);
        } catch (Throwable $exception) {
            Mage::helper('interconnect/log')->exception($exception);

            return;
        }

        Mage::helper('interconnect/log')->info(__METHOD__ . '=> Customer sent to Interconnect (' . $customer->getId() . ')');
    }
}