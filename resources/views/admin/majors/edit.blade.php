@extends('layouts.admin')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="mb-0 fw-bold text-dark opacity-75">
                        <i class="fas fa-pencil-alt text-primary me-2"></i>Edit Departemen
                    </h5>
                    <p class="text-muted small mb-0 mt-1">Ubah atau lengkapi informasi departemen ini.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.majors.index') }}" class="btn btn-light btn-sm px-3 rounded-pill shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="{{ route('admin.majors.update', $major->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Nama Departemen <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       placeholder="Contoh: Teknik Informatika" value="{{ old('name', $major->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Kode (Unique) <span class="text-danger">*</span></label>
                                <input type="text" name="code" class="form-control font-monospace @error('code') is-invalid @enderror" 
                                       placeholder="Contoh: TI" value="{{ old('code', $major->code) }}" required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small text-uppercase">Deskripsi</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="4" placeholder="Berikan deskripsi singkat tentang departemen ini...">{{ old('description', $major->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-2">
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
        <div class="row g-4">
            <div class="col-12">
                <div class="card bg-white border-0 shadow-sm p-4 h-100">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                            <i class="fas fa-layer-group h4 mb-0"></i>
                        </div>
                        <h6 class="mb-0 fw-bold opacity-75">Info Relasi</h6>
                    </div>
                    
                    <div class="vstack gap-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 fw-bold small">Total Kelas</p>
                                <p class="text-muted small mb-0">Terhubung langsung.</p>
                            </div>
                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $major->classes_count }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 fw-bold small">Mata Pelajaran</p>
                                <p class="text-muted small mb-0">Terkait jurusan.</p>
                            </div>
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $major->subjects_count }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto pt-4 text-center opacity-50">
                        <i class="fas fa-shield-alt fa-3x mb-3 text-light"></i>
                        <p class="small mb-0">Data ini memiliki dampak pada entitas lain.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
