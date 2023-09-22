<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('') }}">
    <script src="{{ asset('js/app.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css" />
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>

</head>

<body>
    <div class="container">
        <form id="form-import" action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Pilih File Excel:</label>
                <input type="file" name="file" accept=".xlsx">
            </div>
            <button type="submit" class="btn btn-primary">Impor</button>
        </form>
        <button id="export" class="mt-4 btn btn-primary">Export</button>
        <table class="table" id="dataTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Fakultas</th>
                    <th>Prodi</th>
                    <th>Nomer Telepohone</th>
                    <th>Jenis Kelamin</th>
                    <th>Alamat</th>
                    <th>Tanggal Lahir</th>
                    <!-- Tambahkan kolom lain sesuai kebutuhan -->
                </tr>
            </thead>
        </table>


    </div>


    <script>
        $('document').ready(function() {
            let mahasiswaTable = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('data') }}", // Ganti dengan route Anda
                method: 'GET',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'fakultas',
                        name: 'fakultas'
                    },
                    {
                        data: 'prodi',
                        name: 'prodi'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'gender',
                        name: 'gender'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    },
                    {
                        data: 'born',
                        name: 'born'
                    }
                    // Tambahkan kolom lain sesuai kebutuhan
                ]
            });

            $('#form-import').submit(function(e) {
                e.preventDefault();
                var formData = new FormData($(this)[0]);
                $.ajax({
                    type: "POST",
                    url: "{{ route('import') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        mahasiswaTable.draw();
                        $('#form-import')[0].reset();
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = JSON.parse(xhr.responseText);
                        console.log(errorMessage.error);
                        alert(errorMessage.error);
                    },
                });
            });

            $('#export').click(function(e) {
                e.preventDefault();
                $.ajax({
                    type: "GET",
                    url: "{{ route('export') }}",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        window.location.href = "{{ route('export') }}";
                    }
                });
            });

        });
    </script>

</body>

</html>
