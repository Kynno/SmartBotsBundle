<?php

namespace Kynno\SmartBotsBundle\Tests\Service;

use Kynno\SmartBotsBundle\Service\SmartBotsClient;
use PHPUnit\Framework\TestCase;

class SmartBotsClientTest extends TestCase
{
    public function testGetFirstBot()
    {
        $smartbots = new SmartBotsClient('', '', [
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
        $smartbots = new SmartBotsClient('', '', [
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
