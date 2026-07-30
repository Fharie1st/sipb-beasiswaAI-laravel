import './bootstrap';
// ==============================
// ScholarAI Chat
// ==============================

const predictTab = document.getElementById("predictTab");
const assistantTab = document.getElementById("assistantTab");

const chat = document.getElementById("predictionChat");
const input = document.getElementById("userInput");
const send = document.getElementById("sendBtn");

const bar = document.getElementById("progressBar");
const text = document.getElementById("progressText");

let mode = "predict";
let step = 0;
let answers = {};
let loadingMessage = null;

// ==============================
// Opsi Program Studi
// ==============================
// Daftar ini HARUS persis sama dengan yang dikenali model.pkl.
// Kalau prodi user tidak ada di daftar ini, model akan menolak
// prediksi karena tidak dikenali encoder-nya.

const PRODI_OPTIONS = [
    "Bimbingan dan Konseling | Reguler",
    "D III Keperawatan | Reguler",
    "DIII Kebidanan | Reguler",
    "Manajemen | Reguler",
    "Pendidikan Bahasa Inggris | Reguler",
    "Pendidikan Bahasa dan Sastra Indonesia | Reguler",
    "Pendidikan Guru Sekolah Dasar | Reguler",
    "Pendidikan Matematika | Reguler",
    "S1 Farmasi | Reguler",
    "S1 Ilmu Komunikasi | Reguler",
    "S1 Keperawatan | Reguler",
    "S1 Matematika | Reguler",
    "S1 Psikolog | Reguler",
    "S1 Sastra Inggris | Reguler",
    "S1 Sistem Komputer | Reguler",
    "S1 Teknik Elektro | Reguler",
    "S1 Teknik Mesin | Reguler"
];

// ==============================
// Konfigurasi Pertanyaan Prediksi
// ==============================

const questions = [
    {
        key: "prodi",
        type: "choice",
        title: "Program studi kamu apa?",
        desc: "Pilih program studi kamu dari daftar di bawah ini.",
        options: PRODI_OPTIONS
    },
    {
        key: "ipk",
        type: "text",
        title: "IPK kamu berapa?",
        desc: "Isi dengan angka antara 0.00 sampai 4.00.",
        ex: "Contoh: 3.75"
    },
    {
        key: "sks",
        type: "text",
        title: "Sudah berapa SKS yang kamu lulusi?",
        desc: "Hitung total SKS yang sudah dinyatakan lulus, bukan yang lagi diambil sekarang.",
        ex: "Contoh: 96"
    },
    {
        key: "penghasilan",
        type: "choice",
        title: "Berapa penghasilan orang tua kamu per bulan?",
        desc: "Pilih kategori yang paling sesuai dengan gabungan penghasilan ayah dan ibu per bulan.",
        options: [
            "Rendah",
            "Sedang",
            "Tinggi"
        ],
        optionLabels: {
            "Rendah": "Rendah (di bawah ± Rp2.000.000)",
            "Sedang": "Sedang (± Rp2.000.000 - Rp5.000.000)",
            "Tinggi": "Tinggi (di atas ± Rp5.000.000)"
        }
    },
    {
        key: "tanggungan",
        type: "text",
        title: "Ada berapa tanggungan di keluarga kamu?",
        desc: "Hitung semua anggota keluarga yang jadi tanggungan orang tua, termasuk kamu sendiri.",
        ex: "Contoh: kalau kamu punya 2 saudara dan tinggal bareng orang tua, berarti tanggungannya 4"
    },
    {
        key: "organisasi",
        type: "choice",
        title: "Kamu aktif di organisasi kampus?",
        desc: "Pilih salah satu di bawah ini.",
        options: ["Ya", "Tidak"]
    }
];

// ==============================
// Quick Question (mode Tanya Beasiswa)
// ==============================

const quickQuestions = [
    { label: "KIP Kuliah", question: "Apa syarat dan cara mendaftar KIP Kuliah?" },
    { label: "Persyaratan", question: "Apa saja persyaratan umum untuk mendapatkan beasiswa?" },
    { label: "Tips Lolos", question: "Berikan tips agar lolos seleksi beasiswa." }
];

// ==============================
// Helper Umum
// ==============================

function scrollBottom() {
    chat.scrollTop = chat.scrollHeight;
}

function clearChat() {
    chat.innerHTML = "";
}

function removeLoading() {
    if (loadingMessage) {
        loadingMessage.remove();
        loadingMessage = null;
    }
}

function addMessage(message, type = "bot") {
    const div = document.createElement("div");
    div.className = `message ${type}`;
    div.innerHTML = `<div class="bubble">${message}</div>`;
    chat.appendChild(div);
    scrollBottom();
    return div;
}

function showLoading(title, subtitle) {
    loadingMessage = addMessage(`
        <div class="text-center py-4">
            <div class="spinner-border text-primary mb-3"></div>
            <h5>${title}</h5>
            <small class="text-muted">${subtitle}</small>
        </div>
    `);
}

function formatRupiah(value) {
    const number = Number(value) || 0;
    return "Rp" + number.toLocaleString("id-ID");
}

function getCsrfToken() {
    return document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute("content");
}

function setProgressVisible(visible) {
    const progressContainer = document.getElementById("progressContainer");
    if (progressContainer) {
        progressContainer.style.display = visible ? "" : "none";
    }

    const progressText = document.getElementById("progressText");
    if (progressText) {
        progressText.style.display = visible ? "" : "none";
    }
}

function updateProgress() {
    if (!bar || !text) return;

    if (mode !== "predict") {
        setProgressVisible(false);
        return;
    }

    const p = Math.round(step / questions.length * 100);

    if (p >= 100) {
        setProgressVisible(false);
        return;
    }

    setProgressVisible(true);
    bar.style.width = `${p}%`;
    text.textContent = `${p}%`;
}

function setInputEnabled(enabled) {
    input.disabled = !enabled;
    send.disabled = !enabled;
    input.placeholder = enabled ? "Tulis jawaban Anda..." : "Pilih salah satu opsi di atas...";
}

// Menyembunyikan / menampilkan seluruh kotak input + tombol kirim.
// Dipakai supaya kotak input hilang khusus saat mode "history".
function setInputBarVisible(visible) {
    const inputBar = input.closest(".prediction-input");
    if (inputBar) inputBar.style.display = visible ? "" : "none";
}

// ==============================
// Mode Switcher
// ==============================

function switchMode(newMode) {
    mode = newMode;
    step = 0;
    answers = {};

    clearChat();
    chat.classList.remove("history-view");
    setInputEnabled(true);
    setInputBarVisible(newMode !== "history"); // sembunyikan input hanya di Riwayat
    setProgressVisible(newMode === "predict");

    if (mode === "predict") {
        startPredictionMode();
    } else if (mode === "assistant") {
        startAssistantMode();
    } else {
        startHistoryMode();
    }
}

function startPredictionMode() {
    removeQuickQuestions();

    setProgressVisible(true);
    bar.style.width = "0%";
    text.textContent = "0%";

    addMessage(`
        <h5>ScholarAI Assistant</h5>
        <p>
            Halo! Aku bakal bantu cek kelayakan beasiswa kamu
            pakai model <strong>Decision Tree</strong>.
        </p>
        <p>
            Jawab tiap pertanyaan di bawah dengan data yang bener ya,
            biar hasil analisisnya makin akurat.
        </p>
    `);

    setTimeout(askQuestion, 800);
}

function startAssistantMode() {
    setProgressVisible(false);
    bar.style.width = "100%";
    text.textContent = "AI";

    addMessage(`
        <h5>ScholarAI Assistant</h5>
        <p>Halo! Selamat datang di Tanya Beasiswa.</p>
        <p>
            Kamu bisa pilih salah satu topik cepat di bawah,
            atau langsung ketik pertanyaan sendiri.
        </p>
    `);

    renderQuickQuestions();
}

// ==============================
// Riwayat Analisis (list data, bukan chat)
// ==============================

let historyCache = [];

function injectHistoryStyles() {
    if (document.getElementById("historyStyles")) return;

    const style = document.createElement("style");
    style.id = "historyStyles";
    style.textContent = `
        #predictionChat.history-view {
            background: transparent !important;
            box-shadow: none !important;
            border: none !important;
            padding: 0 !important;
        }
        .history-list {
            padding: 10px 0;
        }
        .history-item {
            border: 1px solid #e5e5e5;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 14px;
            background: #fff;
        }
        .history-item-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .history-item-actions {
            display: flex;
            gap: 8px;
        }
        .history-btn {
            border: 1px solid #d0d0d0;
            background: #fff;
            border-radius: 6px;
            padding: 4px 12px;
            font-size: 13px;
            cursor: pointer;
            transition: background .15s ease;
        }
        .history-btn:hover {
            background: #f0f0f0;
        }
        .history-btn.danger {
            color: #d63b3b;
            border-color: #f0b8b8;
        }
        .history-btn.danger:hover {
            background: #fdeaea;
        }
        #editModalOverlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }
        #editModalBox {
            background: #fff;
            border-radius: 14px;
            width: 100%;
            max-width: 420px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 28px;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }
        #editModalBox label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin: 12px 0 4px;
        }
        #editModalBox input,
        #editModalBox select {
            width: 100%;
            height: 42px;
            border: 1px solid #d0d0d0;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
        }
        #editModalActions {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        #editModalActions button {
            flex: 1;
            height: 42px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            cursor: pointer;
        }
        #editSaveBtn {
            background: #111827;
            color: #fff;
        }
        #editCancelBtn {
            background: #f0f0f0;
            color: #111827;
        }
    `;
    document.head.appendChild(style);
}

async function startHistoryMode() {
    removeQuickQuestions();
    injectHistoryStyles();
    chat.classList.add("history-view");

    setProgressVisible(false);
    bar.style.width = "100%";
    text.textContent = "Riwayat";

    showLoading("Mengambil riwayat...", "Mohon tunggu sebentar");

    try {
        const response = await fetch("/riwayat", {
            method: "GET",
            headers: { "Accept": "application/json" }
        });

        removeLoading();

        if (!response.ok) throw new Error("Gagal mengambil riwayat");

        const result = await response.json();
        historyCache = result.data || [];

        renderHistoryList();

    } catch (e) {
        console.error(e);
        removeLoading();
        addMessage(`
            <div class="alert alert-danger mb-0">
                Gagal mengambil riwayat. Coba refresh halaman.
            </div>
        `);
    }
}

function renderHistoryList() {
    clearChat();

    if (historyCache.length === 0) {
        chat.innerHTML = `
            <div class="text-center py-5">
                <h5>Riwayat kosong</h5>
                <p class="text-muted">Kamu belum pernah melakukan analisis. Yuk coba mulai dari tab Prediksi.</p>
            </div>
        `;
        return;
    }

    const wrapper = document.createElement("div");
    wrapper.className = "history-list";

    wrapper.innerHTML = historyCache.map(item => {
        const isEligible = Number(item.prediction) === 1;
        const status = isEligible ? "Layak Menerima Beasiswa" : "Belum Layak Menerima Beasiswa";
        const color = isEligible ? "#1a9d4c" : "#d63b3b";

        return `
            <div class="history-item" data-id="${item.id}">
                <div class="history-item-top">
                    <strong>${item.prodi}</strong>
                    <span style="font-size:12px; color:#888;">${item.created_at}</span>
                </div>
                <p style="margin:0 0 4px 0; color:${color}; font-weight:600;">${status}</p>
                <p style="margin:0 0 8px 0; color:#555; font-size:14px;">
                    Keyakinan sistem: <strong>${item.confidence}%</strong>
                </p>
                <p style="margin:0 0 12px 0; color:#777; font-size:13px;">
                    IPK ${item.ipk} &middot; SKS ${item.sks} &middot;
                    Penghasilan: ${item.penghasilan} &middot;
                    Tanggungan ${item.tanggungan} &middot;
                    Organisasi: ${item.organisasi}
                </p>
                <div class="history-item-actions">
                    <button type="button" class="history-btn edit-btn" data-id="${item.id}">Edit</button>
                    <button type="button" class="history-btn danger delete-btn" data-id="${item.id}">Hapus</button>
                </div>
            </div>
        `;
    }).join("");

    chat.appendChild(wrapper);

    wrapper.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", () => openEditModal(btn.dataset.id));
    });

    wrapper.querySelectorAll(".delete-btn").forEach(btn => {
        btn.addEventListener("click", () => confirmDeleteHistory(btn.dataset.id));
    });
}

// ==============================
// Edit Riwayat
// ==============================

function openEditModal(id) {
    const item = historyCache.find(h => String(h.id) === String(id));
    if (!item) return;

    const prodiOptionsHtml = PRODI_OPTIONS.map(p =>
        `<option value="${p}" ${item.prodi === p ? "selected" : ""}>${p}</option>`
    ).join("");

    const overlay = document.createElement("div");
    overlay.id = "editModalOverlay";

    overlay.innerHTML = `
        <div id="editModalBox">
            <h5 style="font-weight:700; margin:0 0 8px;">Edit Data Prediksi</h5>

            <label>Program Studi</label>
            <select id="editProdi">${prodiOptionsHtml}</select>

            <label>IPK</label>
            <input type="number" id="editIpk" min="0" max="4" step="0.01" value="${item.ipk}">

            <label>SKS</label>
            <input type="number" id="editSks" min="0" value="${item.sks}">

            <label>Kategori Penghasilan Orang Tua</label>
            <select id="editPenghasilan">
                <option value="Rendah" ${item.penghasilan === "Rendah" ? "selected" : ""}>Rendah</option>
                <option value="Sedang" ${item.penghasilan === "Sedang" ? "selected" : ""}>Sedang</option>
                <option value="Tinggi" ${item.penghasilan === "Tinggi" ? "selected" : ""}>Tinggi</option>
            </select>

            <label>Jumlah Tanggungan</label>
            <input type="number" id="editTanggungan" min="0" value="${item.tanggungan}">

            <label>Aktif Organisasi?</label>
            <select id="editOrganisasi">
                <option value="Ya" ${item.organisasi === "Ya" ? "selected" : ""}>Ya</option>
                <option value="Tidak" ${item.organisasi === "Tidak" ? "selected" : ""}>Tidak</option>
            </select>

            <div id="editModalActions">
                <button id="editCancelBtn" type="button">Batal</button>
                <button id="editSaveBtn" type="button">Simpan</button>
            </div>
        </div>
    `;

    document.body.appendChild(overlay);

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) overlay.remove();
    });

    document.getElementById("editCancelBtn").onclick = () => overlay.remove();
    document.getElementById("editSaveBtn").onclick = () => submitEdit(id, overlay);
}

async function submitEdit(id, overlay) {
    const payload = {
        prodi: document.getElementById("editProdi").value,
        ipk: parseFloat(document.getElementById("editIpk").value),
        sks: Number(document.getElementById("editSks").value),
        penghasilan: document.getElementById("editPenghasilan").value,
        tanggungan: Number(document.getElementById("editTanggungan").value),
        organisasi: document.getElementById("editOrganisasi").value,
    };

    if (!payload.prodi || isNaN(payload.ipk) || isNaN(payload.sks) || isNaN(payload.tanggungan)) {
        alert("Mohon lengkapi semua field dengan data yang valid.");
        return;
    }

    try {
        const response = await fetch(`/riwayat/${id}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCsrfToken()
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            alert(result.message || "Gagal menyimpan perubahan.");
            return;
        }

        const idx = historyCache.findIndex(h => String(h.id) === String(id));
        if (idx !== -1) historyCache[idx] = result.data;

        overlay.remove();
        renderHistoryList();

    } catch (e) {
        console.error(e);
        alert("Gagal terhubung ke server.");
    }
}

// ==============================
// Hapus Riwayat
// ==============================

function confirmDeleteHistory(id) {
    if (!confirm("Yakin mau hapus data riwayat ini? Data yang dihapus tidak bisa dikembalikan.")) {
        return;
    }
    deleteHistoryItem(id);
}

async function deleteHistoryItem(id) {
    try {
        const response = await fetch(`/riwayat/${id}`, {
            method: "DELETE",
            headers: {
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCsrfToken()
            }
        });

        const result = await response.json();

        if (!response.ok || !result.success) {
            alert(result.message || "Gagal menghapus data.");
            return;
        }

        historyCache = historyCache.filter(h => String(h.id) !== String(id));
        renderHistoryList();

    } catch (e) {
        console.error(e);
        alert("Gagal terhubung ke server.");
    }
}

function injectChoiceStyles() {
    if (document.getElementById("choiceStyles")) return;

    const style = document.createElement("style");
    style.id = "choiceStyles";
    style.textContent = `
        .choice-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 10px 0;
        }
        .choice-btn {
            text-align: left;
            background: #ffffff;
            color: #111827;
            border: 1px solid #d0d0d0;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.15s ease, border-color 0.15s ease;
        }
        .choice-btn:hover {
            background: #f0f0f0;
            border-color: #999;
        }
        #quickQuestionBar {
            flex-wrap: nowrap;
            overflow-x: auto;
        }
        .quick-question-btn {
            background: #ffffff;
            color: #000000;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
            padding: 8px 20px;
            font-size: 14px;
            white-space: nowrap;
            flex-shrink: 0;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .quick-question-btn:hover {
            background: #f0f0f0;
        }
    `;
    document.head.appendChild(style);
}

function renderQuickQuestions() {
    removeQuickQuestions();
    injectChoiceStyles();

    const quickBar = document.createElement("div");
    quickBar.id = "quickQuestionBar";
    quickBar.className = "d-flex gap-2 mb-2";

    quickBar.innerHTML = quickQuestions.map((q, i) => `
        <button type="button" class="quick-question-btn" data-index="${i}">
            ${q.label}
        </button>
    `).join("");

    const inputRow = input.parentElement;
    inputRow.parentElement.insertBefore(quickBar, inputRow);

    quickBar.querySelectorAll(".quick-question-btn").forEach(btn => {
        btn.addEventListener("click", () => {
            const q = quickQuestions[btn.dataset.index];
            sendAssistant(q.question);
        });
    });
}

function removeQuickQuestions() {
    const existing = document.getElementById("quickQuestionBar");
    if (existing) existing.remove();
}

// ==============================
// Alur Pertanyaan Prediksi
// ==============================

function askQuestion() {
    if (step >= questions.length) {
        finishPrediction();
        return;
    }

    const q = questions[step];
    injectChoiceStyles();

    if (q.type === "choice") {
        setInputEnabled(false);

        const optionsHtml = q.options.map(opt => {
            const label = (q.optionLabels && q.optionLabels[opt]) ? q.optionLabels[opt] : opt;
            return `<button type="button" class="choice-btn" data-value="${opt}">${label}</button>`;
        }).join("");

        const msgEl = addMessage(`
            <strong>Pertanyaan ${step + 1} / ${questions.length}</strong>
            <hr>
            <h5>${q.title}</h5>
            <p>${q.desc}</p>
            <div class="choice-list">${optionsHtml}</div>
        `);

        msgEl.querySelectorAll(".choice-btn").forEach(btn => {
            btn.addEventListener("click", () => selectChoice(q, btn.dataset.value));
        });

    } else {
        setInputEnabled(true);

        addMessage(`
            <strong>Pertanyaan ${step + 1} / ${questions.length}</strong>
            <hr>
            <h5>${q.title}</h5>
            <p>${q.desc}</p>
            <small class="text-muted">${q.ex}</small>
        `);

        input.focus();
    }

    updateProgress();
}

function selectChoice(q, value) {
    addMessage(value, "user");

    answers[q.key] = value;

    step++;
    setTimeout(askQuestion, 400);
}

// ==============================
// Validasi Input (khusus pertanyaan bertipe text)
// ==============================

function validate(q, value) {
    if (value === "") return "Jawabannya jangan kosong ya.";

    if (["sks", "tanggungan"].includes(q.key)) {
        if (isNaN(value) || Number(value) < 0)
            return "Tolong isi dengan angka yang valid, tidak boleh negatif.";
    }

    if (q.key === "ipk") {
        const ipk = parseFloat(value);
        if (isNaN(ipk) || ipk < 0 || ipk > 4)
            return "IPK harus angka antara 0.00 sampai 4.00.";
    }

    return true;
}

// ==============================
// Kirim Jawaban Prediksi (khusus pertanyaan bertipe text)
// ==============================

function sendPrediction() {
    let value = input.value.trim();
    const q = questions[step];

    if (q.type === "choice") return; // pertanyaan ini dijawab via tombol, bukan input teks

    const check = validate(q, value);

    if (check !== true) {
        addMessage(check);
        return;
    }

    if (q.key === "ipk") value = parseFloat(value);
    if (["sks", "tanggungan"].includes(q.key)) value = Number(value);

    addMessage(value, "user");

    answers[q.key] = value;

    input.value = "";
    step++;

    setTimeout(askQuestion, 500);
}

// ==============================
// Tanya AI (mode assistant)
// ==============================

async function sendAssistant(presetMessage = null) {
    const message = presetMessage ?? input.value.trim();

    if (!message) return;

    addMessage(message, "user");
    input.value = "";

    showLoading("ScholarAI sedang berpikir...", "Mohon tunggu beberapa detik");

    try {
        const response = await fetch("/assistant/ask", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCsrfToken()
            },
            body: JSON.stringify({ question: message })
        });

        removeLoading();

        if (!response.ok) throw new Error("Server Error");

        const data = await response.json();

        addMessage(`
            <strong>ScholarAI</strong>
            <hr>
            ${data.answer}
        `);

    } catch (e) {
        removeLoading();
        addMessage(`
            <div class="alert alert-danger mb-0">
                AI Assistant tidak dapat dihubungi.
            </div>
        `);
    }
}

// ==============================
// Router Kirim Pesan
// ==============================

function sendMessage() {
    if (mode === "predict") sendPrediction();
    else if (mode === "assistant") sendAssistant();
    // mode "history" tidak pakai input teks, jadi diabaikan
}

// ==============================
// Tab Switching
// ==============================

document.querySelectorAll(".mode-tab").forEach(tab => {
    tab.addEventListener("click", () => {
        document.querySelector(".mode-tab.active")?.classList.remove("active");
        tab.classList.add("active");

        let newMode = "assistant";
        if (tab.id === "predictTab") newMode = "predict";
        else if (tab.id === "historyTab") newMode = "history";

        switchMode(newMode);
    });
});

// ==============================
// Analisis Faktor (ringkasan sisi klien)
// ==============================

function buildFactorSummary(answers) {
    const factors = [];

    if (typeof answers.ipk === "number") {
        factors.push({
            label: "IPK",
            ok: answers.ipk >= 3.0,
            note: answers.ipk >= 3.0
                ? "IPK Anda memenuhi standar minimal beasiswa."
                : "IPK Anda di bawah standar umum beasiswa (biasanya minimal 3.00)."
        });
    }

    if (answers.penghasilan) {
        const ok = answers.penghasilan !== "Tinggi";
        factors.push({
            label: "Penghasilan Orang Tua",
            ok,
            note: ok
                ? "Penghasilan orang tua sesuai kriteria penerima beasiswa."
                : "Penghasilan orang tua tergolong tinggi untuk kriteria beasiswa ini."
        });
    }

    if (typeof answers.tanggungan === "number") {
        factors.push({
            label: "Jumlah Tanggungan",
            ok: answers.tanggungan >= 3,
            note: answers.tanggungan >= 3
                ? "Jumlah tanggungan keluarga mendukung penilaian kelayakan."
                : "Jumlah tanggungan keluarga tergolong sedikit."
        });
    }

    if (answers.organisasi) {
        factors.push({
            label: "Keaktifan Organisasi",
            ok: answers.organisasi === "Ya",
            note: answers.organisasi === "Ya"
                ? "Keaktifan berorganisasi menjadi nilai tambah."
                : "Belum aktif di organisasi kampus."
        });
    }

    return factors;
}

function renderFactorList(factors) {
    if (!factors.length) return "";

    return `
        <ul style="list-style:none; padding:0; text-align:left; max-width: 380px; margin:0 auto;">
            ${factors.map(f => `
                <li style="margin-bottom:6px; color:${f.ok ? '#1a9d4c' : '#c9820f'};">
                    <strong style="color:#222;">${f.label}:</strong> ${f.note}
                </li>
            `).join("")}
        </ul>
    `;
}

function buildRecommendation(isEligible, factors) {
    if (isEligible) {
        return "Pertahankan performa akademik dan keaktifan Anda, lalu segera siapkan berkas pendaftaran beasiswa.";
    }

    const weakPoints = factors.filter(f => !f.ok).map(f => f.label);

    if (weakPoints.length) {
        return `Fokus memperbaiki aspek berikut agar peluang lolos meningkat: ${weakPoints.join(", ")}.`;
    }

    return "Coba lengkapi data secara lebih detail atau konsultasikan langsung dengan bagian kemahasiswaan.";
}

function injectResultModalStyles() {
    if (document.getElementById("resultModalStyles")) return;

    const style = document.createElement("style");
    style.id = "resultModalStyles";
    style.textContent = `
        #resultModalOverlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 20px;
        }
        #resultModalBox {
            background: #ffffff;
            border-radius: 14px;
            width: 100%;
            max-width: 460px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 32px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
        }
    `;
    document.head.appendChild(style);
}

function showResultModal(contentHtml) {
    injectResultModalStyles();
    closeResultModal();

    const overlay = document.createElement("div");
    overlay.id = "resultModalOverlay";

    const box = document.createElement("div");
    box.id = "resultModalBox";
    box.innerHTML = contentHtml;

    overlay.appendChild(box);
    document.body.appendChild(overlay);

    overlay.addEventListener("click", (e) => {
        if (e.target === overlay) closeResultModal();
    });
}

function closeResultModal() {
    const existing = document.getElementById("resultModalOverlay");
    if (existing) existing.remove();
}

// ==============================
// Finish Prediction
// ==============================

async function finishPrediction() {
    setProgressVisible(false);
    bar.style.width = "100%";
    text.textContent = "100%";

    setInputEnabled(false);
    showLoading("Sedang menganalisis data...", "Mohon tunggu sebentar");

    try {
        const response = await fetch("/prediksi", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": getCsrfToken()
            },
            body: JSON.stringify(answers)
        });

        const data = await response.json();

        console.log("RAW RESPONSE /prediksi:", data);

        if (!response.ok || data.success === false) {
            removeLoading();
            addMessage(`
                <div class="alert alert-danger mb-0">
                    Gagal memproses prediksi: ${data.message || 'Terjadi kesalahan tidak diketahui.'}
                </div>
            `);
            return;
        }

        if (typeof data.prediction === "undefined" || typeof data.confidence === "undefined") {
            removeLoading();
            addMessage(`
                <div class="alert alert-danger mb-0">
                    Respons dari sistem prediksi tidak lengkap. Cek Console (F12) untuk detail.
                </div>
            `);
            return;
        }

        const isEligible = data.prediction == 1;
        const status = isEligible ? "Layak Menerima Beasiswa" : "Belum Layak Menerima Beasiswa";
        const color = isEligible ? "#1a9d4c" : "#d63b3b";

        const accuracyText = data.accuracy
            ? `<p style="color:#777; margin-bottom:4px;">Akurasi Model: <strong>${data.accuracy}%</strong></p>`
            : "";

        const factors = buildFactorSummary(answers);
        const recommendation = buildRecommendation(isEligible, factors);

        removeLoading();
        clearChat();

        addMessage(`
            Analisis kamu udah selesai. Hasilnya muncul di popup, ya.
        `);

        showResultModal(`
            <h3 style="font-weight:700; margin-bottom:4px;">${status}</h3>

            <p style="color:#777; margin-bottom:4px;">Tingkat Keyakinan Sistem</p>
            <div style="font-size:42px; font-weight:700; color:${color}; margin-bottom:12px;">
                ${data.confidence}%
            </div>

            <p style="color:#777; margin-top:12px; margin-bottom:4px;">
                Model yang dipakai: <strong>Decision Tree</strong>
            </p>
            ${accuracyText}

            <hr style="margin:20px 0;">

            <h6 style="font-weight:700; margin-bottom:12px;">Ringkasan Faktor Penilaian</h6>
            ${renderFactorList(factors)}

            <hr style="margin:20px 0;">

            <h6 style="font-weight:700; margin-bottom:8px;">Rekomendasi</h6>
            <p style="color:#555;">${recommendation}</p>

            <button
                id="predictAgain"
                style="
                    margin-top:16px;
                    background:#1a1a2e;
                    color:#fff;
                    border:none;
                    border-radius:8px;
                    padding:10px 24px;
                    font-size:14px;
                    cursor:pointer;
                ">
                Prediksi Lagi
            </button>
        `);

        document.getElementById("predictAgain").onclick = () => {
            closeResultModal();
            switchMode("predict");
        };

    } catch (e) {
        console.error(e);
        removeLoading();

        addMessage(`
            <div class="alert alert-danger">
                Gagal terhubung ke server.
                <hr>
                Pastikan:
                <ul class="mb-0">
                    <li>Laravel aktif</li>
                    <li>Flask aktif</li>
                    <li>Model berhasil dimuat</li>
                </ul>
            </div>
        `);
    } finally {
        setInputEnabled(true);
    }
}

// ==============================
// Event Listener
// ==============================

send.onclick = sendMessage;

input.addEventListener("keydown", e => {
    if (e.key === "Enter") {
        e.preventDefault();
        sendMessage();
    }
});

// ==============================
// Start
// ==============================

document.addEventListener("DOMContentLoaded", () => {
    switchMode("predict");
});
