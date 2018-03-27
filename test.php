<?php

//namespace Judas;
require 'vendor/autoload.php';

//include "Judas/Judas.php";

use Judas\Judas;

//CONST DEBUG_JUDAS = 1;

$judas = new Judas();
$judas->log('sun.invoice.created', ['agent' => 'sys', 'invoice.id' => 102002, 'invoice.amount' => 2320.00, 'user.id' => 815959,'user.name' => 'JEZAO', 'cassetinho' => 'XPTO']);
