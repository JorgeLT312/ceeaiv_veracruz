<?php

namespace Database\Seeders;

use App\Models\User;
//use Spatie\Permission\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1=User::factory()->create([
            'name' => 'Stephania Cal y Mayor',
            'username' => 'stephania',
            'email' => 'stephania@importare.mx',
            'password' => Hash::make('password'),
            'area_id' => '1',
            'role_id'=>'1']);

        $user1->assignRole('Admin');

        $user2=User::factory()->create([
            'name' => 'Mario Ruiz',
            'username' => 'marioruiz',
            'email' => 'mario@importare.mx',
            'password' => Hash::make('password'),
            'area_id' => '1',
            'role_id'=>'1']);

        $user2->assignRole('Admin');

        $user3=User::factory()->create([
            'name' => 'Isaac López',
            'username' => 'isaaclopez',
            'email' => 'isaac@importare.mx',
            'password' => Hash::make('password'),
            'area_id' => '1',
            'role_id'=>'1']);

        $user3->assignRole('Admin');

        $user4=User::factory()->create([
            'name' => 'CeeaivAdmin',
            'username' => 'ceeaivAdmin',
            'email' => 'ceeaiv@mail.com',
            'password' => Hash::make('password'),
            'area_id' => '1',
            'role_id'=>'1']);

        $user4->assignRole('Admin');

        User::factory()->create([
            'name' => 'Operativo',
            'username' => 'operativo',
            'email' => 'Operativo@importare.mx',
            'password' => Hash::make('password'),
            'area_id' => '2',
            'role_id'=>'3']);

        User::factory()->create([
            'name' => 'Superior',
            'username' => 'superior',
            'email' => 'superior@importare.mx',
            'password' => Hash::make('password'),
            'area_id' => '3',
            'role_id'=>'4']);
    }
}
