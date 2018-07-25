<?php

require_once(getcwd() . '/app/code/local/Gracious/Interconnect/Console/CommandAbstract.php');

/**
 * Class Gracious_Interconnect_Console_SyncCustomerCommand
 */
class Gracious_Interconnect_Console_SyncCustomerCommand extends Gracious_Interconnect_Console_CommandAbstract {

    /**
     * {@inheritdoc}
     */
    public function run() {
        if (!$this->config->isComplete()) {
            $this->error(__METHOD__ . ' :: Unable to rock and roll: module config values not configured (completely) in the backend. Aborting....');

            return;
        }

        $customerId = $this->getOption('id');
        $this->evalInt($customerId);

        /* @var $customer Mage_Customer_Model_Customer */
        $customer = Mage::getModel('customer/customer')->load($customerId);

        if ($customer === null || $customer->getId() != $customerId) {
            $this->line('No customer found, aborting....');

            return;
        }

        $this->line('Found customer, sending...');

        $customerDataFactory = new Gracious_Interconnect_Http_Request_Data_Customer_Factory();
        $requestData = $customerDataFactory->setupData($customer);
        $this->line('Data = '.json_encode($requestData));
        $client = new Gracious_Interconnect_Http_Request_Client();
        $client->sendData($requestData, Gracious_Interconnect_Http_Request_Client::ENDPOINT_CUSTOMER);
    }
}

$command = new Gracious_Interconnect_Console_SyncCustomerCommand();
$command->run();