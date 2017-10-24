<?php

/**
 * Class Gracious_Interconnect_Model_CustomerRegisterSuccessEventObserver
 */
class Gracious_Interconnect_Model_CustomerRegisterSuccesEventObserver extends Gracious_Interconnect_Model_ObserverAbstract
{

    public function execute(Varien_Event_Observer $observer) {
        $customer = $observer->getEvent()->getCustomer();
        Mage::log('Dude, got an event : '.get_class($customer));

        if(!$this->config->isComplete()) {
            Mage::log(__METHOD__.' :: Unable to rock and roll: module config values not configured (completely) in the backend. Aborting....');

            return;
        }

        /* @var $customer Mage_Customer_Model_Customer */ $customer = $observer->getEvent()->getData('customer');
        $customerDataFactory = new Gracious_Interconnect_Http_Request_Data_Customer_Factory();

        // Try/catch because we don't want to disturb critical processes such as the checkout
        try{
            $requestData = $customerDataFactory->setupData($customer);
        }catch (Throwable $exception) {
//            Mage::log('Failed to prepare the customer data. *** MESSAGE ***:  '.$exception->getMessage().',  *** TRACE ***:'.$exception->getTraceAsString());
            Mage::log('Failed to prepare the customer data. *** MESSAGE ***:  '.$exception->getMessage());
            Mage::log('*** TRACE ***:  '.$exception->getTraceAsString());

            return;
        }

        Mage::log(__METHOD__.'Customer data: ' . json_encode($requestData));

        // Try/catch because we don't want to disturb critical processes such as the checkout
        try {
            $client = new Gracious_Interconnect_Http_Request_Client();
            $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_CUSTOMER);
        }catch(Throwable $exception) {
//            Mage::log('Failed to send customer. *** MESSAGE ***: '.$exception->getMessage().', *** TRACE ***: '.$exception->getTraceAsString());
            Mage::log('Failed to send customer. *** MESSAGE ***: '.$exception->getMessage());

            return;
        }

        Mage::log(__METHOD__.' :: Customer sent to Interconnect ('.$customer->getId().')');
    }
}