<?php

namespace Michaelrk02\PhpMq;

/**
 * Listener class
 */
class Listener
{
    /**
     * @var string $serverUrl
     */
    protected $serverUrl;

    /**
     * @var string $secretKey
     */
    protected $secretKey;


    /**
     * Construct a new Broadcaster object
     *
     * @param string $serverUrl Broadcaster server URL
     * @param string $secretKey Secret key used for request authenticity
     */
    public function __construct($serverUrl, $secretKey)
    {
        $this->serverUrl = $serverUrl;
        $this->secretKey = $secretKey;
    }

    /**
     * Redirect to server listener URL
     *
     * @param string $channel Channel to listen on
     *
     * @return void
     */
    public function listen($channel)
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $channel.$timestamp, $this->secretKey);

        $params = [
            'channel' => $channel,
            'timestamp' => $timestamp,
            'signature' => $signature
        ];
        $listenerUrl = $this->serverUrl.'listen.php?'.http_build_query($params);

        http_response_code(301);
        header('Location: '.$listenerUrl);
        exit;
    }
}
