<!-- AI Question Generator Modal -->
<div class="modal fade" id="aiQuestionModal" tabindex="-1" aria-labelledby="aiQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header d-flex align-items-center bg-light border-bottom border-warning pb-3" style="border-bottom-width: 3px !important;">
                <h5 class="modal-title fw-bold text-dark d-flex align-items-center" id="aiQuestionModalLabel">
                    <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-robot"></i>
                    </div>
                    Generate Questions with AI
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('quizzes.ai.generate', $quiz->id) }}" method="POST" enctype="multipart/form-data" id="aiGenerateForm">
                @csrf
                <div class="modal-body p-4 bg-white">
                    <p class="text-muted small mb-4">Upload a document or paste study material, and our AI will automatically generate multiple-choice questions for you.</p>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">Number of Questions to Generate</label>
                        <input type="number" name="question_count" class="form-control form-control-lg bg-light w-25" value="5" min="1" max="20" required>
                        <div class="form-text text-muted mt-1">Maximum 20 questions per request.</div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted text-uppercase mb-2">
                                <i class="fas fa-file-pdf text-danger me-1"></i> Upload Document
                            </label>
                            <div class="p-4 border rounded-3 bg-light text-center h-100" style="border-style: dashed !important; border-width: 2px !important; border-color: #dee2e6 !important;">
                                <input class="form-control" type="file" id="material_file" name="material_file" accept=".pdf,.txt">
                                <span class="d-block mt-2 small text-muted">Supported formats: PDF, TXT</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <span class="badge bg-secondary rounded-pill px-3 py-2 text-uppercase fw-bold shadow-sm">OR</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-2">
                            <i class="fas fa-paste text-primary me-1"></i> Paste Material Text
                        </label>
                        <textarea class="form-control bg-light border-0 px-3 py-2" name="material_text" rows="6" placeholder="Paste the content of your study material here..."></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light px-4 py-3 border-top-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning px-4 py-2 text-dark fw-bold d-flex align-items-center" id="generateBtn">
                        <i class="fas fa-magic me-2"></i> 
                        <span id="generateBtnText">Generate Magic</span>
                        <span class="spinner-border spinner-border-sm ms-2 d-none" id="generateSpinner" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const aiForm = document.getElementById('aiGenerateForm');
    if (aiForm) {
        aiForm.addEventListener('submit', function(e) {
            // Check if file or text is present
            const fileInput = document.getElementById('material_file');
            const file = fileInput.files[0];
            const textInput = document.querySelector('textarea[name="material_text"]').value.trim();

            if (!file && !textInput) {
                e.preventDefault();
                alert('Please upload a document or paste material text.');
                return false;
            }

            const btn = document.getElementById('generateBtn');
            const btnText = document.getElementById('generateBtnText');
            const spinner = document.getElementById('generateSpinner');
            
            btn.classList.add('disabled');
            spinner.classList.remove('d-none');
            btnText.innerHTML = 'Generating... (This may take up to a minute)';
        });
    }
});
</script>
