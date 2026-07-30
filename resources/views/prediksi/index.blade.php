<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ScholarAI Assistant</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="preconnect" href="https://fonts.googleapis.com">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>

*{

margin:0;

padding:0;

box-sizing:border-box;

font-family:'Inter',sans-serif;

}

body{

background:#f3f4f6;

height:100vh;

overflow:hidden;

}

.chat-app{

width:100%;

height:100vh;

display:flex;

justify-content:center;

align-items:center;

padding:30px;

}

.chat-card{

width:1000px;

height:92vh;

background:white;

border-radius:22px;

display:flex;

flex-direction:column;

overflow:hidden;

box-shadow:0 10px 40px rgba(0,0,0,.08);

}

.chat-header{

height:75px;

border-bottom:1px solid #ececec;

display:flex;

align-items:center;

justify-content:space-between;

padding:0 30px;

background:white;

}

.header-left{

display:flex;

align-items:center;

gap:15px;

}

.logo{

width:46px;

height:46px;

border-radius:50%;

background:#222;

color:white;

display:flex;

justify-content:center;

align-items:center;

font-size:20px;

}

.title h5{

margin:0;

font-weight:700;

}

.title span{

color:#777;

font-size:14px;

}

.chat-body{

flex:1;

overflow-y:auto;

padding:35px;

background:#fafafa;

}

.message{

display:flex;

margin-bottom:22px;

}

.message.bot{

justify-content:flex-start;

}

.message.user{

justify-content:flex-end;

}

.bubble{

padding:15px 22px;

border-radius:18px;

max-width:70%;

line-height:1.8;

font-size:15px;

}

.bot .bubble{

background:#ececec;

color:#222;

}

.user .bubble{

background:#111;

color:white;

}

.chat-footer{

padding:18px;

border-top:1px solid #ececec;

background:white;

}

.chat-input{

display:flex;

gap:15px;

}

.chat-input input{

height:52px;

border-radius:15px;

border:1px solid #d6d6d6;

padding:0 20px;

}

.chat-input button{

width:130px;

border-radius:15px;

background:#111;

border:none;

}

.chat-input button:hover{

background:black;

}

</style>

</head>

<body>

<div class="chat-app">

<div class="chat-card">

<div class="chat-header">

<div class="header-left">

<div class="logo">

🎓

</div>

<div class="title">

<h5>ScholarAI Assistant</h5>

<span>Decision Tree Recommendation</span>

</div>

</div>

<a href="{{ route('dashboard') }}" class="btn btn-outline-dark">

Kembali

</a>

</div>

<div class="chat-body" id="chatBody">

<div class="message bot">

<div class="bubble">

Halo 👋 Selamat datang di ScholarAI.

</div>

</div>

<div class="message bot">

<div class="bubble">

Saya akan membantu menentukan apakah Anda layak menerima beasiswa.

</div>

</div>

<div class="message bot">

<div class="bubble">

Silakan tekan tombol kirim untuk memulai percakapan.

</div>

</div>

</div>

<div class="chat-footer">

<div class="chat-input">

<input

id="answer"

class="form-control"

placeholder="Tulis jawaban Anda..." >

<button

class="btn btn-dark"

onclick="startChat()">

Mulai

</button>

</div>

</div>

</div>

</div>
<script>
const questions = [
    "Halo 👋 Sebelum mulai, siapa nama lengkap Anda?",
    "Berapa IPK Anda? (0 - 4)",
    "Berapa persentase kehadiran Anda? (0 - 100)",
    "Apakah Anda memiliki prestasi? (Ya / Tidak)",
    "Apakah Anda aktif organisasi? (Ya / Tidak)",
    "Berapa penghasilan orang tua per bulan?",
    "Semester berapa Anda saat ini?"
];

let currentQuestion = 0;
let started = false;

const answers = {};

const body = document.getElementById("chatBody");

function addBot(text){

    body.innerHTML += `
    <div class="message bot">
        <div class="bubble">${text}</div>
    </div>
    `;

    body.scrollTop = body.scrollHeight;

}

function addUser(text){

    body.innerHTML += `
    <div class="message user">
        <div class="bubble">${text}</div>
    </div>
    `;

    body.scrollTop = body.scrollHeight;

}

function typing(){

    body.innerHTML += `
    <div class="message bot" id="typing">
        <div class="bubble">ScholarAI sedang mengetik...</div>
    </div>
    `;

    body.scrollTop = body.scrollHeight;

}

function removeTyping(){

    const t=document.getElementById("typing");

    if(t) t.remove();

}

function validateInput(value){

    switch(currentQuestion){

        case 1:

            let ipk=parseFloat(value);

            if(isNaN(ipk)||ipk<0||ipk>4){

                addBot("❌ IPK harus antara 0 - 4");

                return false;

            }

        break;

        case 2:

            let hadir=parseInt(value);

            if(isNaN(hadir)||hadir<0||hadir>100){

                addBot("❌ Kehadiran harus 0 - 100");

                return false;

            }

        break;

        case 5:

            let gaji=parseInt(value);

            if(isNaN(gaji)||gaji<0){

                addBot("❌ Penghasilan harus berupa angka.");

                return false;

            }

        break;

        case 6:

            let semester=parseInt(value);

            if(isNaN(semester)||semester<1){

                addBot("❌ Semester tidak valid.");

                return false;

            }

        break;

    }

    return true;

}

function startChat(){

    const input=document.getElementById("answer");

    const value=input.value.trim();

    if(!started){

        started=true;

        document.querySelector(".chat-input button").innerHTML="Kirim";

        addBot(questions[currentQuestion]);

        return;

    }

    if(value=="") return;

    if(!validateInput(value)) return;

    addUser(value);

    answers[currentQuestion]=value;

    input.value="";

    currentQuestion++;

    if(currentQuestion>=questions.length){

        finishConversation();

        return;

    }

    typing();

    setTimeout(()=>{

        removeTyping();

        addBot(questions[currentQuestion]);

    },700);

}

function finishConversation(){

    typing();

    setTimeout(()=>{

        removeTyping();

        addBot("🎉 Semua data berhasil dikumpulkan.");

        addBot("Klik tombol Analisis untuk menyimpan data.");

        document.querySelector(".chat-footer").innerHTML=`

            <button
                class="btn btn-dark w-100"
                onclick="analisis()">

                Analisis Sekarang

            </button>

        `;

    },800);

}

async function analisis(){

    try{

        typing();

        const response=await fetch("{{ route('prediksi.store') }}",{

            method:"POST",

            headers:{

                "Content-Type":"application/json",

                "Accept":"application/json",

                "X-CSRF-TOKEN":"{{ csrf_token() }}"

            },

            body:JSON.stringify({

                nama:answers[0],

                ipk:parseFloat(answers[1]),

                kehadiran:parseInt(answers[2]),

                prestasi:answers[3],

                organisasi:answers[4],

                penghasilan:parseInt(answers[5]),

                semester:parseInt(answers[6])

            })

        });

        removeTyping();

        const result=await response.json();

        if(result.success){

            addBot("✅ Data berhasil disimpan.");

            addBot("ID Prediksi : "+result.id);

            addBot("⏳ Selanjutnya data akan diproses menggunakan Decision Tree.");

        }else{

            addBot("❌ Gagal menyimpan data.");

        }

    }catch(e){

        removeTyping();

        console.log(e);

        addBot("❌ Terjadi kesalahan saat menghubungkan ke server.");

    }

}

document.getElementById("answer").addEventListener("keypress",function(e){

    if(e.key==="Enter"){

        startChat();

    }

});
</script>

</body>

</html>