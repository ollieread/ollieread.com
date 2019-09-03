<?php

use Illuminate\Database\Seeder;
use Ollieread\Users\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = require __DIR__ . '/data/roles.php';

        foreach ($data as $row) {
            $role = (Role::query()->where('ident', '=', $row['ident'])->first() ?? new Role)
                ->fill($row);

            if ($role->save()) {
                $this->command->info(sprintf('Role %s added/updated', $role->ident));
            }
        }
    }
}
