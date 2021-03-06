<?php

/*
 * This file is part of the Kynno/SmartBotsBundle package.
 *
 * (c) Kynno <contact@kynno.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Kynno\SmartBotsBundle\Service;

class SmartBots extends AbstractSmartBotsCommands
{
    /**
     * {@inheritdoc}
     */
    protected $APIUrl;
    /**
     * {@inheritdoc}
     */
    protected $APIKey;
    /**
     * {@inheritdoc}
     */
    protected $botName;
    /**
     * {@inheritdoc}
     */
    protected $botSecret;
    /**
     * @var array|null Contains the credentials of the bots
     */
    private $botList;

    public function __construct(string $APIUrl = null, string $APIKey = null, array $botList = null)
    {
        $this->APIUrl  = $APIUrl;
        $this->APIKey  = $APIKey;
        $this->botList = $botList;

        $this->getFirstBot();
    }

    public function getBotName(): string
    {
        return $this->botName;
    }

    public function getBotSecret(): string
    {
        return $this->botSecret;
    }

    /**
     * Get the first bot from your credentials.
     *
     * @return SmartBots
     */
    public function getFirstBot(): self
    {
        if (\is_string(key($this->botList))) {
            $this->botName   = $this->botList[key($this->botList)]['name'];
            $this->botSecret = $this->botList[key($this->botList)]['botSecret'];
        }

        return $this;
    }

    /**
     * Select a specific bot to run the next commands.
     *
     * @return SmartBots
     */
    public function getBot(string $name): self
    {
        if (isset($this->botList[$name])) {
            $this->botName   = $this->botList[$name]['name'];
            $this->botSecret = $this->botList[$name]['botSecret'];
        }

        return $this;
    }
}
