<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\LecturerUnit;
use App\Models\StudentCourse;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
        $superAdminRole = Role::create(['name'=>'super-admin', 'guard_name'=>'api']);

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

        $password = '12345';

        // users
        $webStudent = User::create([
            'name'=>'Web student',
            'email'=>'webStudent@gmail.com',
            'password'=> Hash::make($password)
            //'guard_name'=>'web'
        ]);

        $apiStudent = User::create([
            'name'=>'APi student',
            'email'=>'apiStudent@gmail.com',
            'password'=> Hash::make($password)
        ]);

        $lecturer = User::create([
            'name'=>'Lecturer',
            'email'=>'lecturer@gmail.com',
            'password'=> Hash::make($password)
        ]);

        $admin = User::create([
            'name'=>'Admin',
            'email'=>'admin@gmail.com',
            'password'=> Hash::make($password)
        ]);

        $ics = Course::create([
            'name' => 'Computer Science',
            'created_by' => $admin->id,
        ]);

        $studentCourse = StudentCourse::create([
            'student_id' => $apiStudent->id,
            'course_id' => $ics->id
        ]);

        $unit = Unit::create([
           'name' => 'Discrete Maths',
            'course_id' => $ics->id
        ]);

        $lecturerUnit = LecturerUnit::create([
            'lecturer_id' => $lecturer->id,
            'unit_id' => $unit->id
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
