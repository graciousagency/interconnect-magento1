<?php

/**
 * Class Gracious_Interconnect_Console_SyncOrderCommand
 */
class Gracious_Interconnect_Console_SyncOrderCommand extends Gracious_Interconnect_Console_CommandAbstract {

    /**
     * {@inheritdoc}
     */
    public function run() {
        if (!$this->config->isComplete()) {
            $this->line(__METHOD__ . ' :: Unable to rock and roll: module config values not configured (completely) in the backend. Aborting....');

            return;
        }

        $orderId = $this->getOption('id');
        $this->evalInt($orderId);
        /* @var $order Mage_Sales_Model_Order */
        $order = Mage::getModel('sales/order')->load($orderId);

        if($order === null || $orderId != $order->getId()) {
            $this->line('No order found, aborting....');

            return;
        }

        $this->line('Found order, sending...');

        $orderDataFactory = new Gracious_Interconnect_Http_Request_Data_Order_Factory();
        $requestData = $orderDataFactory->setupData($order);
        $this->line('Data = '.json_encode($requestData));
        $client = new Gracious_Interconnect_Http_Request_Client();
        $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_ORDER);
        $this->line('Order sent to Interconnect ('.$order->getId().')');
    }
}