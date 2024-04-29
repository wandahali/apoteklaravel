@extends('layouts.template')

@section('content')
    <div class="msg-success"></div>
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Stock</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($medicines as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td style="{{ $item['stock'] <= 3 ? 'background: red; color: white' : 'background: none; color: black' }}">{{ $item['stock'] }}</td>
                <td class="d-flex justify-content-center">
                    <div onclick="edit('{{$item['id']}}')" class="btn btn-primary me-3" style="cursor: pointer">Tambah stock</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal" tabindex="-1" id="edit-stock">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" id="form-stock">
                    <div class="modal-body">
                        <div class="msg"></div>

                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama obat</label>
                            <input type="text" class="form-control" id="name" name="name" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="stock" class="form-label">Stock obat:</label>
                            <input type="number" class="form-control" id="stock" name="stock">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script') <!-- Corrected the spelling here -->
    <script type="text/javascript">
        // Ensure that CSRF token is included in all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Fixed a typo here
            }
        });

        function edit(id) {
            var url = "{{ route('medicine.stock.edit', ":id") }}"; // Fixed a typo here 
            url = url.replace(':id', id); // Fixed the typo and added missing single quotes
            $.ajax({ // 
                type: "GET",
                url: url, //roote mana yng mau di akses
                dataType: 'json',
                success: function (res) {
                    $('#edit-stock').modal('show'); //
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#stock').val(res.stock); //
                }
            });
        }

        $('#form-stock').submit(function (e) { //
            e.preventDefault(); //

            var id = $('#id').val(); //nge get id 
            var urlForm = "{{ route('medicine.stock.update', ":id") }}"; // diroote ke id
            urlForm = urlForm.replace(':id', id); // Fixed the typo and added missing single quotes

            var data = {
                stock: $('#stock').val(), //
            };

            $.ajax({ 
                type: 'PATCH',
                url: urlForm,
                data: data, 
                cache: false,
                success: function (data) {
                    $("#edit-stock").modal('hide');
                    sessionStorage.reloadAfterPageLoad = true; // akan kadaluarsa ketika browse nya di tutup
                    window.location.reload(); 
                },
                error: function (data) {
                    $('#msg').attr("class", "alert alert-danger");
                    $('#msg').text(data.responseJSON.message);
                }
            });
        });

        $(function () {
            if (sessionStorage.reloadAfterPageLoad) { // Fixed a typo
                $('#msg-success').attr("class", "alert alert-success");
                $('#msg-success').text("Berhasil menambahkan data stock");
                sessionStorage.clear(); // Fixed a typo
            }
        });
    </script>
@endpush
{{-- json bentuk nya array --}}