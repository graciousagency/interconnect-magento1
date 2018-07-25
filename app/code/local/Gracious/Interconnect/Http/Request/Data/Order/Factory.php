<?php

/**
 * Class Gracious_Interconnect_Http_Request_Data_Order_Factory
 */
class Gracious_Interconnect_Http_Request_Data_Order_Factory extends Gracious_Interconnect_Http_Request_Data_FactoryAbstract {

    /**
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    public function setupData(Mage_Sales_Model_Order $order) {
        $quoteId = $order->getQuoteId();
        $prefixedQuoteId = $quoteId !== null ? $this->generateEntityId($quoteId, Gracious_Interconnect_Support_EntityType::QUOTE) : null;
        $orderItemFactory = new Gracious_Interconnect_Http_Request_Data_Order_Item_Factory();
        $paymentMethod = Mage::helper('interconnect/formatter')->unSnakeCase($order->getPayment()->getMethod());
        $paymentMethod = ucwords($paymentMethod);
        $total = $order->getGrandTotal();
        $discountAmount = $order->getDiscountAmount();
        $discountPercentage = ($discountAmount !== null && $discountAmount > 0 && $total !== null && $total > 0) ? (($discountAmount / $total) * 100) : 0;
        $couponCode = $order->getCouponCode();
        $discountType = (is_string($couponCode) && trim($couponCode)) != '' ? 'Coupon' :  $order->getDiscountDescription();

        return [
            'orderId'               => $this->generateEntityId($order->getId(), Gracious_Interconnect_Support_EntityType::ORDER),
            'quoteId'               => $prefixedQuoteId,
            'incrementId'           => $order->getIncrementId(),
            'quantity'              => (int)$order->getTotalQtyOrdered(),
            'totalAmountInCents'    => Gracious_Interconnect_Support_PriceCents::create($total)->toInt(),
            'discountAmountInCents' => Gracious_Interconnect_Support_PriceCents::create($discountAmount)->toInt(),
            'discountPercentage'    => round($discountPercentage, 2),
            'discountType'          => $discountType,
            'paymentStatus'         => $this->getPaymentStatus($order),
            'orderStatus'           => ucfirst($order->getState()),
            'shipmentStatus'        => $this->getOrderShipmentStatus($order),
            'couponCode'            => $couponCode,
            'paymentMethod'         => $paymentMethod,
            'emailAddress'          => $order->getCustomerEmail(),
            'customer'              => $this->getOrderCustomerData($order),
            'orderedAtISO8601'      => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($order->getCreatedAt()),
            'updatedAt'             => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($order->getUpdatedAt()),
            'createdAt'             => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($order->getCreatedAt()),
            'items'                 => $orderItemFactory->setupData($order)
        ];
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     */
    protected function getPaymentStatus(Mage_Sales_Model_Order $order) {
        $interconnectOrder = new Gracious_Interconnect_Model_Order($order);
        $paymentStatus = $interconnectOrder->getOrderPaymentStatus();

        return ucwords(Gracious_Interconnect_Support_Formatter::unSnakeCase($paymentStatus));
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return string
     * Returns the shipping status of an order as a string, not a constant.
     */
    protected function getOrderShipmentStatus(Mage_Sales_Model_Order $order) {
        $shipments = $order->getShipmentsCollection();

        // Determining shipment status in Magento is quite complex because an order can have multiple shipments and can also contain virtual and downloadable products.

        // !!!: This if-statement has been intentionally placed above the !$order->canShip() check because that can return false, even when there are shipments (possibly because the order can't ship as it has already been shipped?)
        if (is_array($shipments) && !empty($shipments)) {
            return 'Shipped'; // consider order partially shipped at this moment.
        }

        if (!$order->canShip()) {
            // Doesn't always mean an order doesn't have shippable items; it can also be possible there are other reasons it won't ship.

            return 'Won\'t Ship';
        }

        return 'Not Shipped';
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    protected function getOrderCustomerData(Mage_Sales_Model_Order $order) {
        $customerData = null;
        $customerFactory = new Gracious_Interconnect_Http_Request_Data_Customer_Factory();

        if ($order->getCustomerIsGuest()) {
            return $customerFactory->setUpAnonymousCustomerDataFromOrder($order);
        }

        $customer = $this->getOrderCustomer($order);

        if ($customer === null) {
            return $customerFactory->setUpAnonymousCustomerDataFromOrder($order);
        }

        return $customerFactory->setupData($customer);
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return Mage_Customer_Model_Customer|null
     */
    protected function getOrderCustomer(Mage_Sales_Model_Order $order) {
        $customerId = $order->getCustomerId();
        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')->load($customerId);

        if ($customer !== null && $customer->getId() !== null) {
            return $customer;
        }

        return null;
    }
}