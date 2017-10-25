<?php
/**
 * Class Gracious_Interconnect_Reflection_OrderReflector
 */
class Gracious_Interconnect_Reflection_OrderReflector {
    /**
     * @param Order $order
     * @return string
     */
    public function getOrderPaymentStatus(Mage_Sales_Model_Order $order) {
        $total = $order->getBaseGrandTotal();
        $totalPaid = $order->getTotalPaid();
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