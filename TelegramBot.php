<?php

namespace Zanshee\TelegramBDKBundle;

use CURLFile;

class TelegramBot
{
    const BASE_URL = 'https://api.telegram.org/bot';

    private $api_key;

    /**
     * TelegramBot constructor.
     *
     * @param $api_key
     */
    public function __construct($api_key)
    {
        $this->api_key = $api_key;
    }

    /**
     * Hide the current custom keyboard and display the default letter-keyboard.
     *
     * @link https://core.telegram.org/bots/api#replykeyboardhide
     *
     * @param bool $selective
     *
     * @return string
     */
    public static function replyKeyboardHide($selective = false)
    {
        $hide_keyboard = true;

        return json_encode(compact('hide_keyboard', 'selective'));
    }

    /**
     * Display a reply interface to the user (act as if the user has selected the bots message and tapped 'Reply').
     *
     * @link https://core.telegram.org/bots/api#forcereply
     *
     * @param bool $selective
     *
     * @return string
     */
    public static function forceReply($selective = false)
    {
        $force_reply = true;

        return json_encode(compact('force_reply', 'selective'));
    }

    /**
     * A simple method for testing your bot's auth token.
     * Returns basic information about the bot in form of a User object.
     *
     * @link https://core.telegram.org/bots/api#getme
     * @return array
     */
    public function getMe()
    {
        return $this->sendRequest('getMe', array());
    }

    /**
     * Use this method to receive incoming updates using long polling.
     *
     * @link https://core.telegram.org/bots/api#getupdates
     *
     * @param int $offset
     * @param int $limit
     * @param int $timeout
     *
     * @return array
     */
    public function pollUpdates($offset = null, $timeout = null, $limit = null)
    {
        $params = compact('offset', 'limit', 'timeout');

        return $this->sendRequest('getUpdates', $params);
    }

    /**
     * Send text messages.
     *
     * @link https://core.telegram.org/bots/api#sendmessage
     *
     * @param int    $chat_id
     * @param string $text
     * @param string $parse_mode Send 'Markdown' or 'HTML'
     * @param bool   $disable_web_page_preview
     * @param bool   $disable_notification
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendMessage($chat_id, $text, $parse_mode = "", $disable_web_page_preview = false, $disable_notification = false, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'text', 'parse_mode', 'disable_web_page_preview', 'disable_notification', 'reply_to_message_id', 'reply_markup');

        return $this->sendRequest('sendMessage', $params);
    }

    /**
     * Forward messages of any kind.
     *
     * @link https://core.telegram.org/bots/api#forwardmessage
     *
     * @param int $chat_id
     * @param int $from_chat_id
     * @param int $message_id
     *
     * @return array
     */
    public function forwardMessage($chat_id, $from_chat_id, $message_id)
    {
        $params = compact('chat_id', 'from_chat_id', 'message_id');

        return $this->sendRequest('forwardMessage', $params);
    }

    /**
     * Send Photos.
     *
     * @link https://core.telegram.org/bots/api#sendphoto
     *
     * @param int    $chat_id
     * @param string $photo
     * @param string $caption
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendPhoto($chat_id, $photo, $caption = null, $reply_to_message_id = null, $reply_markup = null)
    {
        $data = compact('chat_id', 'photo', 'caption', 'reply_to_message_id', 'reply_markup');

        if (((!is_dir($photo)) && (filter_var($photo, FILTER_VALIDATE_URL) === false)))
            return $this->sendRequest('sendPhoto', $data);

        return $this->uploadFile('sendPhoto', $data);
    }

    /**
     * Send Audio.
     *
     * @link https://core.telegram.org/bots/api#sendaudio
     *
     * @param int    $chat_id
     * @param string $audio
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendAudio($chat_id, $audio, $reply_to_message_id = null, $reply_markup = null)
    {
        $data = compact('chat_id', 'audio', 'reply_to_message_id', 'reply_markup');

        if (((!is_dir($audio)) && (filter_var($audio, FILTER_VALIDATE_URL) === false)))
            return $this->sendRequest('sendAudio', $data);

        return $this->uploadFile('sendAudio', $data);
    }

    /**
     * Send Document.
     *
     * @link https://core.telegram.org/bots/api#senddocument
     *
     * @param int    $chat_id
     * @param string $document
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendDocument($chat_id, $document, $reply_to_message_id = null, $reply_markup = null)
    {
        $data = compact('chat_id', 'document', 'reply_to_message_id', 'reply_markup');

        if (((!is_dir($document)) && (filter_var($document, FILTER_VALIDATE_URL) === false)))
            return $this->sendRequest('sendDocument', $data);

        return $this->uploadFile('sendDocument', $data);
    }

    /**
     * Send Sticker.
     *
     * @link https://core.telegram.org/bots/api#sendsticker
     *
     * @param int    $chat_id
     * @param string $sticker
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendSticker($chat_id, $sticker, $reply_to_message_id = null, $reply_markup = null)
    {
        $data = compact('chat_id', 'sticker', 'reply_to_message_id', 'reply_markup');

        if (((!is_dir($sticker)) && (filter_var($sticker, FILTER_VALIDATE_URL) === false)))
            return $this->sendRequest('sendSticker', $data);

        return $this->uploadFile('sendSticker', $data);
    }

    /**
     * Send Video.
     *
     * @link https://core.telegram.org/bots/api#sendvideo
     *
     * @param int    $chat_id
     * @param string $video
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendVideo($chat_id, $video, $reply_to_message_id = null, $reply_markup = null)
    {
        $data = compact('chat_id', 'video', 'reply_to_message_id', 'reply_markup');

        if (((!is_dir($video)) && (filter_var($video, FILTER_VALIDATE_URL) === false)))
            return $this->sendRequest('sendVideo', $data);

        return $this->uploadFile('sendVideo', $data);
    }

    /**
     * Send Location.
     *
     * @link https://core.telegram.org/bots/api#sendlocation
     *
     * @param int    $chat_id
     * @param float  $latitude
     * @param float  $longitude
     * @param int    $reply_to_message_id
     * @param string $reply_markup
     *
     * @return array
     */
    public function sendLocation($chat_id, $latitude, $longitude, $reply_to_message_id = null, $reply_markup = null)
    {
        $params = compact('chat_id', 'latitude', 'longitude', 'reply_to_message_id', 'reply_markup');

        return $this->sendRequest('sendLocation', $params);
    }

    /**
     * Send Chat Action.
     *
     * @link https://core.telegram.org/bots/api#sendchataction
     *
     * @param int    $chat_id
     * @param string $action
     *
     * @return array
     */
    public function sendChatAction($chat_id, $action)
    {

//TODO Verification of actions w/o Exceptions, when incorrect input or provide TelegramException !!

//        $actions = array(
//            'typing',
//            'upload_photo',
//            'record_video',
//            'upload_video',
//            'record_audio',
//            'upload_audio',
//            'upload_document',
//            'find_location',
//        );
//        if (isset($action) && in_array($action, $actions)) {
        $params = compact('chat_id', 'action');

        return $this->sendRequest('sendChatAction', $params);
//        }

//        throw new TelegramException('Invalid Action! Accepted value: ' . implode(', ', $actions));
    }

    /**
     * Get user profile photos.
     *
     * @link     https://core.telegram.org/bots/api#getsserprofilephotos
     *
     * @param int $user_id
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getUserProfilePhotos($user_id, $offset = null, $limit = null)
    {
        $params = compact('user_id', 'offset', 'limit');

        return $this->sendRequest('getUserProfilePhotos', $params);
    }

    /**
     * Set a Webhook to receive incoming updates via an outgoing webhook.
     *
     * @param string $url HTTPS url to send updates to.
     *
     * @return array
     */
    public function setWebhook($url)
    {
//        if (filter_var($url, FILTER_VALIDATE_URL) === false)
//            throw new TelegramException('Invalid URL provided');
//
//        if (parse_url($url, PHP_URL_SCHEME) !== 'https')
//            throw new TelegramException('Invalid URL, it should be a HTTPS url.');

        return $this->sendRequest('setWebhook', compact('url'));
    }

    /**
     * Returns webhook updates sent by Telegram.
     * Works only if you set a webhook.
     *
     * @see setWebhook
     * @return array
     */
    public function getWebhookUpdates()
    {
        $body = json_decode(file_get_contents('php://input'), true);

        return $body;
    }

    /**
     * Removes the outgoing webhook.
     *
     * @return array
     */
    public function removeWebhook()
    {
        $url = '';

        return $this->sendRequest('setWebhook', compact('url'));
    }

    /**
     * Builds a custom keyboard markup.
     *
     * @link https://core.telegram.org/bots/api#replykeyboardmarkup
     *
     * @param array $keyboard
     * @param bool  $resize_keyboard
     * @param bool  $one_time_keyboard
     * @param bool  $selective
     *
     * @return string
     */
    public function replyKeyboardMarkup($keyboard, $resize_keyboard = false, $one_time_keyboard = false, $selective = false)
    {
        return json_encode(compact('keyboard', 'resize_keyboard', 'one_time_keyboard', 'selective'));
    }

    private function sendRequest($method, $params)
    {
        return json_decode(file_get_contents($this::BASE_URL . $this->api_key . DIRECTORY_SEPARATOR . $method . '?' . http_build_query($params)), true);
    }

    private function uploadFile($method, $data)
    {
        $key = array(
            'sendPhoto'    => 'photo',
            'sendAudio'    => 'audio',
            'sendDocument' => 'document',
            'sendSticker'  => 'sticker',
            'sendVideo'    => 'video',
        );
        $url = false;
        $newFile = null;

        $file = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . mt_rand(0, 9999);
        if (filter_var($data[$key[$method]], FILTER_VALIDATE_URL)) {
            $url = true;
            file_put_contents($file, file_get_contents($data[$key[$method]]));
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $file);

            $extensions = array(
                'image/jpeg' => '.jpg',
                'image/png'  => '.png',
                'image/gif'  => '.gif',
                'image/bmp'  => '.bmp',
                'image/tiff' => '.tif',
                'audio/ogg'  => '.ogg',
                'video/mp4'  => '.mp4',
                'image/webp' => '.webp',
            );

            if ($method != 'sendDocument') {
                if (!array_key_exists($mime_type, $extensions)) {
                    unlink($file);
//TODO avoid or provide TelegramException !!
//                    throw new TelegramException('Bad file type/extension');
                }
            }

            $newFile = $file . $extensions[$mime_type];
            rename($file, $newFile);
            $data[$key[$method]] = new CurlFile($newFile, $mime_type, $newFile);
        } else {
            $file_info = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($file_info, $data[$key[$method]]);
            $data[$key[$method]] = new CurlFile($data[$key[$method]], $mime_type, $data[$key[$method]]);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this::BASE_URL . $this->api_key . DIRECTORY_SEPARATOR . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = json_decode(curl_exec($ch), true);

        if ($url) unlink($newFile);

        return $response;
    }

}
