<?php

class Gracious_Interconnect_Model_Observer
{

    public function controllerActionPreDispatch($observer)
    {
        $fullActionName = $observer->getEvent()->getControllerAction()->getFullActionName();

        if ($fullActionName == 'newsletter_subscriber_new') {
            Mage::dispatchEvent("newsletter_subscribe_before", array('request' => $observer->getControllerAction()->getRequest()));
        }
    }

    public function controllerActionPostDispatch($observer)
    {
        $fullActionName = $observer->getEvent()->getControllerAction()->getFullActionName();

        if ($fullActionName == 'newsletter_subscriber_new') {
            Mage::dispatchEvent("newsletter_subscribe_after", array('request' => $observer->getControllerAction()->getRequest()));
        }
    }

    public function newsletterSubscribeAfter($observer)
    {

        $email = (string)$observer->getRequest()->getPost('email');
        $subscriber = $this->getSubscriberByEmail($email);

        if ($subscriber !== null) {
            $this->sendSubscription($subscriber);
        }

    }

    /**
     * @param string $emailAddress
     *
     * @return Mage_Newsletter_Model_Subscriber
     */
    private function getSubscriberByEmail($emailAddress)
    {
        try {
            /* @var $subscriber Mage_Newsletter_Model_Subscriber */
            $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($emailAddress);

            if ($subscriber !== null && $subscriber->getId() === null) {
                return null;
            }

            return $subscriber;
        } catch (Exception $exception) {
            Gracious_Interconnect_Reporting_Log::exception($exception);
        }

        return null;
    }

    /**
     * @param Mage_Newsletter_Model_Subscriber $subscriber
     */
    private function sendSubscription(Mage_Newsletter_Model_Subscriber $subscriber)
    {
        try {
            $subscriberFactory = new Gracious_Interconnect_Http_Request_Data_Subscriber_Factory();
            // Set date manually; model/db table does not have these properties
            $date = date('Y-m-d H:i:s');
            $requestData = $subscriberFactory->setupData($subscriber, [
                'updatedAt' => $date,
                'createdAt' => $date
            ]);

            $client = new Gracious_Interconnect_Http_Request_Client();
            $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_NEWSLETTER_SUBSCRIBER);
        } catch (Exception $exception) {
            Gracious_Interconnect_Reporting_Log::exception($exception);
        }
    }

}