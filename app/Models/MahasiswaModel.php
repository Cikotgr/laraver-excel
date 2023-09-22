<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MahasiswaModel extends Model
{
    use HasFactory;
    protected $table = 'data_mahasiswa';
    protected $fillable =[
        "name",
        "fakultas",
        "prodi",
        "phone",
        "gender",
        "address",
        "born"
    ];
}
