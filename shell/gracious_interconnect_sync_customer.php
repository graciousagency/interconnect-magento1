<?php

require_once(getcwd() . 'app/code/local/Gracious/Interconnect/Console/SyncCustomerCommand.php');

$command = new Gracious_Interconnect_Console_SyncCustomerCommand();
$command->run();