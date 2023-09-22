<?php

namespace App\DataTables;

use App\Models\MahasiswaModel;
use App\Models\Post;
use Yajra\DataTables\Services\DataTable;

class MahasiswaDataTable extends DataTable
{
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'post.action'); // 'action' adalah nama kolom yang digunakan untuk aksi (opsional)
    }

    public function query(MahasiswaModel $model)
    {
        return $model->newQuery();
    }

    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Bfrtip')
            ->orderBy(1); // Urutkan berdasarkan kolom pertama (ID) secara default
    }

    protected function getColumns()
    {
        return [
            'id'=> ['title' => 'ID'],
            'name' => ['title' => 'Nama Mahasiswa'],
            'fakultas' => ['title' => 'Fakultas'],
            'prodi' => ['title' => 'Prodi'],
            'phone' => ['title' => 'Nomor Telepon'],
            'gender' => ['title' => 'Jenis Kelamin'],
            'address' => ['title' => 'Alamat'],
            'born' => ['title' => 'tanggal Lahir'],
            // Anda bisa menambahkan kolom lain sesuai dengan struktur tabel Anda
        ];
    }
}
