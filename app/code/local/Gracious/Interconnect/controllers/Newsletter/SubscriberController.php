<?php

/**
 * Class Gracious_Interconnect_Newsletter_SubscriberController
 */
class Gracious_Interconnect_Newsletter_SubscriberController extends Mage_Newsletter_SubscriberController {

    /**
     * {@inheritdoc}
     */
    public function newAction() {
        if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session = Mage::getSingleton('core/session');
            $customerSession = Mage::getSingleton('customer/session');
            $email = (string)$this->getRequest()->getPost('email');

            try {
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    Mage::throwException($this->__('Please enter a valid email address.'));
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                    !$customerSession->isLoggedIn()) {
                    Mage::throwException($this->__('Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.', Mage::helper('customer')->getRegisterUrl()));
                }

                $ownerId = Mage::getModel('customer/customer')
                    ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                    ->loadByEmail($email)
                    ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    Mage::throwException($this->__('This email address is already assigned to another user.'));
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    $session->addSuccess($this->__('Confirmation request has been sent.'));
                } else {
                    $session->addSuccess($this->__('Thank you for your subscription.'));
                    $subscriber = $this->getSubscriberByEmail($email);

                    if ($subscriber !== null) {
                        $this->sendSubscription($subscriber);
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            } catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
        $this->_redirectReferer();
    }

    /**
     * Subscription confirm action
     */
    public function confirmAction() {
        $id = (int)$this->getRequest()->getParam('id');
        $code = (string)$this->getRequest()->getParam('code');

        if ($id && $code) {
            $subscriber = Mage::getModel('newsletter/subscriber')->load($id);
            $session = Mage::getSingleton('core/session');

            if ($subscriber->getId() && $subscriber->getCode()) {
                if ($subscriber->confirm($code)) {
                    $session->addSuccess($this->__('Your subscription has been confirmed.'));
                    $this->sendSubscription($subscriber);
                } else {
                    $session->addError($this->__('Invalid subscription confirmation code.'));
                }
            } else {
                $session->addError($this->__('Invalid subscription ID.'));
            }
        }

        $this->_redirectUrl(Mage::getBaseUrl());
    }

    /**
     * @param string $emailAddress
     * @return Mage_Newsletter_Model_Subscriber
     */
    protected function getSubscriberByEmail($emailAddress) {
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
    protected function sendSubscription(Mage_Newsletter_Model_Subscriber $subscriber) {
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