<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    // Veritabanına otomatik olarak veri ekleme işlemi
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
            'image' => 'news-' . time() . '.webp',
        ];
    }
}
