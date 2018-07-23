<?php
/**
 * Created by PhpStorm.
 * User: justinvanschaick
 * Date: 25/10/2017
 * Time: 09:33
 */

class Gracious_Interconnect_Observer_CheckoutOnePageControllerSuccessActionEventObserver extends Gracious_Interconnect_Observer_ObserverAbstract {
    use Gracious_Interconnect_Generic_Behaviours_SendsOrder;

    /**
     * @param Varien_Event_Observer $observer
     */
    public function execute(Varien_Event_Observer $observer) {
        if (!$this->config->isComplete()) {
            Gracious_Interconnect_Reporting_Log::alert(__METHOD__ . '=> Unable to send data; the module\'s config values are not configured in the backend. Aborting....');

            return;
        }

        $orderIds = $observer->getData('order_ids');

        if (!is_array($orderIds) || empty($orderIds)) {
            Gracious_Interconnect_Reporting_Log::alert(__METHOD__ . '=> Expected to get an order id but none was provided! Aborting....');

            return;
        }

        $orderId = $orderIds[0];

        if(!Gracious_Interconnect_Support_Validation_RegEx::test(Gracious_Interconnect_Support_Validation_RegEx::INT, (string)$orderId)) { // don't trust Magento entirely here... There's something in the array but is it an integer?
            Gracious_Interconnect_Reporting_Log::alert(__METHOD__ . '=> Invalid order id (' . json_encode($orderId) . ') Aborting....');

            return;
        }

        $orderRepository = Mage::getModel('sales/order');
        /* @var $order Mage_Sales_Model_Order */
        $order = $orderRepository->load($orderId);

        if ($order === null || $order->getId() != $orderId) {
            Gracious_Interconnect_Reporting_Log::alert(__METHOD__ . '=> No order found for id(' . $orderId . ') Aborting....');

            return;
        }

        $this->sendOrder($order, new Gracious_Interconnect_Http_Request_Client());
    }
}