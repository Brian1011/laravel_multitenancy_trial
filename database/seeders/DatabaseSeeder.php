<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // Default Roles
        $apiStudentRole = Role::create(['name'=>'student', 'guard_name'=>'api']);
        $webStudentRole = Role::create(['name'=>'student', 'guard_name'=>'web']);
        $lecturerRole = Role::create(['name'=>'lecturer', 'guard_name'=>'api']);

        // Default permissions
        $permissions = [
            ['name' => 'add course', 'guard_name'=>'api'],
            ['name' => 'view courses api', 'guard_name'=>'api'],
            ['name' => 'view courses', 'guard_name'=>'web'],
            ['name' => 'add unit', 'guard_name'=>'api'],
            ['name' => 'view unit', 'guard_name'=>'api'],
            ['name' => 'edit unit'],
            ['name' => 'delete unit'],
            ['name' => 'view units', 'guard_name'=>'web'],
            ['name' => 'view lecturers', 'guard_name'=>'web']
        ];

       collect($permissions)->each(function ($item){
        Permission::create($item);
       });

        // role permission
        $apiStudentRole->givePermissionTo(['view courses api', 'view unit', 'add unit']);
        $webStudentRole->givePermissionTo(['view units', 'view lecturers']);
        $lecturerRole->givePermissionTo(['add unit','view unit']);
        $apiStudentRole->givePermissionTo('add course');

        // users
        $webStudent = User::create([
            'name'=>'Web student',
            'email'=>'webStudent@gmail.com',
            'password'=>'12345',
            //'guard_name'=>'web'
        ]);

        $apiStudent = User::create([
            'name'=>'APi student',
            'email'=>'apiStudent@gmail.com',
            'password'=>'12345'
        ]);

        $lecturer = User::create([
            'name'=>'Lecturer',
            'email'=>'lecturer@gmail.com',
            'password'=>'12345'
        ]);

        $admin = User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=>'12345'
        ]);

        // assign role
        // $webStudent->assignRole($webStudentRole);
        $apiStudent->assignRole($apiStudentRole);
        $lecturer->assignRole($lecturerRole);

        // assign specific role
        $apiStudent->givePermissionTo('add course');
        // $webStudent->givePermissionTo('view lecturers');
    }
}
