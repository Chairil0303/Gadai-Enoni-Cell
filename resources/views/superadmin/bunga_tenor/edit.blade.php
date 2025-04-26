@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-edit"></i> Edit Bunga & Tenor</h4>
                </div>
                <div class="card-body">
                    <form id="form-bunga-tenor" action="{{ route('superadmin.bunga-tenor.update', $bungaTenor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="">
                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-calendar-day"></i> Tenor (Hari)</label>
                                    <input type="number" name="tenor" class="form-control" value="{{ old('tenor', $bungaTenor->tenor) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label"><i class="fas fa-percent"></i> Bunga (%)</label>
                                    <input type="number" name="bunga_percent" class="form-control" step="0.01" value="{{ old('bunga_percent', $bungaTenor->bunga_percent) }}" required>
                                </div>

                                <div class="d-flex justify-content-between mb-3">
                                    <a href="{{ route('superadmin.bunga-tenor.index') }}" class="btn btn-secondary d-none d-sm-inline-block btn-sm">
                                        <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline">Kembali</span>
                                    </a>
                                    <a href="{{ route('superadmin.bunga-tenor.index') }}" class="btn btn-secondary d-inline-block d-sm-none btn-block btn-sm">
                                        <i class="fas fa-arrow-left"></i>
                                    </a>
                                    <button type="button" id="btn-update" class="btn btn-success d-none d-sm-inline-block btn-sm">
                                        <i class="fas fa-save"></i> <span class="d-none d-md-inline">Update</span>
                                    </button>
                                    <button type="button" id="btn-update" class="btn btn-warning d-inline-block d-sm-none btn-block btn-sm">
                                        <i class="fas fa-save"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <script>
                        document.querySelectorAll('#btn-update').forEach(function(btn) {
                            btn.addEventListener('click', function () {
                                Swal.fire({
                                    title: 'Update Data?',
                                    text: "Pastikan data bunga dan tenor sudah benar.",
                                    icon: 'question',
                                    showCancelButton: true,
                                    confirmButtonColor: '#ffb74d',
                                    cancelButtonColor: '#aaa',
                                    confirmButtonText: 'Ya, Update',
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
