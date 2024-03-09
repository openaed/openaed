<?php
namespace App\Models;

class AttributedResponse
{
    /**
     * Create a new AttributedResponse instance.
     *
     * @param mixed $data The data to be returned
     */
    public static function new($data)
    {
        return [
            'meta' => [
                'attribution' => 'Data provided in part by OpenStreetMap',
                'copyright' => 'https://www.openstreetmap.org/copyright',
                'timestamp' => time(),
            ],
            'result' => $data,
        ];
    }
}