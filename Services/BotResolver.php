<?php

namespace Zanshee\TelegramBDKBundle\Services;

use Symfony\Component\Config\Definition\Exception\Exception;
use Zanshee\TelegramBDKBundle\TelegramBot;

/**
 * Class BotResolver
 *
 * @package Zanshee\TelegramBDKBundle
 */
class BotResolver
{
    /**
     * List of Bot's names and API key's
     *
     * @var array
     */
    private $api_list = array();

    /**
     * Resolver constructor.
     *
     * @param null $api_list
     */
    public function __construct($api_list)
    {
        $this->api_list = $api_list;
    }

    /**
     * @param $bot
     *
     * @return TelegramBot
     */
    public function take($bot)
    {
        if (in_array($bot, array_keys($this->api_list))) {
            return new TelegramBot($this->api_list[$bot]['api_key']);
        }

        throw new Exception("Bot " . $bot . " not found");
    }

}
