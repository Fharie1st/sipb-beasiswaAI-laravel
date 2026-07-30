@extends('layouts.app')

@section('title', 'ScholarAI Assistant')

@section('content')

<style>

.prediction-wrapper{
    margin-top:110px;
    margin-bottom:40px;
}

.prediction-card{
    height:calc(100vh - 180px);
    border:none;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 15px 40px rgba(0,0,0,.08);
}

.prediction-header{
    background:linear-gradient(135deg,#111827,#374151);
    color:#fff;
    padding:22px 28px;
}

.prediction-header h4{
    margin:0;
    font-size:24px;
    font-weight:700;
}

.prediction-header small{
    display:block;
    margin-top:4px;
    opacity:.9;
}

.prediction-progress{
    background:#fff;
    padding:16px 28px;
    border-bottom:1px solid #ececec;
}

.mode-tab{

    flex:1;

    padding:12px 20px;

    border:1px solid #D1D5DB;

    border-radius:14px;

    background:#fff;

    color:#111827;

    font-weight:600;

    cursor:pointer;

    transition:.25s;

}

.mode-tab:hover{

    background:#F3F4F6;

}

.mode-tab.active{

    background:#111827;

    color:#fff;

    border-color:#111827;

}

#predictionChat{
    height:calc(100vh - 360px);
    overflow-y:auto;
    background:#F8FAFC;
    padding:25px;
}

.message{
    display:flex;
    margin-bottom:18px;
}

.message.bot{
    justify-content:flex-start;
}

.message.user{
    justify-content:flex-end;
}

.bubble{
    max-width:70%;
    padding:14px 18px;
    border-radius:18px;
    font-size:15px;
    line-height:1.7;
    box-shadow:0 6px 18px rgba(0,0,0,.05);
}

.message.bot .bubble{
    background:#fff;
    border:1px solid #E5E7EB;
}

.message.user .bubble{
    background:#111827;
    color:#fff;
}

.prediction-input{
    background:#fff;
    padding:18px;
    border-top:1px solid #ECECEC;
}

.prediction-input .form-control{
    height:52px;
    border-radius:30px 0 0 30px;
}

.prediction-input .btn{
    width:70px;
    border-radius:0 30px 30px 0;
    background:#111827;
    border-color:#111827;
}

.prediction-input .btn:hover{
    background:#374151;
    border-color:#374151;
}

</style>

<div class="container prediction-wrapper">

    <div class="card prediction-card">

        <div class="prediction-header">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h4>🎓 ScholarAI Assistant</h4>

                    <small>Sistem Analisis Beasiswa menggunakan Decision Tree</small>

                </div>

                <div class="text-end">

                    <strong id="progressText" style="display:none">0%</strong>

                </div>

            </div>

        </div>

        <div class="prediction-progress">
            <div class="mode-tabs">

<div class="mode-tabs">

    <button
        id="predictTab"
        type="button"
        class="mode-tab active">

        🎯 Prediksi

    </button>

    <button
        id="assistantTab"
        type="button"
        class="mode-tab">

        💬 Tanya Beasiswa

    </button>

    <button type="button" id="historyTab" class="mode-tab">Riwayat</button>

</div>

</div>

            <div id="progressContainer" style="display:none; margin-top:12px;">
                <div class="progress" style="height:8px">

                    <div
                        id="progressBar"
                        class="progress-bar progress-bar-striped progress-bar-animated"
                        style="width:0%">

                    </div>

                </div>
            </div>

        </div>

        <div id="predictionChat">

        </div>

        <div class="prediction-input">

            <div class="input-group">

                <input
                    id="userInput"
                    type="text"
                    class="form-control"
                    placeholder="Tulis jawaban Anda...">

                <button
                    id="sendBtn"
                    class="btn btn-primary">

                    <i class="bi bi-send-fill"></i>

                </button>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script src="{{ asset('js/chat.js') }}"></script>


@endpush