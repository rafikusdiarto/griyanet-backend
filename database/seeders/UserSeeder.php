<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customer = User::create([
            'name' => 'sales',
            'nip' => '12345678',
            'email' => 'sales@mail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'remember_token' => \Str::random(60),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        event(new Registered($customer));
        $customer->assignRole('sales');

        $admin = User::create([
            'name' => 'admin',
            'nip' => '0001888',
            'email' => 'admin@mail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password123'),
            'remember_token' => \Str::random(60),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        event(new Registered($admin));
        $admin->assignRole('admin');
    }
}
