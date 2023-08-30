<?php

namespace Pedros80\LANDEphp\Contracts;

use stdClass;

interface LiftsAndEscalators
{
    public function getAssetsByStationCode(string $station, string $token): stdClass;
    public function getAssetInfoById(int $id, string $token): stdClass;
    public function getSensors(string $token, int $num=50, int $offset=0): stdClass;
    public function getSensorInfoById(int $id, string $token): stdClass;
}