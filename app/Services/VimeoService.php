<?php

namespace App\Services;

use Vimeo\Vimeo;

class VimeoService
{
    protected Vimeo $client;

    public function __construct()
    {
        $this->client = new Vimeo(
            config('services.vimeo.client_id'),
            config('services.vimeo.client_secret'),
            config('services.vimeo.access_token')
        );
    }

    public function getClient(): Vimeo
    {
        return $this->client;
    }




}
