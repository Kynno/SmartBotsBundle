<?php

/*
 * This file is part of the Kynno/SmartBotsBundle package.
 *
 * (c) Kynno <contact@kynno.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kynno\SmartBotsBundle\Exception;

class SmartBotsException extends \Exception
{
    /**
     * {@inheritdoc}
     */
    public function __construct($message = 'An error occurred.')
    {
        parent::__construct($message, 500);
    }
}
