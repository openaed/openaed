<?php
namespace App\Models;

use Illuminate\Http\Request;

class AttributedResponse
{
    /**
     * Create a new AttributedResponse instance.
     *
     * @param Request $request The request object
     * @param mixed $data The data to be returned
     */
    public static function new(mixed $data)
    {
        return [
            'openapi' => '3.1.0',
            'info' => [
                'title' => 'OpenAED',
                'contact' => [
                    'name' => 'OpenAED',
                    'url' => route('about-us'),
                    'email' => 'api@openaed.nl'
                ],
                'license' => [
                    'name' => 'Open Data Commons Open Database License v1.0',
                    'identifier' => 'ODbL-1.0',
                    'url' => 'https://opendatacommons.org/licenses/odbl/1.0/'
                ],
                'version' => '1.0.0',
            ],
            'result' => $data,
        ];
    }
}