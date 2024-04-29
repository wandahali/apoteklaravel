@extends('layouts.template')

@section('content')

@if(Session::get('success'))
    <div class="alert alert-success"> {{ Session::get('success') }}</div>
    @endif
@if(Session::get('deleted'))
<div class="alert alert-warning"> {{ Session::get('deleted') }}</div>
    @endif

    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <a href="{{route('akun.create')}}" type= "submit" class= "btn " style="background-color:#7D7C7C; margin-left:955px; margin-bottom:10px; color:white; margin-top:30px">Tambah Pengguna</a>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach ($user as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['email'] }}</td>
                <td>{{ $item['role'] }}</td>
                <td class="d-flex justify-content-center">
                    <a href="{{route('akun.edit', $item['id'])}}" class="btn btn-primary me-3">Edit</a>
                    
                        <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal-{{ $item->id}}">
                            Hapus
                          </button>
                    </td>
                </tr>

                <!-- Button trigger modal -->
    
      
      <!-- Modal -->
      <div class="modal fade" id="exampleModal-{{ $item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <form action="{{ route('akun.delete', $item->id) }}" method="POST"> 
                  {{-- kasi identitas  --}}
                    @csrf
                    @method('DELETE')
                <button type="submit" class="btn btn-primary">Hapus</button>
            </form>
              <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
                @endforeach
            </tbody>
        </table>
    @endsection