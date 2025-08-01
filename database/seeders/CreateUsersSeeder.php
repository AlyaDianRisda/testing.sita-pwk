<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
  
class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $users = [
            [
               'name'=>'Andika',
               'email'=>'admin@sita-pwk',
               'type'=>1,
               'password'=> bcrypt('123'),
            ],
            [
               'name'=>'Bagaskara',
               'email'=>'admin2@sita-pwk',
               'type'=>1,
               'password'=> bcrypt('123'),
            ],
            [
               'name'=>'Andini',
               'nip'=>'000000000000 001',
               'wa_dos'=>'082188888888',
               'email'=>'dosen@sita-pwk',
               'tipe_dos'=>'Utama',
               'type'=> 2,
               'password'=> bcrypt('123'),
            ],
            [
               'name'=>'Budi',
               'nip'=>'000000000000 002',
               'wa_dos'=>'082288888888',
               'email'=>'dosen2@sita-pwk',
               'tipe_dos'=>'Utama',
               'type'=> 2,
               'password'=> bcrypt('123'),
            ],
            [
               'name'=>'Putri',
               'nip'=>'000000000000 003',
               'wa_dos'=>'082388888888',
               'email'=>'dosen3@sita-pwk',
               'tipe_dos'=>'Pendamping',
               'type'=> 2,
               'password'=> bcrypt('123'),
            ],
            [
               'name'=>'Anna',
               'nim'=>'120140148',
               'email'=>'user@sita-pwk',
               'type'=>0,
               'password'=> bcrypt('123'),
            ],
            [
               'name'=>'Bobby',
               'nim'=>'120140143',
               'email'=>'user2@sita-pwk',
               'type'=>0,
               'password'=> bcrypt('123'),
            ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}