<?php

namespace Michaelrk02\PhpMq;

/**
 * Broadcaster class
 */
class Broadcaster
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
     * Broadcast a message
     *
     * @param string $channel Channel ID to broadcast into
     * @param string $event Event name
     * @param string $data Event data (do NOT include any newlines)
     *
     * @return int Broadcast HTTP status code
     */
    public function broadcast($channel, $event, $data)
    {
        $timestamp = time();
        $signature = hash_hmac('sha256', $channel.$timestamp.$event.$data, $this->secretKey);

        $request = curl_init($this->serverUrl.'broadcast.php');
        $params = [
            'channel' => $channel,
            'timestamp' => $timestamp,
            'event' => $event,
            'data' => $data,
            'signature' => $signature
        ];
        curl_setopt($request, CURLOPT_POST, true);
        curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_exec($request);

        return (int)curl_getinfo($request, CURLINFO_RESPONSE_CODE);
    }
}
