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

    /**
     * Send a PING request
     *
     * This should be called periodically when the stream connection is
     * alive
     *
     * @param int $client Client connection ID
     *
     * @return int PING HTTP response code
     */
    public function ping($client)
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $client.$timestamp, $this->secretKey);

        $request = curl_init($this->serverUrl.'ping.php');
        $params = [
            'client' => $client,
            'timestamp' => $timestamp,
            'signature' => $signature
        ];
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_exec($request);

        return curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    }
}
