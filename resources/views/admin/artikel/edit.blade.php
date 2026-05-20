@extends('admin.layouts.app')
@section('content')
@include('admin.components.page-header',['title'=>'Edit Artikel'])
<div class="main-content"><div class="card"><div class="card-body"><form action="{{ route('admin.artikel.update',$artikel) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.artikel._form',['item'=>$artikel])<button class="btn btn-primary mt-3">Perbarui</button></form></div></div></div>
@endsection
