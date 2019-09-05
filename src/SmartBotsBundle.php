<?php

namespace Kynno\SmartBotsBundle;

use Kynno\SmartBotsBundle\DependencyInjection\SmartBotsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartBotsBundle extends Bundle
{
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new SmartBotsExtension();
        }

        return $this->extension;
    }
}
