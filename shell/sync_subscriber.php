<?php

require_once(__DIR__.'/app/code/local/Gracious/Interconnect/Console/SyncSubscriberCommand.php');

$command = new Gracious_Interconnect_Console_SyncSubscriberCommand();
$command->run();