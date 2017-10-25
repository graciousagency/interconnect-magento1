<?php

/**
 * Class Gracious_Interconnect_Model_CustomerRegisterSuccessEventObserver
 */
class Gracious_Interconnect_Observer_CustomerRegisterSuccessEventObserver extends Gracious_Interconnect_Observer_ObserverAbstract {

    /**
     * @param Varien_Event_Observer $observer
     */
    public function execute(Varien_Event_Observer $observer) {
        if (!$this->config->isComplete()) {
            Gracious_Interconnect_Reporting_Log::alert(__METHOD__ . '=> Unable to send data; the module\'s config values are not configured in the backend. Aborting....');

            return;
        }

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = $observer->getEvent()->getData('customer');
        $customerDataFactory = new Gracious_Interconnect_Http_Request_Data_Customer_Factory();

        // Try/catch because we don't want to disturb critical processes such as the checkout
        try {
            $requestData = $customerDataFactory->setupData($customer);
        } catch (Throwable $exception) {
            Gracious_Interconnect_Reporting_Log::exception($exception);

            return;
        }

        if (Mage::getIsDeveloperMode()) {
            Gracious_Interconnect_Reporting_Log::debug(__METHOD__ . '=> Customer data: ' . json_encode($requestData));
        }

        // Try/catch because we don't want to disturb critical processes such as the checkout
        try {
            $client = new Gracious_Interconnect_Http_Request_Client();
            $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_CUSTOMER);
        } catch (Throwable $exception) {
            Gracious_Interconnect_Reporting_Log::exception($exception);

            return;
        }

        Gracious_Interconnect_Reporting_Log::info(__METHOD__ . '=> Customer sent to Interconnect (' . $customer->getId() . ')');
    }
}