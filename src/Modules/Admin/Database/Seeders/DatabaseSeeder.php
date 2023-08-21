<?php

namespace Modules\Admin\Database\Seeders;

use App\Models\Contact;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'name' => 'Lê Toản',
            'first_name' => 'Toan',
            'last_name' => 'Le',
            'email' => 'toanld1905@gmail.com',
            'password' => 'secret',
            'owner' => true,
        ]);

    }
}
