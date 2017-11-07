<?php

/**
 * Class Gracious_Interconnect_Http_Request_Data_Address_Factory
 */
class Gracious_Interconnect_Http_Request_Data_Address_Factory extends Gracious_Interconnect_Http_Request_Data_FactoryAbstract {

    /**
     * @param Mage_Customer_Model_Address|Mage_Sales_Model_Order_Address $address
     * @return array
     */
    public function setupData($address) {
        $addressId = $address->getId();
        $uniqueId = $addressId !== null ? $this->generateEntityId($address->getId(), Gracious_Interconnect_Support_EntityType::ADDRESS) : null;
        $street = $address->getStreet();
        $street = is_array($street) ? implode(' ', $street) : $street;

        return [
            'addressId'     => $uniqueId,
            'street'        => $street,
            'zipcode'       => $address->getPostcode(),
            'city'          => $address->getCity(),
            'country'       => $address->getCountryId(),
            'company'       => $address->getCompany(),
            'telephone'    => $address->getTelephone()
        ];
    }
}