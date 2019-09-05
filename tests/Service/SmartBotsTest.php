<?php

namespace Kynno\SmartBotsBundle\Tests\Service;

use Kynno\SmartBotsBundle\Service\SmartBots;
use PHPUnit\Framework\TestCase;

class SmartBotsTest extends TestCase
{
    public function testGetFirstBot()
    {
        $smartbots = new SmartBots('', '', [
            'Kynno ' => [
                'name'      => 'KynnoSystems Resident',
                'botSecret' => 'pwd',
            ],
            'Leekyn' => [
                'name'      => 'Leekyn Resident',
                'botSecret' => 'pwd',
            ],
        ]);

        $smartbots->getFirstBot();

        $this->assertSame($smartbots->getBotName(), 'KynnoSystems Resident');
        $this->assertSame($smartbots->getBotSecret(), 'pwd');
    }

    public function testBot()
    {
        $smartbots = new SmartBots('', '', [
            'Kynno ' => [
                'name'      => 'KynnoSystems Resident',
                'botSecret' => 'pwd',
            ],
            'Leekyn' => [
                'name'      => 'Leekyn Resident',
                'botSecret' => 'pwd',
            ],
        ]);

        $smartbots->getBot('Leekyn');

        $this->assertSame($smartbots->getBotName(), 'Leekyn Resident');
        $this->assertSame($smartbots->getBotSecret(), 'pwd');
    }
}
