<?php

/*
 * This file is part of the Kynno/SmartBotsBundle package.
 *
 * (c) Kynno <contact@kynno.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
