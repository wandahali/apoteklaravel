@extends('layouts.template')

 @section('content')
    <form action="{{ route('akun.store') }}" method="POST" class="card p-5">
        @csrf 
        {{-- token --}}
        @if(Session::get('success'))
            <div class="alert alert-success"> {{ Session::get('success') }}</div>
        @endif
            @if ($errors->any())
            <ul class="alert alert-danger">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            @endif

    <div class="mb-3 row">
        <label for="name" class="col-sm-2 col-form-label">Nama :</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="name" name="name">
        </div>
    </div>
    <div class="mb-3 row">
        <label for="email" class="col-sm-2 col-form-label">Email :</label>
        <div class="col-sm-10">
        <input type="email" class="form-control" id="name" name="email">
    </div>
    </div>
        <div class="mb-3 row">
            <label for="type" class="col-sm-2 col-form-label">Tipe pengguna :</label>
            <div class="col-sm-10">
            <select class="form-select" id="type" name="type">
                <option selected disabled hidden>Pilih</option>
                <option value="admin">admin</option>
                <option value="cashier">kasir</option>
            </select>
        </div>
    </div>
    <button type="submit" class="btn btn-primary mt-3">Simpan data</button>
</form>
@endsection

