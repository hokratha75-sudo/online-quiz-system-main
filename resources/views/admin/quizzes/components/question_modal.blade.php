<!-- Add Question Modal -->
<div class="modal fade" id="newQuestionModal" tabindex="-1" aria-labelledby="newQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-[28px] border-none shadow-2xl shadow-indigo-900/20 overflow-hidden">
            
            <!-- Header -->
            <div class="bg-indigo-600 px-6 py-4 text-white relative">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 blur-3xl rounded-full"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <h3 class="text-base font-bold tracking-tight" id="newQuestionModalLabel">Add New Question</h3>
                        <p class="text-[11px] font-medium text-indigo-200 mt-0.5">Fill in the details below, then click Save</p>
                    </div>
                    <button type="button" class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all flex items-center justify-center outline-none" data-bs-dismiss="modal">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">
                
                <!-- Type & Points -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Question Type</label>
                        <div class="relative">
                            <select id="questionTypeDropdown" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all appearance-none cursor-pointer">
                                <option value="single_choice">Single Choice — one correct answer</option>
                                <option value="multiple_choice">Multiple Choice — many correct answers</option>
                                <option value="true_false">True / False</option>
                                <option value="short_answer">Short Answer — graded manually by teacher</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Points</label>
                        <input type="number" id="questionPoints"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all text-center tabular-nums"
                               value="1" min="1">
                    </div>
                </div>

                <!-- Question Text -->
                <div>
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Question Text <span class="text-rose-500">*</span></label>
                    <textarea id="questionEditor"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-medium text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-400 min-h-[80px] resize-none"
                              placeholder="Type your question here..."></textarea>
                </div>

                <!-- Short Answer Info Note -->
                <div id="shortAnswerNote" class="hidden bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-start gap-3">
                    <i class="fas fa-pen-to-square text-blue-400 mt-0.5 text-sm shrink-0"></i>
                    <p class="text-xs font-medium text-blue-700 leading-relaxed">
                        Students will type their own answer. The teacher must review and assign points manually after submission.
                    </p>
                </div>

                <!-- Answer Options -->
                <div id="optionsSection">
                    <div class="flex items-center justify-between mb-3">
                        <label class="block text-xs font-semibold text-slate-500">Answer Options <span class="text-rose-500">*</span></label>
                        <button type="button" id="addOptionBtn" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors flex items-center gap-1">
                            <i class="fas fa-plus text-[10px]"></i> Add Option
                        </button>
                    </div>
                    
                    <div id="optionsContainer" class="space-y-2.5">
                        <!-- Option 1 -->
                        <div class="flex items-center gap-3 option-row group">
                            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-sm">
                                <div class="px-3 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                                <input type="text" class="w-full py-2.5 bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-300" placeholder="Option A...">
                                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" style="cursor:pointer;">
                                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-emerald-600 transition-all whitespace-nowrap">Correct</span>
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center remove-option shrink-0">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                        <!-- Option 2 -->
                        <div class="flex items-center gap-3 option-row group">
                            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-sm">
                                <div class="px-3 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                                <input type="text" class="w-full py-2.5 bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-300" placeholder="Option B...">
                                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" style="cursor:pointer;">
                                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-emerald-600 transition-all whitespace-nowrap">Correct</span>
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center remove-option shrink-0">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Save to Question Bank -->
                <div class="px-4 py-3 border border-slate-100 rounded-xl bg-slate-50/50">
                    <div class="flex items-center justify-between group cursor-pointer" onclick="document.getElementById('isReusableCheck').click()">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 shadow-sm flex items-center justify-center text-indigo-500">
                                <i class="fas fa-bookmark text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-semibold text-slate-800">Save to Question Bank</h4>
                                <p class="text-[10px] text-slate-400 mt-0.5">Reuse this question in other quizzes later</p>
                            </div>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="isReusableCheck" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- Footer Buttons -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center gap-2.5">
                <input type="hidden" id="quizId" value="{{ $quiz->id }}">
                <button type="button" class="w-full md:w-auto px-5 h-10 rounded-xl text-sm font-semibold text-slate-500 hover:text-slate-900 hover:bg-white transition-all" data-bs-dismiss="modal">Cancel</button>
                <div class="flex-grow"></div>
                <button type="button" id="saveAndAddBtn" class="w-full md:w-auto px-5 h-10 bg-white border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 rounded-xl text-sm font-semibold transition-all shadow-sm">
                    <i class="fas fa-plus text-xs mr-1.5"></i>Save &amp; Add Another
                </button>
                <button type="button" id="saveQuestionBtn" class="w-full md:w-auto px-6 h-10 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold transition-all shadow-md shadow-indigo-600/20">
                    <i class="fas fa-check text-xs mr-1.5"></i>Save Question
                </button>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const optionsContainer = document.getElementById('optionsContainer');
    const addOptionBtn     = document.getElementById('addOptionBtn');
    const saveQuestionBtn  = document.getElementById('saveQuestionBtn');
    const saveAndAddBtn    = document.getElementById('saveAndAddBtn');
    const questionEditor   = document.getElementById('questionEditor');
    const typeDropdown     = document.getElementById('questionTypeDropdown');
    const quizId           = document.getElementById('quizId').value;
    const optionsSection   = document.getElementById('optionsSection');
    const shortAnswerNote  = document.getElementById('shortAnswerNote');

    const letters = ['A','B','C','D','E','F','G','H'];

    function createOptionHtml(index) {
        const label = letters[index] || (index + 1);
        const div = document.createElement('div');
        div.className = 'flex items-center gap-3 option-row group transition-all duration-200';
        div.innerHTML = `
            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-sm">
                <div class="px-3 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                <input type="text" class="w-full py-2.5 bg-transparent text-sm font-medium text-slate-900 outline-none placeholder:text-slate-300" placeholder="Option ${label}...">
                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" style="cursor:pointer;">
                        <span class="text-[10px] font-semibold text-slate-400 group-hover:text-emerald-600 transition-all whitespace-nowrap">Correct</span>
                    </label>
                </div>
            </div>
            <button type="button" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center remove-option shrink-0">
                <i class="fas fa-times text-xs"></i>
            </button>
        `;
        return div;
    }

    addOptionBtn.addEventListener('click', function() {
        const count = optionsContainer.querySelectorAll('.option-row').length;
        optionsContainer.appendChild(createOptionHtml(count));
    });

    optionsContainer.addEventListener('click', function(e) {
        if (e.target.closest('.remove-option')) {
            if (optionsContainer.querySelectorAll('.option-row').length > 2) {
                e.target.closest('.option-row').remove();
            } else {
                alert('At least 2 answer options are required.');
            }
        }
    });

    typeDropdown.addEventListener('change', function() {
        const isShortAnswer = this.value === 'short_answer';
        optionsSection.style.display  = isShortAnswer ? 'none' : 'block';
        shortAnswerNote.classList.toggle('hidden', !isShortAnswer);

        // True/False: auto-fill 2 options
        if (this.value === 'true_false') {
            optionsContainer.innerHTML = '';
            ['True', 'False'].forEach((label, idx) => {
                const row = createOptionHtml(idx);
                row.querySelector('input[type="text"]').value = label;
                optionsContainer.appendChild(row);
            });
            addOptionBtn.style.display = 'none';
        } else {
            addOptionBtn.style.display = '';
        }
    });

    function resetModal() {
        questionEditor.value = '';
        document.getElementById('questionPoints').value = 1;
        document.getElementById('isReusableCheck').checked = false;
        typeDropdown.value = 'single_choice';
        optionsSection.style.display = 'block';
        shortAnswerNote.classList.add('hidden');
        addOptionBtn.style.display = '';
        optionsContainer.innerHTML = '';
        optionsContainer.appendChild(createOptionHtml(0));
        optionsContainer.appendChild(createOptionHtml(1));
    }

    // Reset modal on close
    document.getElementById('newQuestionModal').addEventListener('hidden.bs.modal', resetModal);

    function saveQuestion(stayOpen = false) {
        const questionText = questionEditor.value.trim();
        const points       = document.getElementById('questionPoints').value;
        const type         = typeDropdown.value;
        const isReusable   = document.getElementById('isReusableCheck').checked;

        if (!questionText) {
            return alert('Please enter the question text.');
        }

        const options = [];
        const correct = [];

        if (type !== 'short_answer') {
            optionsContainer.querySelectorAll('.option-row').forEach((row) => {
                const textInput = row.querySelector('input[type="text"]');
                const text      = textInput ? textInput.value.trim() : '';
                const checkbox  = row.querySelector('.correct-checkbox');
                const isCorrect = checkbox ? checkbox.checked : false;
                if (text) {
                    options.push(text);
                    if (isCorrect) correct.push(options.length - 1);
                }
            });

            if (options.length < 2) return alert('Please add at least 2 answer options.');
            if (correct.length === 0) return alert('Please mark at least one option as correct.');
        }

        const data = {
            quiz_id:      quizId,
            content:      questionText,
            points:       points,
            type:         type,
            is_reusable:  isReusable,
            options:      options,
            correct:      correct,
            _token:       '{{ csrf_token() }}'
        };

        saveQuestionBtn.disabled = true;
        saveAndAddBtn.disabled   = true;
        saveQuestionBtn.innerHTML = '<i class="fas fa-spinner fa-spin text-xs mr-1.5"></i>Saving...';

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
                } else {
                    window.location.reload();
                }
            } else {
                alert('Error: ' + res.message);
            }
        })
        .catch(() => alert('A network error occurred. Please try again.'))
        .finally(() => {
            saveQuestionBtn.disabled = false;
            saveAndAddBtn.disabled   = false;
            saveQuestionBtn.innerHTML = '<i class="fas fa-check text-xs mr-1.5"></i>Save Question';
        });
    }

    saveQuestionBtn.addEventListener('click', () => saveQuestion(false));
    saveAndAddBtn.addEventListener('click',   () => saveQuestion(true));
});
</script>
