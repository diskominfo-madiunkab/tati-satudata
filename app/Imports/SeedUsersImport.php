<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class SeedUsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'username' => $row[0],
            'password' => Hash::make($row[1]),
            'name' => $row[2],
            'email' => $row[3],
            'role_id' => $row[4],
            'opd_id' => $row[5],
        ]);
    }
}
