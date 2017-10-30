<?php

/**
 * Class Gracious_Interconnect_Model_Customer
 */
class Gracious_Interconnect_Model_Customer {

    /**
     * @var string
     */
    protected $email;

    /**
     * @var Mage_Customer_Model_Customer
     */
    protected $customer;

    /**
     * Gracious_Interconnect_Model_Customer constructor.
     * @param $email
     * @param Mage_Customer_Model_Customer|null $customer
     */
    public function __construct($email, Mage_Customer_Model_Customer $customer = null) {
        $this->email = $email;
        $this->customer = $customer;
        $this->loadCustomer();
    }

    protected function loadCustomer() {
        if($this->customer === null) {
            $customer = null;
            /* @var $customerRepository Mage_Customer_Model_Customer */
            $customerRepository = Mage::getModel('customer/customer');

            try {
                $customerRepository->setWebsiteId(Mage::app()->getStore()->getWebsiteId());
                /* @var $customer Mage_Customer_Model_Customer */
                $customer = $customerRepository->loadByEmail($this->email);

                if ($customer != null && $customer->getId() === null) {
                    $customer = null;
                }
            } catch (Exception $exception) {
                // pfff, Magento throws an exception if it can't find the customer instead of just returning null.
                // Log it anyway because there could be something else going on.
                Gracious_Interconnect_Reporting_Log::exception($exception);
                $customer = null;
            }

            $this->customer = $customer;
        }
    }

    /**
     * @return Gracious_Interconnect_Model_CustomerHistoricInfo
     */
    public function getHistoricInfo() {
        $orders = $this->getCustomerOrders();
        $totalOrderCount = count($orders);
        $totalOrderAmount = 0.00;
        $firstOrderDate = null;
        $lastOrderDate = null;
        $index = 0;
        $registrationDate = $this->customer != null ? $this->customer->getCreatedAt() : null;

        foreach ($orders as $order) {
            /* @var $order Mage_Sales_Model_Order */
            $totalOrderAmount += $order->getBaseGrandTotal();

            if ($index == 0) {
                $firstOrderDate = $order->getCreatedAt();
            }

            if ($index + 1 == $totalOrderCount) {
                $lastOrderDate = $order->getCreatedAt();
            }

            $index++;
        }

        return new Gracious_Interconnect_Model_CustomerHistoricInfo($this->email, $totalOrderCount, $totalOrderAmount, $firstOrderDate, $lastOrderDate, $registrationDate);
    }

    /**
     * @return
     */
    public function getCustomerOrders() {
        /* @var $orderFactory Mage_Sales_Model_Order */
        $orderFactory = Mage::getModel('sales/order');
        $orders = $orderFactory
            ->getCollection()
            ->addFieldToFilter('customer_email', $this->email)
            ->addOrder('created_at')
            ->getItems();

        return $orders;
    }
}