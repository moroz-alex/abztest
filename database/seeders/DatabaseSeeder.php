<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Position::all()->count() == 0) {
            Position::insert(
                [
                    [
                        'name' => 'Security',
                    ],
                    [
                        'name' => 'Designer',
                    ],
                    [
                        'name' => 'Content manager',
                    ],
                    [
                        'name' => 'Lawyer',
                    ],
                ]);
        }

        // Создаем "администратора"
        User::insert(
            [
                'name' => 'Admin',
                'email' => 'admin@admin',
                'phone' => '+380000000000',
                'position_id' => 1,
                'photo' => 'images/' . fake()->image(public_path('storage/images'),70, 70, null, false, true, null, false, 'jpg'),
                'email_verified_at' => null,
                'password' => '',
                'remember_token' => '',
            ]);

        User::factory(44)->create();
    }
}
