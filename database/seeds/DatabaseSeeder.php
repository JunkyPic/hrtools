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
         $this->createDefaultRolesAndPermissions();
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

    private function createDefaultRolesAndPermissions() {
      $roles_and_permission = new \App\Mapping\RolesAndPermissions();

      $roles_and_permission->createRolesAndPermissions();

      $user = \App\User::where('id', '=', 1)->first();

      $roles = $roles_and_permission->getAllRoles();

      foreach($roles as $role) {
          if(!$user->hasRole($role)) {
              $user->assignRole($role);
          }
      }
    }
}
