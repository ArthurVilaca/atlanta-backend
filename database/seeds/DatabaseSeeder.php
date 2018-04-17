<?php

use Illuminate\Database\Seeder;
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
        DB::table('users')->insert([
            'name' => 'Arthur Vilaca',
            'username' => 'arthur_vilaca',
            'password' => bcrypt('123'),
            'user_type' => 'U',
            'status' => 'APPROVED'
        ]);

        // basic dealer
        $dealer = DB::table('users')->insert([
            'name' => 'Rev Teste',
            'username' => 'rev_teste',
            'password' => bcrypt('123'),
            'user_type' => 'D',
            'status' => 'APPROVED',
        ]);

        DB::table('dealers')->insert([
            'registration_code' => '0000000000',
            'user_id' => 2
        ]);

        DB::table('plans')->insert([
            'name' => 'Plano Basico',
            'value' => 90,
            'description' => 'Plano basico de ASDASDASDASD',
            'pass_through' => 20
        ]);

        DB::table('plans')->insert([
            'name' => 'Plano Intermediario',
            'value' => 150,
            'description' => 'Plano Intermediario de ASDASDASDASD',
            'pass_through' => 20
        ]);

        DB::table('plans')->insert([
            'name' => 'Plano Premium',
            'value' => 210,
            'description' => 'Plano Premium de ASDASDASDASD',
            'pass_through' => 20
        ]);
    }
}
