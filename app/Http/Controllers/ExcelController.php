<?php

namespace App\Http\Controllers;

use App\DataTables\MahasiswaDataTable;
use App\Models\MahasiswaModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelController extends Controller
{
    public function data()  {
        $data = MahasiswaModel::all();
        return response()->json([
            'data' => $data,
            'recordTotal' => MahasiswaModel::count(),
            'recordsFiltered' => $data->count(),
        ]);
    }

    public function index(){
        return view('home');
    }

    public function import(Request $request){
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);
    
        $file = $request->file('file');
        $path = $file->getRealPath();
        // Baca file Excel
         // Baca file Excel
    $spreadsheet = IOFactory::load($path);
    $worksheet = $spreadsheet->getActiveSheet();
    
    $headerRow = $worksheet->getRowIterator()->current();
    $columnIndex = 1;
    $missingColumns = [];
    
    // Kolom yang diperlukan
    $requiredColumns = ['Nama Mahasiswa', 'Fakultas', 'Prodi', 'No Telpon', 'Jenis Kelamin', 'Alamat', 'Tanggal Lahir'];
    
    // Memeriksa kolom yang hilang
    foreach ($requiredColumns as $column) {
        $cellValue = $worksheet->getCellByColumnAndRow($columnIndex, 1)->getValue();
        if ($cellValue !== $column) {
            $missingColumns[] = $column;
        }
        $columnIndex++;
    }
    
    if (!empty($missingColumns)) {
        // Handle jika ada kolom yang hilang
        return response()->json(['error' => 'Kolom berikut tidak ditemukan: ' . implode(', ', $missingColumns)], 400);
    }
        // Loop melalui baris-baris spreadsheet untuk mengambil data
    foreach ($worksheet->getRowIterator(2) as $row) {
        $cellIterator = $row->getCellIterator();
        $cellIterator->setIterateOnlyExistingCells(false);

        $data = [];
        foreach ($cellIterator as $cell) {
            $data[] = $cell->getValue();
        }

        // Simpan data ke dalam database
        MahasiswaModel::create([
            "name" => $data[0],
            "fakultas" => $data[1],
            "prodi" => $data[2],
            "phone" => $data[3],
            "gender" => $data[4],
            "address" => $data[5],
            "born" => $data[6]
        ]);
    }
        return redirect()->back();
    }

    public function export(){
        $mahasiswa = MahasiswaModel::all();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $header = ["ID","Nama Mahasiswa", "Fakultas","Prodi","Phone","Jenis Kelamin","Alamat","Tanggal Lahir"];
        $sheet->fromArray([$header]);

        foreach($mahasiswa as $data){
            $mahasiswaData = [
                $data->id,
                $data->name,
                $data->fakultas,
                $data->prodi,
                $data->phone,
                $data->gender,
                $data->address,
                $data->born,
            ];
    
            // Add Mahasiswa data to the sheet
            $sheet->fromArray([$mahasiswaData]);
        }

        $writer = new WriterXlsx($spreadsheet);
        $filename = 'mahasiswa.xlsx';
        $writer->save($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }
    
}
