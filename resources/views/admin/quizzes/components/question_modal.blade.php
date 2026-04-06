<!-- New Question Modal -->
<div class="modal fade" id="newQuestionModal" tabindex="-1" aria-labelledby="newQuestionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-[32px] border-none shadow-2xl shadow-indigo-900/20 overflow-hidden">
            
            <div class="bg-indigo-600 px-5 py-2.5 text-white relative">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 blur-3xl rounded-full"></div>
                <div class="flex items-center justify-between relative z-10">
                    <div>
                        <h3 class="text-lg font-bold uppercase tracking-tight leading-tight" id="newQuestionModalLabel">Add New Question</h3>
                        <p class="text-[9px] font-medium text-indigo-100 uppercase tracking-widest opacity-80">Knowledge entity creation protocol</p>
                    </div>
                    <button type="button" class="w-7 h-7 rounded-xl bg-white/10 hover:bg-white/20 text-white transition-all flex items-center justify-center outline-none" data-bs-dismiss="modal">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="px-5 py-3 space-y-2.5">
                
                <!-- Settings Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1 ml-1">Question Type</label>
                        <select id="questionTypeDropdown" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all">
                            <option value="single_choice">Single Choice (Radio)</option>
                            <option value="multiple_choice">Multiple Choice (Checkbox)</option>
                            <option value="true_false">True / False</option>
                            <option value="short_answer">Short Answer (Manual Grading)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1 ml-1">Points</label>
                        <input type="number" id="questionPoints" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all tabular-nums text-center" value="1" min="1">
                    </div>
                </div>

                <!-- Question Text -->
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1 ml-1">Question Prompt</label>
                    <textarea id="questionEditor" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-900 outline-none focus:border-indigo-500 focus:bg-white transition-all placeholder:text-slate-300 min-h-[70px]" placeholder="Enter your question text here..." required></textarea>
                </div>

                <div class="px-4 py-2.5 border border-slate-100 rounded-xl bg-slate-50/50">
                    <div class="flex items-center justify-between group cursor-pointer" onclick="document.getElementById('isReusableCheck').click()">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 shadow-sm flex items-center justify-center text-indigo-600 group-hover:text-indigo-500 transition-colors">
                                <i class="fas fa-database text-xs"></i>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-tight">Save to Global Bank</h4>
                                <p class="text-[10px] font-bold text-slate-500 uppercase mt-0.5 tracking-tight">Make this question available for other quizzes.</p>
                            </div>
                        </div>
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="isReusableCheck" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        </div>
                    </div>
                </div>

                <!-- Options -->
                <div id="optionsSection" class="pt-2">
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 ml-1">Answer Options</label>
                        <button type="button" id="addOptionBtn" class="text-xs font-bold uppercase tracking-widest text-indigo-600 hover:text-indigo-800 transition-colors">
                            <i class="fas fa-plus mr-1"></i> Add Option
                        </button>
                    </div>
                    
                    <div id="optionsContainer" class="space-y-3">
                        <!-- Options will be dynamic -->
                        <div class="flex items-center gap-3 option-row group">
                            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-sm">
                                <div class="px-4 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                                <input type="text" class="w-full py-2.5 bg-transparent text-sm font-bold text-slate-900 outline-none placeholder:text-slate-300" placeholder="Option choice #1...">
                                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" style="cursor:pointer;">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-indigo-600 transition-all">Correct</span>
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="w-10 h-10 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center remove-option flex-shrink-0">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>

                        <div class="flex items-center gap-3 option-row group">
                            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-sm">
                                <div class="px-4 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                                <input type="text" class="w-full py-2.5 bg-transparent text-sm font-bold text-slate-900 outline-none placeholder:text-slate-300" placeholder="Option choice #2...">
                                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" style="cursor:pointer;">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-indigo-600 transition-all">Correct</span>
                                    </label>
                                </div>
                            </div>
                            <button type="button" class="w-10 h-10 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center remove-option flex-shrink-0">
                                <i class="fas fa-times text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col md:flex-row items-center gap-3">
                <input type="hidden" id="quizId" value="{{ $quiz->id }}">
                <button type="button" class="w-full md:w-auto px-5 h-9 rounded-xl font-bold uppercase tracking-widest text-[10px] text-slate-500 hover:text-slate-900 hover:bg-white transition-all" data-bs-dismiss="modal">Cancel</button>
                <div class="flex-grow"></div>
                <button type="button" id="saveAndAddBtn" class="w-full md:w-auto px-5 h-9 bg-white border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 rounded-xl font-bold uppercase tracking-widest text-[10px] transition-all shadow-sm">Save & Add Another</button>
                <button type="button" id="saveQuestionBtn" class="w-full md:w-auto px-6 h-9 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold uppercase tracking-widest text-[10px] transition-all shadow-md shadow-indigo-600/20">Save</button>
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
        div.className = 'flex items-center gap-3 option-row group transition-all duration-300';
        div.innerHTML = `
            <div class="flex-grow flex items-center bg-slate-50 border border-slate-200 rounded-xl overflow-hidden focus-within:border-indigo-500 focus-within:bg-white transition-all shadow-sm">
                <div class="px-4 text-slate-300"><i class="far fa-circle text-[10px]"></i></div>
                <input type="text" class="w-full py-2.5 bg-transparent text-sm font-bold text-slate-900 outline-none placeholder:text-slate-300" placeholder="New option choice...">
                <div class="px-4 border-l border-slate-200 bg-slate-50/50">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input class="correct-checkbox w-4 h-4 text-indigo-600 bg-white border-slate-300 rounded focus:ring-indigo-500" type="checkbox" style="cursor:pointer;">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest group-hover:text-indigo-600 transition-all">Correct</span>
                    </label>
                </div>
            </div>
            <button type="button" class="w-10 h-10 rounded-xl border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all flex items-center justify-center remove-option flex-shrink-0">
                <i class="fas fa-times text-xs"></i>
            </button>
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
        document.getElementById('isReusableCheck').checked = false;
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
        const isReusable = document.getElementById('isReusableCheck').checked;

        const options = [];
        const correct = [];

        optionsContainer.querySelectorAll('.option-row').forEach((row) => {
            const textInput = row.querySelector('input[type="text"]');
            const text = textInput ? textInput.value.trim() : '';
            const checkbox = row.querySelector('.correct-checkbox');
            const isCorrect = checkbox ? checkbox.checked : false;
            
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
            is_reusable: isReusable,
            options: options,
            correct: correct,
            _token: '{{ csrf_token() }}'
        };

        const btnSave = document.getElementById('saveQuestionBtn');
        const btnSaveAdd = document.getElementById('saveAndAddBtn');
        
        btnSave.disabled = true;
        btnSaveAdd.disabled = true;

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
            btnSave.disabled = false;
            btnSaveAdd.disabled = false;
        });
    }

    saveQuestionBtn.addEventListener('click', () => saveQuestion(false));
    saveAndAddBtn.addEventListener('click', () => saveQuestion(true));
});
</script>
