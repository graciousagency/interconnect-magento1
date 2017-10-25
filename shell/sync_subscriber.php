<?php
require_once(__DIR__.'/../Console/SyncSubscriberCommand.php');
$command = new Gracious_Interconnect_Console_SyncSubscriberCommand();
$command->run();