<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        Model::unguard();

        $this->call(UserSeeder::class);
        $this->call(CycleSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(ActivitySeeder::class);
        $this->call(PackageSeeder::class);

        \App\Models\Branch::truncate();
        \App\Models\Branch::create(['id' => 1, 'name' => 'Main Branch', 'status' => 'active', 'description' => 'HQ Facility']);
        \App\Models\Branch::create(['id' => 2, 'name' => 'Downtown Branch', 'status' => 'active', 'description' => 'Downtown Facility']);

        Model::reguard();
        DB::statement("SET foreign_key_checks=1");
    }
}
