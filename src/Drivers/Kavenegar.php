<?php

namespace Tzsk\Sms\Drivers;

use Kavenegar\KavenegarApi;
use Tzsk\Sms\Contracts\Driver;

class Kavenegar extends Driver
{
    protected array $settings;

    protected KavenegarApi $client;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
        $this->client = new KavenegarApi(data_get($this->settings, 'apiKey'));
    }

    public function send()
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->Send(data_get($this->settings, 'from'), $recipient, $this->body)
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }

    public function verify(string $template, string $token1, string $token2 = null, string $token3 = null)
    {
        $response = collect();
        foreach ($this->recipients as $recipient) {
            $response->put(
                $recipient,
                $this->client->VerifyLookup($recipient, $template, $token1, $token2, $token3)
            );
        }

        return (count($this->recipients) == 1) ? $response->first() : $response;
    }
}
