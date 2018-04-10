<?php

namespace Eduzz\Judas\LogKeeper;

use Eduzz\Judas\LogKeeper\LogKeeperInterface;

class JudasKeeper implements LogKeeperInterface
{
    public function store($json) {
        var_dump($json);
        // Send to Elastic
    }

    public function setElasticConfig($config) {
        var_dump($config);
        // Send to elastic
    }
}
