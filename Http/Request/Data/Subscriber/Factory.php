<?php

/**
 * Class Gracious_Interconnect_Http_Request_Data_Subscriber_Factory
 */
class Gracious_Interconnect_Http_Request_Data_Subscriber_Factory extends Gracious_Interconnect_Http_Request_Data_FactoryAbstract
{
    /**
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     * @param array $extraData
     * @return array
     */
    public function setupData(Mage_Newsletter_Model_Subscriber $subscriber, array $extraData = []) {
        $subscriberId = $subscriber->getId();
        $prefixedSubscriberId = $this->generateEntityId($subscriberId, Gracious_Interconnect_Support_EntityType::NEWSLETTER_SUBSCRIPTION);
        $createdAt = isset($extraData['createdAt']) ? $extraData['createdAt'] : null;
        $updatedAt = isset($extraData['updatedAt']) ? $extraData['updatedAt'] : null;

        return [
            'subscriptionId'        => $prefixedSubscriberId,
            'emailAddress'          => $subscriber->getEmail(),
            'optIn'                 => $subscriber->isSubscribed(),
            'createdAt'             => Gracious_Interconnect_Support_Formatter::formatDateStringToIso8601($createdAt),
            'updatedAt'             => Gracious_Interconnect_Support_Formatter::formatDateStringToIso8601($updatedAt)
        ];
    }
}