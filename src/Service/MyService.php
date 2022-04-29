<?php

namespace App\Service;

class MyService
{
    public function getHappyMessage(): string
    {
        $messages = [
            'Akounamatata quel chason fantastique',
            'bonjour avec mon service prefere?',
            'ahhhh la vie de programmeur!!!!!',

        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}

