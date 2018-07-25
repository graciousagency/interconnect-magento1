<?php

/**
 * Trait Gracious_Interconnect_Generic_Behaviours_SendsOrder
 */
trait Gracious_Interconnect_Generic_Behaviours_SendsOrder
{

    /**
     * @param Mage_Sales_Model_Order                    $order
     * @param Gracious_Interconnect_Http_Request_Client $client
     */
    public function sendOrder(Mage_Sales_Model_Order $order, Gracious_Interconnect_Http_Request_Client $client)
    {
        $orderDataFactory = new Gracious_Interconnect_Http_Request_Data_Order_Factory();

        try {
            $requestData = $orderDataFactory->setupData($order);
            $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_ORDER);
        } catch (Throwable $exception) {
            Mage::helper('interconnect/log')->exception($exception);

            return;
        }
    }
}