<?php
use Mage_Sales_Model_Order as Order;
use Gracious_Interconnect_Support_PaymentStatus as PaymentStatus;

class Gracious_Interconnect_Reflection_OrderReflector
{
    /**
     * @param Order $order
     * @return string
     */
    public function getOrderPaymentStatus(Order $order) {
        $total = $order->getBaseGrandTotal();
        $totalPaid = $order->getTotalPaid();
        $amountRemaining = $total - $totalPaid;
        $paymentStatus = null;

        switch ($amountRemaining) {
            case $amountRemaining == 0:
                $paymentStatus = PaymentStatus::PAID;
                break;

            case $amountRemaining == $total:
                $paymentStatus = PaymentStatus::NOT_PAID;
                break;

            case $amountRemaining > 0 && $amountRemaining < $total;
            default:
                $paymentStatus = PaymentStatus::PARTIALLY_PAID;
                break;
        }

        return $paymentStatus;
    }
}