<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionCreate extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Cria permissões
        Permission::create(['name' => 'manage users']);
        Permission::create(['name' => 'manage schedule']);
        Permission::create(['name' => 'manage bus']);
        Permission::create(['name' => 'manage routes']);
        Permission::create(['name' => 'manage city']);
        Permission::create(['name' => 'manage province']);

        Permission::create(['name' => 'manage tickets']);
        Permission::create(['name' => 'validate tickets']);
        Permission::create(['name' => 'manage passenger']);
        Permission::create(['name' => 'view reports']);

        // Cria roles e atribui permissões
        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $schedule = Role::create(['name' => 'Agendador']);
        $schedule->givePermissionTo(['manage schedule', 'manage bus', 'manage routes', 'manage city', 'manage province']);

        $agente = Role::create(['name' => 'Agente']);
        $agente->givePermissionTo(['manage tickets', 'view reports', 'manage passenger']);

        $fiscal = Role::create(['name' => 'Fiscal']);
        $fiscal->givePermissionTo(['validate tickets']);

        // Cria admin padrão (roda só uma vez)
        // $user = \App\Models\User::create([
        //     'name' => 'Administrador',
        //     'email' => 'admin@eticketlink.co.mz',
        //     'password' => bcrypt('admin123'),
        // ]);
        // $user->assignRole('Admin');
    }
}
