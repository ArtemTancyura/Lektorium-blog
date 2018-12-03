<?php

namespace App\Service;

class MessageService
{
    public function getMessage()
    {
        $messages = [
            'Hello my friend',
            'Nice to meet you',
            'Admin lox',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}