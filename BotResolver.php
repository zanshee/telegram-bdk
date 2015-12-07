<?php

namespace Zanshee\TelegramBDKBundle;

/**
 * Class BotResolver
 *
 * @package Zanshee\TelegramBDKBundle
 */
class BotResolver extends TelegramAPI
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
     * @return bool
     */
    public function take($bot)
    {
        if (in_array($bot, array_keys($this->api_list))) {
            $this->current_bot = $this->api_list[$bot]["api_key"];
            return true;
        } else {
            return false;
        }
    }

}
