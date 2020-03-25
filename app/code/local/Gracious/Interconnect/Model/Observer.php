<?php

class Gracious_Interconnect_Model_Observer
{

    /**
     * Dispatch a custom event before a specific controller action
     *
     * @param $observer
     */
    public function controllerActionPreDispatch($observer)
    {
        $fullActionName = $observer->getEvent()->getControllerAction()->getFullActionName();

        if ($fullActionName == 'newsletter_subscriber_new') {
            Mage::dispatchEvent("newsletter_subscribe_before", array('request' => $observer->getControllerAction()->getRequest()));
        }
    }

    /**
     * Dispatch a custom event after a specific controller action
     *
     * @param $observer
     */
    public function controllerActionPostDispatch($observer)
    {
        $fullActionName = $observer->getEvent()->getControllerAction()->getFullActionName();

        if ($fullActionName == 'newsletter_subscriber_new') {
            Mage::dispatchEvent("newsletter_subscribe_after", array('request' => $observer->getControllerAction()->getRequest()));
        }
    }

    /**
     * Send subscriber to Interconnect on Newsletter subscribe after event
     *
     * @param $observer
     */
    public function newsletterSubscribeAfter($observer)
    {

        $email = (string)$observer->getRequest()->getPost('email');
        if(empty($email))    {
            return;
        }
        $subscriber = $this->getSubscriberByEmail($email);
        if (null === $subscriber)    {
            return;
        }
        $this->sendSubscription($subscriber);
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
            Mage::helper('interconnect/log')->exception($exception);
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
            Mage::helper('interconnect/log')->exception($exception);
        }
    }

}
