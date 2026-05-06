/**
 * お気に入りボタンのAJAX化
 *
 * 対応フォーマット:
 *   1. <x-favorite-button> コンポーネント（推奨）
 *      <button data-favorite-toggle data-target-type="model|job" data-target-id="N" aria-pressed="false">
 *
 *   2. 既存の <form class="js-ajax-favorite-home"> もサポート（後方互換）
 */

(function () {
    'use strict';

    // CSRFトークン取得
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // 統一トグルエンドポイントへリクエスト
    async function toggleFavorite(targetType, targetId) {
        const formData = new FormData();
        formData.append('target_type', targetType);
        formData.append('target_id', targetId);
        formData.append('_token', getCsrfToken());

        const response = await fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
            },
            credentials: 'same-origin',
            body: formData,
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        return response.json();
    }

    // ボタンの見た目を切り替え
    function setButtonState(button, favorited) {
        const labelOn = button.dataset.labelOn || 'お気に入りから外す';
        const labelOff = button.dataset.labelOff || 'お気に入りに追加';

        button.setAttribute('aria-pressed', favorited ? 'true' : 'false');
        button.setAttribute('title', favorited ? labelOn : labelOff);

        // icon variant のクラス切り替え
        if (button.classList.contains('fav-btn') || button.classList.contains('fav-btn-active')) {
            button.classList.toggle('fav-btn', !favorited);
            button.classList.toggle('fav-btn-active', favorited);
        } else {
            // large variant のクラス切り替え（黒/白の反転）
            const onClasses = ['bg-secondary-900', 'text-canvas-50', 'border-secondary-900', 'hover:bg-canvas-50', 'hover:text-secondary-900'];
            const offClasses = ['bg-canvas-50', 'text-secondary-900', 'border-secondary-900', 'hover:bg-secondary-900', 'hover:text-canvas-50'];
            const apply = favorited ? onClasses : offClasses;
            const remove = favorited ? offClasses : onClasses;
            apply.forEach(c => button.classList.add(c));
            remove.forEach(c => button.classList.remove(c));
        }

        // アイコン切り替え
        const offIcon = button.querySelector('[data-favorite-icon="off"]');
        const onIcon = button.querySelector('[data-favorite-icon="on"]');
        if (offIcon && onIcon) {
            offIcon.classList.toggle('hidden', favorited);
            onIcon.classList.toggle('hidden', !favorited);
        }

        // ラベル切り替え（large variant）
        const labelEl = button.querySelector('[data-favorite-label]');
        if (labelEl) {
            labelEl.textContent = favorited ? labelOn : labelOff;
        }
    }

    // クリックイベントの処理（イベント委任 - キャプチャフェーズで先取り）
    const clickHandler = async function (event) {
        const button = event.target.closest('[data-favorite-toggle]');
        if (!button) return;

        // 親要素の <a> や form がクリックを奪わないよう確実にブロック
        event.preventDefault();
        event.stopPropagation();
        if (event.stopImmediatePropagation) event.stopImmediatePropagation();

        if (button.disabled) return;
        button.disabled = true;
        button.style.opacity = '0.5';

        const targetType = button.dataset.targetType;
        const targetId = button.dataset.targetId;

        if (!targetType || !targetId) {
            console.warn('Favorite button missing data-target-type or data-target-id');
            button.disabled = false;
            button.style.opacity = '';
            return;
        }

        try {
            const result = await toggleFavorite(targetType, targetId);
            if (result.success) {
                setButtonState(button, result.favorited);

                // カスタムイベント発火（カウンタ等の連動更新に使える）
                button.dispatchEvent(new CustomEvent('favorite:changed', {
                    bubbles: true,
                    detail: { favorited: result.favorited, count: result.count, type: targetType, id: targetId },
                }));
            }
        } catch (err) {
            console.error('Favorite toggle failed:', err);
            // 失敗時はサイレントに（必要ならtoast通知を追加可能）
        } finally {
            button.disabled = false;
            button.style.opacity = '';
        }
    };

    // キャプチャフェーズで登録 → 親要素の <a> やフォームより先にハンドリング
    document.addEventListener('click', clickHandler, true);

    // ─────────────────────────────────────────
    // 後方互換: 既存の <form class="js-ajax-favorite-home"> を AJAX 化
    // ─────────────────────────────────────────
    document.addEventListener('submit', async function (event) {
        const form = event.target;
        if (!form.matches('form.js-ajax-favorite-home')) return;

        event.preventDefault();

        const button = form.querySelector('button[type="submit"]');
        if (!button || button.disabled) return;

        button.disabled = true;
        button.style.opacity = '0.5';

        try {
            const formData = new FormData(form);
            const isDelete = (formData.get('_method') || '').toUpperCase() === 'DELETE';

            const response = await fetch(form.action, {
                method: isDelete ? 'DELETE' : 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
                credentials: 'same-origin',
                body: isDelete ? null : formData,
            });

            if (response.ok) {
                // form を「逆向き」に書き換える簡易処理
                // action URL の destroy <-> store と method を入れ替え、ボタンの見た目を切り替える
                const csrfInput = form.querySelector('input[name="_token"]');
                const csrf = csrfInput ? csrfInput.value : getCsrfToken();

                if (isDelete) {
                    // 解除 → 追加に切り替え
                    const newAction = form.action.replace('/favorites/', '/favorites/').replace('destroy', 'store');
                    // action URL の差し替えは route 構造によるためスキップして UI のみ切り替え
                    setButtonState(button, false);
                    // method override 削除
                    const m = form.querySelector('input[name="_method"]');
                    if (m) m.remove();
                } else {
                    setButtonState(button, true);
                    // method override 追加
                    if (!form.querySelector('input[name="_method"]')) {
                        const m = document.createElement('input');
                        m.type = 'hidden';
                        m.name = '_method';
                        m.value = 'DELETE';
                        form.appendChild(m);
                    }
                }
            }
        } catch (err) {
            console.error('Favorite (legacy form) failed:', err);
        } finally {
            button.disabled = false;
            button.style.opacity = '';
        }
    });
})();
