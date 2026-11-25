<?php

namespace Database\Seeders;

use App\Models\Education;
use App\Models\Profile;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@example.com')],
            ['name' => 'Admin', 'password' => Hash::make(env('ADMIN_PASSWORD', 'ChangeMe123!'))]
        );

        if (!Profile::exists()) {
            Profile::create([
                'name'                => 'Your Name',
                'headline'            => 'Documentary Maker & Content Creator',
                'bio'                 => 'Write a short bio about yourself here.',
                'email'               => 'you@example.com',
                'youtube_channel_url' => 'https://www.youtube.com/@yourchannel',
            ]);
            SocialLink::create(['platform' => 'Facebook', 'url' => 'https://facebook.com/yourpage', 'sort_order' => 1]);
            Education::create([
                'institution' => 'Your University', 'degree' => 'B.Sc.',
                'field' => 'Computer Science & Engineering', 'start_year' => '2018', 'end_year' => '2022', 'sort_order' => 1,
            ]);
        }
    }
}
