<!-- New Question Modal -->
<div class="modal fade" id="newQuestionModal" tabindex="-1" aria-labelledby="newQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            
            <div class="modal-header border-bottom px-4 py-3 bg-light rounded-top-4">
                <h5 class="modal-title fw-bold text-dark" id="newQuestionModalLabel"><i class="fas fa-plus-circle text-primary me-2"></i>New Question</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 py-4">
                
                <!-- Settings Row -->
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <label class="form-label fw-bold text-dark small text-uppercase">Question Type</label>
                        <!-- Functional Type Dropdown -->
                        <select id="questionTypeDropdown" class="form-select bg-light border-0 shadow-none">
                            <option value="single_choice">Single Choice (Radio)</option>
                            <option value="multiple_choice">Multiple Choice (Checkbox)</option>
                            <option value="true_false">True / False</option>
                            <option value="short_answer">Short Answer (Manual Grading)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-dark small text-uppercase">Points</label>
                        <input type="number" id="questionPoints" class="form-control bg-light border-0 shadow-none" value="1" min="1">
                    </div>
                </div>

                <!-- Question Text -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">Question Prompt</label>
                    <textarea id="questionEditor" class="form-control bg-light border-0 shadow-none p-3" rows="4" placeholder="Enter your question here..." required></textarea>
                </div>

                <!-- Options -->
                <div id="optionsSection">
                    <label class="form-label fw-bold text-dark small text-uppercase mb-3">Answer Options</label>
                    
                    <div id="optionsContainer">
                        <!-- Initial Option 1 -->
                        <div class="input-group mb-3 option-row shadow-sm rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="far fa-circle"></i></span>
                            <input type="text" class="form-control option-text border-0 shadow-none" placeholder="Enter option text...">
                            <div class="input-group-text bg-white border-0 border-start px-3">
                                <div class="form-check mb-0 d-flex align-items-center gap-2 cursor-pointer">
                                    <input class="form-check-input correct-checkbox mt-0" type="checkbox" style="cursor:pointer; width:18px;height:18px;">
                                    <label class="form-check-label small fw-bold text-dark mb-0" style="cursor:pointer; padding-top:2px;">Correct</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light bg-white border-0 border-start text-danger remove-option px-3"><i class="fas fa-times"></i></button>
                        </div>
                        
                        <!-- Initial Option 2 -->
                        <div class="input-group mb-3 option-row shadow-sm rounded-3 overflow-hidden border">
                            <span class="input-group-text bg-white border-0 text-muted"><i class="far fa-circle"></i></span>
                            <input type="text" class="form-control option-text border-0 shadow-none" placeholder="Enter option text...">
                            <div class="input-group-text bg-white border-0 border-start px-3">
                                <div class="form-check mb-0 d-flex align-items-center gap-2 cursor-pointer">
                                    <input class="form-check-input correct-checkbox mt-0" type="checkbox" style="cursor:pointer; width:18px;height:18px;">
                                    <label class="form-check-label small fw-bold text-dark mb-0" style="cursor:pointer; padding-top:2px;">Correct</label>
                                </div>
                            </div>
                            <button type="button" class="btn btn-light bg-white border-0 border-start text-danger remove-option px-3"><i class="fas fa-times"></i></button>
                        </div>
                    </div>

                    <button type="button" id="addOptionBtn" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-2">
                        <i class="fas fa-plus me-1"></i> Add Another Option
                    </button>
                </div>

            </div>
            
            <div class="modal-footer border-top bg-light px-4 py-3 rounded-bottom-4">
                <input type="hidden" id="quizId" value="{{ $quiz->id }}">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="saveAndAddBtn" class="btn btn-outline-primary rounded-pill px-4">Save & Add Another</button>
                <button type="button" id="saveQuestionBtn" class="btn btn-primary rounded-pill px-5 shadow-sm">Save Question</button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const optionsContainer = document.getElementById('optionsContainer');
    const addOptionBtn = document.getElementById('addOptionBtn');
    const saveQuestionBtn = document.getElementById('saveQuestionBtn');
    const saveAndAddBtn = document.getElementById('saveAndAddBtn');
    const questionEditor = document.getElementById('questionEditor');
    const typeDropdown = document.getElementById('questionTypeDropdown');
    const quizId = document.getElementById('quizId').value;

    function createOptionHtml() {
        const div = document.createElement('div');
        div.className = 'input-group mb-3 option-row shadow-sm rounded-3 overflow-hidden border';
        div.innerHTML = `
            <span class="input-group-text bg-white border-0 text-muted"><i class="far fa-circle"></i></span>
            <input type="text" class="form-control option-text border-0 shadow-none" placeholder="Enter option text...">
            <div class="input-group-text bg-white border-0 border-start px-3">
                <div class="form-check mb-0 d-flex align-items-center gap-2 cursor-pointer">
                    <input class="form-check-input correct-checkbox mt-0" type="checkbox" style="cursor:pointer; width:18px;height:18px;">
                    <label class="form-check-label small fw-bold text-dark mb-0" style="cursor:pointer; padding-top:2px;">Correct</label>
                </div>
            </div>
            <button type="button" class="btn btn-light bg-white border-0 border-start text-danger remove-option px-3"><i class="fas fa-times"></i></button>
        `;
        return div;
    }

    addOptionBtn.addEventListener('click', function() {
        optionsContainer.appendChild(createOptionHtml());
    });

    optionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            if (optionsContainer.querySelectorAll('.option-row').length > 2) {
                e.target.closest('.option-row').remove();
            } else {
                alert('A minimum of 2 options is required.');
            }
        }
    });

    function resetModal() {
        questionEditor.value = '';
        document.getElementById('questionPoints').value = 1;
        optionsContainer.innerHTML = '';
        optionsContainer.appendChild(createOptionHtml());
        optionsContainer.appendChild(createOptionHtml());
    }

    typeDropdown.addEventListener('change', function() {
        const isShortAnswer = this.value === 'short_answer';
        document.getElementById('optionsSection').style.display = isShortAnswer ? 'none' : 'block';
    });

    function saveQuestion(stayOpen = false) {
        const questionText = questionEditor.value.trim();
        const points = document.getElementById('questionPoints').value;
        const type = typeDropdown.value;

        const options = [];
        const correct = [];

        optionsContainer.querySelectorAll('.option-row').forEach((row) => {
            const text = row.querySelector('.option-text').value.trim();
            const isCorrect = row.querySelector('.correct-checkbox').checked;
            
            if (text) {
                options.push(text);
                if (isCorrect) {
                    correct.push(options.length - 1);
                }
            }
        });

        if (!questionText) { return alert('Please enter question text.'); }
        
        if (type !== 'short_answer') {
            if (options.length < 2) { return alert('Please add at least 2 options.'); }
            if (correct.length === 0) { return alert('Please mark at least one option as correct.'); }
        }

        const data = {
            quiz_id: quizId,
            content: questionText,
            points: points,
            type: type,
            options: options,
            correct: correct,
            _token: '{{ csrf_token() }}'
        };

        saveQuestionBtn.disabled = true;
        saveAndAddBtn.disabled = true;

        fetch('{{ route("questions.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': data._token
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                if (stayOpen) {
                    resetModal();
                    alert('Question added successfully!');
                } else {
                    window.location.reload();
                }
            } else {
                alert('Error: ' + res.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert('An error occurred while saving the question.');
        })
        .finally(() => {
            saveQuestionBtn.disabled = false;
            saveAndAddBtn.disabled = false;
        });
    }

    saveQuestionBtn.addEventListener('click', () => saveQuestion(false));
    saveAndAddBtn.addEventListener('click', () => saveQuestion(true));
});
</script>
