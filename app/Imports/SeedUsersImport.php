<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SeedUsersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $user = User::create([
            'username' => $row['username'],
            'password' => Hash::make($row['password']),
            'name' => $row['name'],
            'email' => $row['email'],
            'role_id' => $row['role_id'],
            'opd_id' => $row['opd_id'],
        ]);

        return $user;
    }
}
