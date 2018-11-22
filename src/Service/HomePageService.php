<?php

namespace App\Service;

class HomePageService
{
    public function getPage()
    {
        $faker = \Faker\Factory::create();
        
        $content = [
            
            'text' => $faker->realText(500),
            
            'name' => $faker->name,

            'img' => $faker->imageUrl(640,440),
            
            'date' => date('Y-m-d h:i'),

            'title' => $faker->realText(15)
        ];

        return $content;   
        
    }
}