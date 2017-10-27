<?php
require_once(__DIR__.'/CommandAbstract.php');

/**
 * Class Gracious_Interconnect_Console_SyncSubscriberCommand
 */
class Gracious_Interconnect_Console_SyncSubscriberCommand extends Gracious_Interconnect_Console_CommandAbstract
{
    /**
     * {@inheritdoc}
     */
    public function run(){
        if(!$this->config->isComplete()) {
            $this->error(__METHOD__.' :: Unable to rock and roll: module config values not configured (completely) in the backend. Aborting....');

            return;
        }

        $subscriberId = $this->getOption('id');
        $this->evalInt($subscriberId);
        $this->line('$subscriberId : '.$subscriberId);
        /* @var $subscriber Mage_Newsletter_Model_Subscriber */
        $subscriber = Mage::getModel('newsletter/subscriber')->load($subscriberId);

        if($subscriber === null || $subscriber->getId() !== $subscriberId) {
            $this->info('Subscriber not found, all done here ....');

            return;
        }

        $this->info('Found subscriber \''.$subscriber->getEmail().'\', sending...');
        $subscriberFactory = new Gracious_Interconnect_Http_Request_Data_Subscriber_Factory();
        $requestData = $subscriberFactory->setupData($subscriber);
        $this->line('Data = '.json_encode($requestData));
        $client = new Gracious_Interconnect_Http_Request_Client();
        $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_NEWSLETTER_SUBSCRIBER);
    }
}