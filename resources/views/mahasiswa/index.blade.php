@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between mb-4">

    <h3 class="fw-bold">
        Data Mahasiswa
    </h3>

    <button class="btn btn-primary">
        <i class="bi bi-plus-circle"></i>
        Tambah Mahasiswa
    </button>

</div>

<div class="row">

@for($i=1;$i<=6;$i++)

<div class="col-md-4 mb-4">

<div class="card border-0 shadow rounded-4">

<div class="card-body text-center">

<img src="https://ui-avatars.com/api/?name=Mahasiswa+{{$i}}&background=0D6EFD&color=fff&size=128"
class="rounded-circle mb-3">

<h5 class="fw-bold">
Mahasiswa {{$i}}
</h5>

<p class="text-muted">
22010{{$i}}
</p>

<hr>

<div class="mb-2">

IPK

<div class="progress">

<div class="progress-bar bg-success"
style="width:90%">
3.82
</div>

</div>

</div>

<div class="mb-2">

Kehadiran

<div class="progress">

<div class="progress-bar bg-info"
style="width:95%">
95%
</div>

</div>

</div>

<div class="mb-3">

<span class="badge bg-success">
Aktif Organisasi
</span>

<span class="badge bg-warning text-dark">
2 Prestasi
</span>

</div>

<div class="d-grid">

<a href="/analisis"
class="btn btn-primary">

<i class="bi bi-cpu"></i>

Analisis AI

</a>

</div>

</div>

</div>

</div>

@endfor

</div>

@endsection