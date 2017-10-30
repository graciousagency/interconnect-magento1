<?php

/**
 * Class Gracious_Interconnect_Model_Order
 */
class Gracious_Interconnect_Model_Order {

    /**
     * @var Mage_Sales_Model_Order
     */
    protected $order;

    /**
     * Gracious_Interconnect_Model_Order constructor.
     * @param Mage_Sales_Model_Order $order
     */
    public function __construct(Mage_Sales_Model_Order $order) {
        $this->order = $order;
    }

    public function getOrderPaymentStatus() {
        $total = $this->order->getBaseGrandTotal();
        $totalPaid = $this->order->getTotalPaid();
        $amountRemaining = $total - $totalPaid;
        $paymentStatus = null;

        switch ($amountRemaining) {
            case $amountRemaining == 0:
                $paymentStatus = Gracious_Interconnect_Support_PaymentStatus::PAID;
                break;

            case $amountRemaining == $total:
                $paymentStatus = Gracious_Interconnect_Support_PaymentStatus::NOT_PAID;
                break;

            case $amountRemaining > 0 && $amountRemaining < $total;
            default:
                $paymentStatus = Gracious_Interconnect_Support_PaymentStatus::PARTIALLY_PAID;
                break;
        }

        return $paymentStatus;
    }
}