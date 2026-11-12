/**
 * Kolom sandi standar (SPBE A.1/A.3) — port logika spora-fe/src/lib/password.ts.
 * Generator memakai crypto.getRandomValues (CSPRNG, bukan Math.random),
 * 14 karakter, dijamin mengandung huruf kecil/kapital/angka/simbol,
 * tanpa karakter ambigu (0 O 1 l I |).
 * Terpasang otomatis pada input dengan class "pw-field-input".
 *
 * FILE STANDAR 4 APLIKASI (sisnas / sihijap / sijagung / spora) — identik.
 * Jangan edit di satu repo saja; samakan ke keempatnya.
 */
(function () {
    'use strict';

    var SET_LOWER = 'abcdefghjkmnpqrstuvwxyz';
    var SET_UPPER = 'ABCDEFGHJKMNPQRSTUVWXYZ';

    // Ikon standar inline SVG (tidak tergantung FontAwesome versi app).
    var SVG = function (inner) {
        return '<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' + inner + '</svg>';
    };
    var ICON_EYE = SVG('<path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/>');
    var ICON_EYE_OFF = SVG('<path d="M17.94 17.94A10.5 10.5 0 0 1 12 19c-7 0-11-7-11-7a19 19 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.9 9.9 0 0 1 12 4c7 0 11 7 11 7a19 19 0 0 1-3.16 4.19"/><path d="M14.12 14.12a3 3 0 1 1-4.24-4.24"/><line x1="2" y1="2" x2="22" y2="22"/>');
    var ICON_REFRESH = SVG('<polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>');
    var ICON_COPY = SVG('<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>');
    var ICON_CHECK = SVG('<polyline points="20 6 9 17 4 12"/>');
    var SET_DIGIT = '23456789';
    var SET_SYMBOL = '!@#$%^&*-_=+';

    function randInt(maxExclusive) {
        var range = 4294967296;
        var limit = range - (range % maxExclusive);
        var buf = new Uint32Array(1);
        var x;
        do {
            crypto.getRandomValues(buf);
            x = buf[0];
        } while (x >= limit);
        return x % maxExclusive;
    }

    /** Sandi acak lolos A.3 (≥1 tiap kelas), Fisher–Yates dengan CSPRNG. */
    function generatePassword(length) {
        var len = Math.max(8, Math.min(72, length || 14));
        var sets = [SET_LOWER, SET_UPPER, SET_DIGIT, SET_SYMBOL];
        var chars = sets.map(function (s) { return s[randInt(s.length)]; });
        var pool = sets.join('');
        for (var i = chars.length; i < len; i++) chars.push(pool[randInt(pool.length)]);
        for (var j = chars.length - 1; j > 0; j--) {
            var k = randInt(j + 1);
            var t = chars[j]; chars[j] = chars[k]; chars[k] = t;
        }
        return chars.join('');
    }

    /** Salin teks ke clipboard; fallback execCommand untuk konteks lama. */
    function copyText(text) {
        if (navigator.clipboard && navigator.clipboard.writeText) {
            return navigator.clipboard.writeText(text).then(function () { return true; }, function () {
                return copyFallback(text);
            });
        }
        return copyFallback(text);
    }

    function copyFallback(text) {
        var ta = document.createElement('textarea');
        ta.value = text;
        ta.style.position = 'fixed';
        ta.style.opacity = '0';
        document.body.appendChild(ta);
        ta.select();
        var ok = false;
        try { ok = document.execCommand('copy'); } catch (e) { ok = false; }
        ta.remove();
        return ok;
    }

    /** 5 kriteria A.3 (SPBE) — label sama dengan checklist FE 2027. */
    var KRITERIA = [
        { key: 'len', label: 'Minimal 8 karakter', test: function (pw) { return pw.length >= 8; } },
        { key: 'lower', label: 'Huruf kecil', test: function (pw) { return /[a-z]/.test(pw); } },
        { key: 'upper', label: 'Huruf kapital', test: function (pw) { return /[A-Z]/.test(pw); } },
        { key: 'digit', label: 'Angka', test: function (pw) { return /[0-9]/.test(pw); } },
        { key: 'symbol', label: 'Simbol', test: function (pw) { return /[^a-zA-Z0-9]/.test(pw); } }
    ];

    /** Status 5 kriteria (array true/false) — dipakai meter & checklist. */
    function kriteria(pw) {
        return KRITERIA.map(function (k) { return k.test(pw); });
    }

    function buildMeter(input) {
        var wrap = document.createElement('div');
        wrap.className = 'pw-meter';
        var hint = input.getAttribute('data-pw-hint')
            || 'Minimal level Kuat: 8 karakter dengan huruf kecil, huruf kapital, dan angka. Simbol membuat sandi Sangat kuat.';
        wrap.innerHTML =
            '<div class="pw-meter-track"><div class="pw-meter-bar" role="progressbar" style="width:0%;"></div></div>' +
            '<strong class="pw-meter-text"></strong>' +
            '<ul class="pw-checklist">' +
            KRITERIA.map(function (k) { return '<li data-pw-k="' + k.key + '">\u25CB ' + k.label + '</li>'; }).join('') +
            '</ul>' +
            '<small class="text-muted">' + hint + '</small>';
        // Letakkan meter DI BAWAH satu baris input+tombol (bukan di antaranya).
        var host = input.closest('.pwf-group') || input.closest('.input-group');
        if (host) {
            host.after(wrap);
        } else {
            input.after(wrap);
        }
        return wrap;
    }

    function updateMeter(input) {
        if (input.dataset.pwMeter !== '1') return;
        var wrap = input._pwMeterWrap || (input._pwMeterWrap = buildMeter(input));
        var bar = wrap.querySelector('.pw-meter-bar');
        var text = wrap.querySelector('.pw-meter-text');
        var items = wrap.querySelectorAll('.pw-checklist li');
        var oks = kriteria(input.value);
        var s = 0;
        for (var i = 0; i < oks.length; i++) if (oks[i]) s++;
        input.dataset.pwdScore = String(s);
        var pct = s * 20;
        var color = '#d9534f';
        var label = 'Sangat lemah';
        if (s >= 5) { color = '#00a65a'; label = 'Sangat kuat'; }
        else if (s === 4) { color = '#00a65a'; label = 'Kuat'; }
        else if (s === 3) { color = '#f0ad4e'; label = 'Sedang'; }
        else if (s === 2) { color = '#f0ad4e'; label = 'Lemah'; }
        bar.style.width = pct + '%';
        bar.style.backgroundColor = color;
        text.textContent = input.value ? label : '';
        text.style.color = input.value ? color : '';
        items.forEach(function (li, idx) {
            li.classList.toggle('ok', oks[idx]);
            li.textContent = (oks[idx] ? '\u2713 ' : '\u25CB ') + KRITERIA[idx].label;
        });
    }

    function initAll() {
        document.querySelectorAll('input.pw-field-input').forEach(function (input) {
            if (input._pwInit) return;
            input._pwInit = true;
            input.addEventListener('input', function () { updateMeter(input); });
            updateMeter(input);
        });
    }

    document.addEventListener('input', function (e) {
        var t = e.target;
        if (t && t.classList && t.classList.contains('pw-field-input')) updateMeter(t);
    });

    document.addEventListener('click', function (e) {
        var btn = e.target.closest ? e.target.closest('[data-pw-action]') : null;
        if (!btn) return;
        e.preventDefault();
        var action = btn.getAttribute('data-pw-action');
        var inputId = btn.getAttribute('data-pw-input');
        var input = document.getElementById(inputId);
        if (!input) return;

        if (action === 'toggle') {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.innerHTML = show ? ICON_EYE_OFF : ICON_EYE;
        } else if (action === 'generate') {
            input.value = generatePassword(14);
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
            var syncId = btn.getAttribute('data-pw-sync');
            if (syncId) {
                var sync = document.getElementById(syncId);
                if (sync) {
                    sync.value = input.value;
                    sync.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        } else if (action === 'copy') {
            if (!input.value || btn.dataset.pwCopied) return;
            var showCopied = function (ok) {
                if (!ok) return;
                btn.dataset.pwCopied = '1';
                btn.classList.add('pwf-copied');
                btn.setAttribute('title', 'Tersalin');
                btn.innerHTML = ICON_CHECK;
                setTimeout(function () {
                    delete btn.dataset.pwCopied;
                    btn.classList.remove('pwf-copied');
                    btn.setAttribute('title', 'Salin sandi');
                    btn.innerHTML = ICON_COPY;
                }, 1600);
            };
            var res = copyText(input.value);
            if (res && typeof res.then === 'function') res.then(showCopied, function () {});
            else showCopied(res);
        }
    });

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAll);
    } else {
        initAll();
    }
})();
