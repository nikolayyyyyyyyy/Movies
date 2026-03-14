<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Actor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin'],
            ['name' => 'User'],
        ];
        Role::insert($roles);

        $categories = [
            ['name' => 'Action'],
            ['name' => 'Adventure'],
            ['name' => 'Comedy'],
            ['name' => 'Drama'],
            ['name' => 'Fantasy'],
            ['name' => 'Horror'],
            ['name' => 'Mystery'],
            ['name' => 'Romance'],
            ['name' => 'Thriller']
        ];
        Category::insert($categories);

        $actors = [
            [
                'name' => 'Robert De Niro',
                'profile_picture' => 'actor_pictures/deniro.jpg',
            ],
            [
                'name' => 'Al Pacino',
                'profile_picture' => 'actor_pictures/pacino.jpg',
            ],
        ];
        Actor::insert($actors);

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin',
                'password' => Hash::make('password'),
                'role_id' => 1
            ]
        ];
        User::insert($users);
    }
}
