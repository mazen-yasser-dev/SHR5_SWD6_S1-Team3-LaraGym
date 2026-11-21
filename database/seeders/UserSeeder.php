<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        User::create([
            'id' => 1,
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'is_admin' => true,
            'password' => bcrypt('password'),
            'account_number' => '000000000001',
            'avatar' => 'https://ui-avatars.com/api/?name=Admin'
        ]);

        for ($i = 2; $i <= 10; $i++) {
            User::create([
                'id' => $i,
                'name' => "Member $i",
                'email' => "member$i@example.com",
                'is_admin' => false,
                'password' => bcrypt('password'),
                'account_number' => str_pad($i, 12, "0", STR_PAD_LEFT),
                'avatar' => "https://ui-avatars.com/api/?name=Member+$i"
            ]);
        }
    }
}
