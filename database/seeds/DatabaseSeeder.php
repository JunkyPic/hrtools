<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if(env('APP_ENV') == 'local') {
            $this->call(QuestionTableSeeder::class);
        }
         $this->createAdmin();
         $this->createDefaultRoles();
    }

    private function createAdmin() {
        DB::table('users')->insert([
            'username' => 'admin',
            'email' => 'hrtools@optaros.com',
            'password' => Hash::make('hrtoolsadmin'),
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }

    private function createDefaultRoles() {
        \Spatie\Permission\Models\Role::create(['name' => 'content creator']);
        \Spatie\Permission\Models\Role::create(['name' => 'reviewer']);
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'invite users']);
        \Spatie\Permission\Models\Role::create(['name' => 'invite candidates']);

        $user = \App\User::where('id', '=', 1)->first();

        $roles = ['content creator', 'reviewer', 'admin', 'invite users', 'invite candidates'];

        foreach($roles as $role) {
            if(!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }
}
