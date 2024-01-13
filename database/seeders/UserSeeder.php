<?php

namespace Database\Seeders;

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
        \App\Models\User::factory(9)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Admin Maulana',
            'email' => 'maulana@fic12.com',
            'password' => Hash::make('berjayajaya'),
            'phone' => '089633755424',
            'roles' => 'ADMIN'
        ]);
    }
}
