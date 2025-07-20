<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'admin',
            'description' => 'Administrator with full access'
        ]);

        Role::create([
            'name' => 'librarian',
            'description' => 'Librarian with book management access'
        ]);

        Role::create([
            'name' => 'student',
            'description' => 'Student with limited access'
        ]);
    }
} 