<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\Auth\Database\Seeders\RoleSeeder;
use Modules\Auth\Models\User;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class BuilderInitCommand extends Command
{
    protected $signature = 'builder:init';

    protected $description = 'Initialize Builder with roles and an admin user';

    public function handle()
    {
        info('Seeding roles and permissions...');

        $this->call(RoleSeeder::class);

        info('Roles and permissions seeded successfully.');

        $firstName = text(
            label: 'First Name',
            validate: ['first_name' => ['required', 'string', 'max:100']]
        );

        $lastName = text(
            label: 'Last Name',
            validate: ['last_name' => ['required', 'string', 'max:100']]
        );

        $email = text(
            label: 'Email Address',
            validate: ['email' => ['required', 'string', 'lowercase', 'email', 'max:100', 'unique:users,email']]
        );
        $password = password(
            label: 'Password',
            validate: ['password' => ['required', Password::defaults()]]
        );

        $confirmed = confirm('Are you ready to create the admin user?', default: true);

        if (! $confirmed) {
            info('Cancelled.');

            return Command::FAILURE;
        }

        $user = User::create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole('administrator');

        info("Admin user {$user->email} created successfully.");

        return Command::SUCCESS;
    }
}
