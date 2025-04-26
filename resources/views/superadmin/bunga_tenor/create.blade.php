@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-plus-circle"></i> Tambah Bunga & Tenor</h4>
                </div>
                <div class="card-body">
                    <form id="form-bunga-tenor" action="{{ route('superadmin.bunga-tenor.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-calendar-day"></i> Tenor (Hari)</label>
                                    <input type="number" name="tenor" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-percent"></i> Bunga (%)</label>
                                    <input type="number" name="bunga_percent" class="form-control" step="0.01" required>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <a href="{{ route('superadmin.bunga-tenor.index') }}" class="btn btn-secondary d-none d-sm-inline-block btn-sm">
                                        <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                    <a href="{{ route('superadmin.bunga-tenor.index') }}" class="btn btn-secondary d-inline-block d-sm-none btn-block btn-sm">
                                        <i class="fas fa-arrow-left"></i>
                                    </a>
                                    <button type="button" id="btn-simpan" class="btn btn-success d-none d-sm-inline-block btn-sm">
                                        <i class="fas fa-save"></i> <span class="d-none d-md-inline">Simpan</span>
                                    </button>
                                    <button type="button" id="btn-simpan" class="btn btn-success d-inline-block d-sm-none btn-block btn-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.querySelectorAll('#btn-simpan').forEach(function(btn) {
                            btn.addEventListener('click', function () {
                                Swal.fire({
                                    title: 'Simpan Data?',
                                    text: "Pastikan data bunga dan tenor sudah benar.",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#1a8754',
                                    cancelButtonColor: '#aaa',
                                    confirmButtonText: 'Ya, Simpan',
                                    cancelButtonText: 'Batal'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('form-bunga-tenor').submit();
                                    }
                                });
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
