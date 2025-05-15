@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Template WhatsApp</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.whatsapp_template.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Tipe Template</label>
            <input type="text" name="type" class="form-control" value="{{ $template->type }}" readonly>
        </div>

        <div class="form-group mb-3">
            <label>Template Pesan ({{ ucfirst($template->type) }})</label>
            <textarea name="message" class="form-control" rows="8">{{ $template->message }}</textarea>
            <small class="text-muted">Gunakan placeholder: <code>{{ '{no_bon}, {nama_barang}, {nama}, {jumlah}, {tanggal}, {nama_cabang}' }}</code></small>
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.whatsapp_template.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
