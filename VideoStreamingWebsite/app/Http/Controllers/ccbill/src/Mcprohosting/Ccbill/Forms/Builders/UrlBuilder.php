<?php

namespace App\Http\Controllers\ccbill\src\Mcprohosting\Ccbill\Forms\Builders;

use App\Http\Controllers\ccbill\src\Mcprohosting\Ccbill\Client\Client;
use App\Http\Controllers\ccbill\src\Mcprohosting\Ccbill\Forms\Form;

class UrlBuilder implements Builder
{
    public function build(Client $client, Form $form)
    {
        return $client->buildUrl($form->getPath()) . '?' . http_build_query($form->serialize());
    }
}