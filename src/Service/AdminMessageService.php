<?php

namespace App\Service;

class AdminMessageService
{
    public function getAdminMessage()
    {
        $messages = [
            'Hello Admin',
            'Nice to meet you, admin',
            'Admin lox',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}