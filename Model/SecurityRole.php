<?php

namespace Dayspring\LoginBundle\Model;

use Dayspring\LoginBundle\Model\om\BaseSecurityRole;

class SecurityRole extends BaseSecurityRole implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return $this->getRoleName();
    }
}
