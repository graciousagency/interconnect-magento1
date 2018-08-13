<?php

/**
 * Class Gracious_Interconnect_Http_Request_Data_Customer_Factory
 */
class Gracious_Interconnect_Http_Request_Data_Customer_Factory extends Gracious_Interconnect_Http_Request_Data_FactoryAbstract {

    /**
     * @param Mage_Customer_Model_Customer $customer
     * @return array
     */
    public function setupData(Mage_Customer_Model_Customer $customer) {
        $prefix = $customer->getPrefix();
        $customerId = $customer->getId();
        $customerEmail = $customer->getEmail();
        $interconnectCustomer = new Gracious_Interconnect_Model_Customer($customerEmail, $customer);
        $historicInfo = $interconnectCustomer->getHistoricInfo();

        return [
            'customerId' => $this->generateEntityId($customerId, Gracious_Interconnect_Support_EntityType::CUSTOMER),
            'firstName' => $customer->getFirstname(),
            'lastName' => Mage::helper('interconnect/formatter')->prefixLastName($customer->getLastname(), $prefix),
            'emailAddress' => $customerEmail,
            'gender' => $customer->getGender(),
            'birthDate' => $customer->getDob(),
            'optIn' => $this->isCustomerSubscribedToNewsletter($customerEmail),
            'billingAddress' => $this->setupAddressData($customer->getDefaultBillingAddress()),
            'shippingAddress' => $this->setupAddressData($customer->getDefaultShippingAddress()),
            'phoneNumber' => $customer->getDefaultBillingAddress()->getTelephone() ?? null,
            'isAnonymous' => false,
            'totalOrderCount' => (int)$historicInfo->getTotalOrderCount(),
            'totalOrderAmount' => Gracious_Interconnect_Support_PriceCents::create($historicInfo->getTotalOrderAmount())->toInt(),
            'firstOrderDate' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($historicInfo->getFirstOrderDate()),
            'lastOrderDate' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($historicInfo->getLastOrderDate()),
            'registrationDate' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($historicInfo->getRegistrationDate()),
            'createdAt' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($customer->getCreatedAt()),
            'updatedAt' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($customer->getUpdatedAt())
        ];
    }

    public function setupNewCustomerData(Mage_Customer_Model_Customer $customer) {
        $customerEmail = $customer->getEmail();

        return [
            'customerId' => $this->generateEntityId($customer->getId(), Gracious_Interconnect_Support_EntityType::CUSTOMER),
            'firstName' => $customer->getFirstname(),
            'lastName' => Mage::helper('interconnect/formatter')->prefixLastName($customer->getLastname(), $customer->getPrefix()),
            'emailAddress' => $customerEmail,
            'gender' => $customer->getGender(),
            'optIn' => $this->isCustomerSubscribedToNewsletter($customerEmail),
            'phoneNumber' => '',
            'isAnonymous' => false,
            'totalOrderCount' => 0,
            'totalOrderAmount' => 0,
            'createdAt' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($customer->getCreatedAt()),
            'updatedAt' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($customer->getUpdatedAt())
        ];
    }

    /**
     * @param Mage_Sales_Model_Order $order
     * @return array
     */
    public function setUpAnonymousCustomerDataFromOrder(Mage_Sales_Model_Order $order) {
        $billingAddress = $order->getBillingAddress();
        $shippingAddress = $order->getShippingAddress();
        $interconnectCustomer = new Gracious_Interconnect_Model_Customer($billingAddress->getEmail());
        $historicInfo = $interconnectCustomer->getHistoricInfo();

        return [
            'customerId' => null,
            'firstName' => $billingAddress->getFirstname(),
            'lastName' => Mage::helper('interconnect/formatter')->prefixLastName($billingAddress->getLastname(), $billingAddress->getPrefix()),
            'emailAddress' => $billingAddress->getEmail(),
            'gender' => null,
            'birthDate' => null,
            'optIn' => null,
            'billingAddress' => $this->setupAddressData($billingAddress),
            'shippingAddress' => $this->setupAddressData($shippingAddress),
            'phoneNumber' => $billingAddress->getTelephone() ?? null,
            'isAnonymous' => true,
            'totalOrderCount' => (int)$historicInfo->getTotalOrderCount(),
            'totalOrderAmountInCents' => Gracious_Interconnect_Support_PriceCents::create($historicInfo->getTotalOrderAmount())->toInt(),
            'firstOrderDate' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($historicInfo->getFirstOrderDate()),
            'lastOrderDate' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($historicInfo->getLastOrderDate()),
            'registrationDate' => null,
            'createdAt' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($order->getCreatedAt()),
            'updatedAt' => Mage::helper('interconnect/formatter')->formatDateStringToIso8601($order->getUpdatedAt())
        ];
    }

    /**
     * @param Mage_Customer_Model_Address|Mage_Sales_Model_Order_Address $address
     * @return array|null
     */
    protected function setupAddressData($address) {
        if (!($address instanceof Mage_Customer_Model_Address) && !($address instanceof Mage_Sales_Model_Order_Address)) {
            return null;
        }

        $addressFactory = new Gracious_Interconnect_Http_Request_Data_Address_Factory();

        return $addressFactory->setupData($address);
    }

    /**
     * @param string $email
     * @return bool
     */
    protected function isCustomerSubscribedToNewsletter($email) {
        /* @var $utilitySubscriber Mage_Newsletter_Model_Subscriber */
        $utilitySubscriber = Mage::getModel('newsletter/subscriber');
        $checkSubscriber = $utilitySubscriber->loadByEmail($email);

        return $checkSubscriber !== null && $checkSubscriber->getId() !== null && $checkSubscriber->isSubscribed();
    }
}