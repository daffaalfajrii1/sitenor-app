@extends('admin.layouts.app')
@section('content')
@include('admin.components.page-header',['title'=>'Edit Prestasi'])
<div class="main-content"><div class="card"><div class="card-body"><form action="{{ route('admin.prestasi.update',$prestasi) }}" method="POST">@csrf @method('PUT') @include('admin.prestasi._form',['item'=>$prestasi])<button class="btn btn-primary mt-3">Perbarui</button></form></div></div></div>
@endsection
