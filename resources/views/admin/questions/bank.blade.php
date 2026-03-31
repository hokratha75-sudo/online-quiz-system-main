@extends('layouts.admin')

@section('content')
<div class="row items-center mb-4">
    <div class="col">
        <h2 class="fw-bold mb-1"><i class="fas fa-database text-primary me-2"></i> Global Question Bank</h2>
        <p class="text-muted small mb-0">Manage reusable questions across all quizzes.</p>
    </div>
</div>

<div class="card card-custom border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>Question Content</th>
                        <th>Origin Quiz</th>
                        <th>Type</th>
                        <th>Points</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($questions as $question)
                        <tr>
                            <td class="text-muted">{{ $question->id }}</td>
                            <td class="fw-medium">
                                <div class="text-truncate" style="max-width: 400px;">
                                    {!! strip_tags($question->content) !!}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">
                                    {{ $question->quiz->title ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                </span>
                            </td>
                            <td>{{ $question->points }} pts</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-light border text-primary" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light border text-danger" title="Remove from Bank">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted opacity-25 mb-3"></i>
                                <p class="text-muted">No reusable questions found in the bank.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
