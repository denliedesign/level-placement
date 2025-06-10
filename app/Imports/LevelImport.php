<?php

namespace App\Imports;

use App\Models\Level;
use Maatwebsite\Excel\Concerns\ToModel;

class LevelImport implements ToModel
{

    public function model(array $row)
    {
        // Replace empty values with "0"
        $row = array_map(function ($value) {
            return empty($value) ? '0' : $value;
        }, $row);
        return new Level([
            'first_name'    => $row[0],
            'last_name'     => $row[1],
            'email'         => $row[2],
            'ballet'        => $row[3],
            'jazz'          => $row[4],
            'tap'           => $row[5],
            'pointe'        => $row[6],
            'acro'          => $row[7],
        ]);
    }
}
