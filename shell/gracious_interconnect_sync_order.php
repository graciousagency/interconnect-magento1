<?php

require_once(getcwd() . 'app/code/local/Gracious/Interconnect/Console/SyncOrderCommand.php');

$command = new Gracious_Interconnect_Console_SyncOrderCommand();
$command->run();