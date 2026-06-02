@extends('admin.layouts.app')
@section('title', 'Tambah Artikel')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah Artikel', 'crumbs' => [['label' => 'Artikel', 'url' => route('admin.artikel.index')], ['label' => 'Tambah']]])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form id="artikel-form" action="{{ route('admin.artikel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.artikel._form')
                @include('admin.artikel._form-actions')
            </form>
        </div>
    </div>
</div>
@endsection
