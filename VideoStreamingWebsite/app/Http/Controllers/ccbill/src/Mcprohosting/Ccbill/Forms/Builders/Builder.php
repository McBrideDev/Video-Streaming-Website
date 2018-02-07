<?php

namespace App\Http\Controllers\ccbill\src\Mcprohosting\Ccbill\Forms\Builders;


use App\Http\Controllers\ccbill\src\Mcprohosting\Ccbill\Client\Client;
use App\Http\Controllers\ccbill\src\Mcprohosting\Ccbill\Forms\Form;

interface Builder
{
    /**
     * Creates some sort of built string out of the client and form.
     *
     * @param $client Client
     * @param $form Form
     * @return string
     */
    public function build(Client $client, Form $form);
}