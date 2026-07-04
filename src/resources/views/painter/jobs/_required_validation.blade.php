{{-- 送信時の必須フィールド検証
     対象: フォーム内の required 属性が付いた input / select / textarea
     未入力で送信しようとした場合、ページ遷移なし(JS)でその場で赤文字エラーを表示する。 --}}
<script>
(function(){
    const form = document.querySelector('form[action*="/painter/jobs"]');
    if (!form) return;

    function ensureErrorEl(field) {
        // 既に挿入済みのエラー要素があれば再利用
        let next = field.parentElement?.querySelector('[data-required-error]');
        if (next) return next;
        const p = document.createElement('p');
        p.dataset.requiredError = 'true';
        p.className = 'text-xs text-error-600 mt-2 hidden';
        // 既存のサーバ側エラー出力の直後 or input の直後に置く
        field.insertAdjacentElement('afterend', p);
        return p;
    }

    function labelOf(field) {
        const id = field.getAttribute('id');
        if (id) {
            const lbl = form.querySelector('label[for="' + CSS.escape(id) + '"]');
            if (lbl) {
                // "*" などの装飾を取り除いてラベルテキストだけ抽出
                return (lbl.textContent || '').replace(/[*＊\s]+$/, '').trim();
            }
        }
        return field.getAttribute('name') || '項目';
    }

    function clearError(field) {
        const el = ensureErrorEl(field);
        el.classList.add('hidden');
        field.classList.remove('border-error-500', 'ring-1', 'ring-error-500');
    }

    function showError(field, message) {
        const el = ensureErrorEl(field);
        el.textContent = message;
        el.classList.remove('hidden');
        field.classList.add('border-error-500');
    }

    function isEmpty(field) {
        if (field.type === 'radio') {
            // 同名 radio がチェックされているか
            const name = field.getAttribute('name');
            return !form.querySelector('input[type="radio"][name="' + CSS.escape(name) + '"]:checked');
        }
        if (field.type === 'checkbox') {
            return !field.checked;
        }
        return field.value === '' || field.value === null;
    }

    function validateField(field) {
        if (!field.hasAttribute('required')) return true;
        if (isEmpty(field)) {
            showError(field, labelOf(field) + ' を入力してください。');
            return false;
        }
        clearError(field);
        return true;
    }

    // 各 required フィールドに blur/change イベントで個別チェック
    form.querySelectorAll('[required]').forEach(field => {
        const evt = (field.tagName === 'SELECT' || field.type === 'radio' || field.type === 'checkbox' || field.type === 'date')
            ? 'change' : 'blur';
        field.addEventListener(evt, () => validateField(field));
        field.addEventListener('input', () => {
            // 入力中はエラー解除のみ（赤を残さない）
            if (!isEmpty(field)) clearError(field);
        });
    });

    form.addEventListener('submit', function(e){
        let firstInvalid = null;
        const seenRadioGroups = new Set();

        form.querySelectorAll('[required]').forEach(field => {
            // radio はグループ単位で1回だけ判定
            if (field.type === 'radio') {
                const name = field.getAttribute('name');
                if (seenRadioGroups.has(name)) return;
                seenRadioGroups.add(name);
            }
            if (!validateField(field)) {
                if (!firstInvalid) firstInvalid = field;
            }
        });

        if (firstInvalid) {
            e.preventDefault();
            firstInvalid.scrollIntoView({behavior: 'smooth', block: 'center'});
            try { firstInvalid.focus({preventScroll: true}); } catch (_) {}
        }
    });
})();
</script>
