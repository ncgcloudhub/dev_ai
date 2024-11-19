<?php

namespace App\Imports;

use Spatie\Permission\Models\Permission;
use Maatwebsite\Excel\Concerns\ToModel;

class PermissionImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       // Check if the permission already exists
       $existingPermission = Permission::where('name', $row[0])->first();

       // If it doesn't exist, create a new permission
       if (!$existingPermission) {
           return new Permission([
               'name'       => $row[0],
               'group_name' => $row[1],
           ]);
       }

       // If it exists, do nothing (return null)
       return null;
    }
}
