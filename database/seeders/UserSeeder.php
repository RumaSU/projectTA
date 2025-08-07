<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

use App\Library\Helper as LibHelper;
use App\Models\Users;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $length = 10;
        for ($i = 0; $i < $length; $i++) {
            $uuid = LibHelper::generateUniqueUuId();
            $gender = ['Male', 'Female', 'Prefer not to say'];
            $name = fake()->name();
            $username = strtolower(implode('', explode(' ', $name)));
            
            $email = "rijal{$i}@gmail.com";
            
            if (Users\User::where('email', '=', $email)->exists()) {
                continue;
            }
            
            Users\User::create([
                'id_user' => $uuid,
                'email' => $email,
                'username' => $username,
                'password' => Hash::make('321654987'),
            ]);
            
            Users\UserPersonal::create([
                'id_user' => $uuid,
                'fullname' => $name,
                'gender' => $gender[rand(0, count($gender) - 1)],
                'birthdate' => fake()->date(),
            ]);
            
            
            
        }
    }
}
