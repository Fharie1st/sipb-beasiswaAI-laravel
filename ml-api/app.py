from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd

app = Flask(__name__)
CORS(app)

# ==========================================
# LOAD MODEL
# ==========================================

model_package = joblib.load("model.pkl")

model = model_package["model"]
feature_names = model_package["feature_names"]
encoders = model_package["encoders"]

print("\n====================================")
print("ScholarAI Flask API")
print("Feature Names :", feature_names)
print("====================================\n")

# ==========================================
# HOME
# ==========================================

@app.route("/")
def home():
    return jsonify({
        "status": "running",
        "message": "ScholarAI API Ready"
    })

# ==========================================
# PREDICT
# ==========================================

@app.route("/predict", methods=["POST"])
def predict():

    try:

        data = request.get_json()

        if not data:
            return jsonify({
                "success": False,
                "message": "Data tidak ditemukan."
            }), 400

        # ==========================
        # Pastikan semua feature ada
        # ==========================

        for feature in feature_names:

            if feature not in data:

                return jsonify({
                    "success": False,
                    "message": f"Field '{feature}' belum dikirim."
                }), 400

        # ==========================
        # DataFrame
        # ==========================

        df = pd.DataFrame([data])

        # ==========================
        # Encode kategori
        # ==========================

        for col, encoder in encoders.items():

            if col in df.columns:

                try:
                    df[col] = encoder.transform(df[col])

                except Exception:

                    return jsonify({
                        "success": False,
                        "message": f"Nilai '{df[col].iloc[0]}' tidak dikenali pada kolom '{col}'."
                    }), 400

        # ==========================
        # Urutkan kolom
        # ==========================

        df = df[feature_names]

        # ==========================
        # Predict
        # ==========================

        prediction = int(model.predict(df)[0])

        confidence = round(
            float(model.predict_proba(df).max()) * 100,
            2
        )

        status = (
            "Layak Menerima Beasiswa"
            if prediction == 1
            else "Belum Layak Menerima Beasiswa"
        )

        return jsonify({

            "success": True,

            "prediction": prediction,

            "status": status,

            "confidence": confidence

        })

    except Exception as e:

        return jsonify({

            "success": False,

            "message": str(e)

        }), 500


# ==========================================
# ASK (ScholarAI Assistant - Tanya Beasiswa)
# ==========================================
# Endpoint terpisah dari /predict, khusus untuk menjawab
# pertanyaan bebas seputar beasiswa. Menggunakan pencocokan
# kata kunci sederhana (tanpa LLM eksternal) sehingga bisa
# langsung jalan tanpa API key tambahan.

FAQ_KNOWLEDGE_BASE = [
    {
        "keywords": ["kip", "kip kuliah", "kip-kuliah"],
        "answer": (
            "<p><strong>KIP Kuliah</strong> (Kartu Indonesia Pintar Kuliah) adalah bantuan biaya "
            "pendidikan dari pemerintah untuk lulusan SMA/SMK/sederajat yang memiliki potensi "
            "akademik baik namun terkendala secara ekonomi.</p>"
            "<p>Syarat umum:</p>"
            "<ul>"
            "<li>Terdaftar sebagai siswa SMA/SMK/sederajat yang akan lulus pada tahun berjalan, "
            "atau lulusan maksimal 2 tahun sebelumnya</li>"
            "<li>Memiliki potensi akademik baik namun keterbatasan ekonomi (dibuktikan dengan KIP, "
            "KKS, PKH, atau Data Terpadu Kesejahteraan Sosial)</li>"
            "<li>Lolos seleksi masuk perguruan tinggi pada program studi terakreditasi</li>"
            "</ul>"
            "<p>Pendaftaran dilakukan melalui laman resmi <em>kip-kuliah.kemdikbud.go.id</em> "
            "dengan NIK, NISN, dan NPSN yang valid.</p>"
        )
    },
    {
        "keywords": ["syarat", "persyaratan", "kriteria"],
        "answer": (
            "<p>Secara umum, persyaratan untuk mendaftar beasiswa meliputi:</p>"
            "<ul>"
            "<li>IPK minimal sesuai ketentuan beasiswa (umumnya 3.00 ke atas)</li>"
            "<li>Aktif sebagai mahasiswa dan belum menyelesaikan studi</li>"
            "<li>Tidak sedang menerima beasiswa lain pada periode yang sama</li>"
            "<li>Melampirkan bukti penghasilan orang tua/wali</li>"
            "<li>Surat rekomendasi dari kampus (jika diminta oleh pemberi beasiswa)</li>"
            "<li>Melengkapi berkas administrasi seperti KTM, KHS, dan KTP</li>"
            "</ul>"
            "<p>Persyaratan detail bisa berbeda-beda tergantung jenis beasiswanya, jadi selalu cek "
            "pengumuman resmi dari penyelenggara beasiswa yang dituju.</p>"
        )
    },
    {
        "keywords": ["tips", "lolos", "strategi", "cara lolos"],
        "answer": (
            "<p>Beberapa tips agar peluang lolos seleksi beasiswa lebih besar:</p>"
            "<ul>"
            "<li>Jaga IPK tetap stabil atau meningkat tiap semester</li>"
            "<li>Aktif di organisasi, kepanitiaan, atau kegiatan sosial sebagai nilai tambah</li>"
            "<li>Siapkan berkas jauh-jauh hari agar tidak terburu-buru mendekati deadline</li>"
            "<li>Tulis esai/motivation letter yang jujur, spesifik, dan menonjolkan kontribusi nyata</li>"
            "<li>Minta surat rekomendasi dari dosen yang benar-benar mengenal kamu</li>"
            "<li>Cek ulang seluruh syarat administrasi sebelum submit agar tidak gugur teknis</li>"
            "</ul>"
        )
    },
    {
        "keywords": ["unggulan", "beasiswa unggulan"],
        "answer": (
            "<p><strong>Beasiswa Unggulan</strong> adalah program beasiswa dari Kemdikbudristek "
            "untuk mahasiswa berprestasi di jenjang S1, S2, maupun S3, baik jalur reguler, "
            "prestasi, maupun penghargaan.</p>"
            "<p>Beasiswa ini mencakup biaya kuliah dan biaya hidup, dengan seleksi berbasis "
            "prestasi akademik maupun non-akademik.</p>"
        )
    },
    {
        "keywords": ["dokumen", "berkas", "administrasi"],
        "answer": (
            "<p>Dokumen yang umumnya dibutuhkan saat mendaftar beasiswa:</p>"
            "<ul>"
            "<li>Kartu Tanda Mahasiswa (KTM) dan KTP</li>"
            "<li>Kartu Hasil Studi (KHS) atau transkrip nilai terbaru</li>"
            "<li>Surat keterangan penghasilan orang tua/wali</li>"
            "<li>Surat rekomendasi (jika diminta)</li>"
            "<li>Esai atau motivation letter</li>"
            "<li>Foto dan pas foto sesuai ketentuan</li>"
            "</ul>"
        )
    }
]

DEFAULT_ANSWER = (
    "<p>Maaf, saya belum menemukan jawaban spesifik untuk pertanyaan itu di basis "
    "pengetahuan saya saat ini.</p>"
    "<p>Coba tanyakan seputar topik berikut, ya:</p>"
    "<ul>"
    "<li>Syarat dan cara mendaftar KIP Kuliah</li>"
    "<li>Persyaratan umum beasiswa</li>"
    "<li>Tips lolos seleksi beasiswa</li>"
    "</ul>"
)


def find_best_answer(question: str) -> str:

    normalized = question.lower()

    for item in FAQ_KNOWLEDGE_BASE:
        for keyword in item["keywords"]:
            if keyword in normalized:
                return item["answer"]

    return DEFAULT_ANSWER


@app.route("/ask", methods=["POST"])
def ask():

    try:

        data = request.get_json()

        if not data or not data.get("question"):
            return jsonify({
                "success": False,
                "message": "Pertanyaan tidak boleh kosong."
            }), 400

        question = str(data.get("question")).strip()

        answer = find_best_answer(question)

        return jsonify({
            "success": True,
            "answer": answer
        })

    except Exception as e:

        return jsonify({

            "success": False,

            "message": str(e)

        }), 500


# ==========================================
# RUN
# ==========================================

if __name__ == "__main__":

    app.run(
        host="0.0.0.0",
        port=5000,
        debug=True
    )