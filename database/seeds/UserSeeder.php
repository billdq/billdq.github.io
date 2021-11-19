<?php

use Illuminate\Database\Seeder;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::where("email", "admin@admin.com")->first())
            return;

        $user = new User();
        $user->{"uid"} = "admin@admin.com";
        $user->{"name"} = "Default Admin";
        $user->{"email"} = "admin@admin.com";
        $user->{"password"} = bcrypt("123456");
        $user->{"is_admin"} = true;
        $user->save();
    }
}
