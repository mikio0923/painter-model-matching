{{-- 撮影日 >= 応募締切 のクライアントバリデーション
     対象: フォーム内の id=scheduled_date / id=apply_deadline --}}
<script>
(function(){
    const scheduledEl = document.getElementById('scheduled_date');
    const deadlineEl  = document.getElementById('apply_deadline');
    if (!scheduledEl || !deadlineEl) return;

    const form = scheduledEl.closest('form');
    const submitBtns = form ? form.querySelectorAll('button[type="submit"]') : [];

    let errorEl = document.createElement('p');
    errorEl.className = 'text-xs text-error-600 mt-2';
    errorEl.textContent = '撮影日は応募締切以降の日付を選択してください。';
    errorEl.hidden = true;
    scheduledEl.insertAdjacentElement('afterend', errorEl);

    function setSubmitDisabled(disabled){
        submitBtns.forEach(btn => {
            btn.disabled = disabled;
            btn.classList.toggle('opacity-50', disabled);
            btn.classList.toggle('cursor-not-allowed', disabled);
        });
    }

    function validate(){
        const sched = scheduledEl.value;
        const dl    = deadlineEl.value;

        if (!sched || !dl) {
            errorEl.hidden = true;
            setSubmitDisabled(false);
            return true;
        }
        if (sched < dl) {
            errorEl.hidden = false;
            setSubmitDisabled(true);
            return false;
        }
        errorEl.hidden = true;
        setSubmitDisabled(false);
        return true;
    }

    function syncMin(){
        scheduledEl.min = deadlineEl.value || '';
        validate();
    }

    deadlineEl.addEventListener('change', syncMin);
    scheduledEl.addEventListener('change', validate);
    if (form) form.addEventListener('submit', function(e){
        if (!validate()) {
            e.preventDefault();
            scheduledEl.focus();
        }
    });

    // 初期表示
    syncMin();
})();
</script>
