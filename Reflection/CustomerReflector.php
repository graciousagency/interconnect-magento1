<?php

/**
 * Class Gracious_Interconnect_Reflection_CustomerReflector
 */
class Gracious_Interconnect_Reflection_CustomerReflector
{
    /**
     * @param $email
     * @return Gracious_Interconnect_Model_CustomerHistoricInfo
     */
    public function getCustomerHistoricInfoByCustomerEmail($email) {
        /* @var $customerRepository Mage_Customer_Model_Customer  */ $customerRepository = Mage::getModel('customer/customer');

        try {
            /* @var $customer Mage_Customer_Model_Customer */ $customer = $customerRepository->loadByEmail($email);

            if($customer != null && $customer->getId() === null) {
                $customer = null;
            }
        }catch (Exception $exception) {
            // pfff, Magento throws an exception if it can't find the customer instead of just returning null
            $customer = null;
        }

        $orders = $this->getCustomerOrdersByCustomerEmail($email);
        $totalOrderCount = count($orders);
        $totalOrderAmount = 0.00;
        $firstOrderDate = null;
        $lastOrderDate = null;
        $index = 0;
        $registrationDate = $customer != null ? $customer->getCreatedAt() : null;

        foreach($orders as $order) {
            /* @var $order Mage_Sales_Model_Order */
            $totalOrderAmount+= $order->getBaseGrandTotal();

            if($index == 0) {
                $firstOrderDate = $order->getCreatedAt();
            }

            if($index + 1 == $totalOrderCount) {
                $lastOrderDate = $order->getCreatedAt();
            }

            $index++;
        }

        return new Gracious_Interconnect_Model_CustomerHistoricInfo($email, $totalOrderCount, $totalOrderAmount, $firstOrderDate, $lastOrderDate, $registrationDate);
    }

    /**
     * @param string $email
     * @return
     */
    public function getCustomerOrdersByCustomerEmail($email) {
        /* @var $orderFactory Mage_Sales_Model_Order */ $orderFactory = Mage::getModel('sales/order');
        $orders = $orderFactory
            ->getCollection()
            ->addFieldToFilter('customer_email', $email)
            ->addOrder('created_at')
            ->getItems();

        return $orders;
    }
}