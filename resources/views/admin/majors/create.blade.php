@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 fw-bold text-dark opacity-75">
                        <i class="fas fa-plus-circle text-primary me-2"></i>Tambah Departemen Baru
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Lengkapi informasi untuk menambahkan jurusan atau fakultas baru.</p>
                </div>
                <a href="{{ route('admin.majors.index') }}" class="btn btn-light btn-sm px-3 rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('admin.majors.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nama Departemen <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Contoh: Teknik Informatika" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Kode (Unique) <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control font-monospace @error('code') is-invalid @enderror" 
                                       placeholder="Contoh: TI" value="{{ old('code') }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="Berikan deskripsi singkat tentang departemen ini...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="reset" class="btn btn-light px-4">Reset</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm" style="background:#0ea5e9; border:none;">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Info Card -->
    <div class="col-md-4">
        <div class="card bg-white border-0 shadow-sm p-4 h-100">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                    <i class="fas fa-info-circle h4 mb-0"></i>
                </div>
                <h6 class="mb-0 fw-bold opacity-75">Tips Pengisian</h6>
            </div>
            
            <div class="vstack gap-4">
                <div class="d-flex gap-3">
                    <div class="dot mt-2 bg-primary" style="width:8px; height:8px; border-radius:50%; flex-shrink:0;"></div>
                    <div>
                        <p class="mb-1 fw-bold small">Nama Departemen</p>
                        <p class="text-muted small mb-0">Gunakan nama lengkap resmi dari fakultas atau jurusan.</p>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="dot mt-2 bg-success" style="width:8px; height:8px; border-radius:50%; flex-shrink:0;"></div>
                    <div>
                        <p class="mb-1 fw-bold small">Kode Jurusan</p>
                        <p class="text-muted small mb-0">Gunakan singkatan unik (2-10 karakter) untuk mempermudah identifikasi.</p>
                    </div>
                </div>
                <div class="d-flex gap-3">
                    <div class="dot mt-2 bg-info" style="width:8px; height:8px; border-radius:50%; flex-shrink:0;"></div>
                    <div>
                        <p class="mb-1 fw-bold small">Struktur Database</p>
                        <p class="text-muted small mb-0">Data ini akan menjadi induk dari Kelas dan Mata Pelajaran.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-auto pt-4 text-center opacity-50">
                <i class="fas fa-shield-alt fa-3x mb-3 text-light"></i>
                <p class="small mb-0">Data yang Anda masukkan aman & terenkripsi.</p>
            </div>
        </div>
    </div>
</div>
@endsection
