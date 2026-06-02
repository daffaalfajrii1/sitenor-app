@extends('admin.layouts.app')
@section('title', 'Tambah Kepala Cabor')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Tambah Kepala Cabor',
    'crumbs' => [
        ['label' => 'Kepala Cabor', 'url' => route('admin.kepala-cabor.index')],
        ['label' => 'Tambah'],
    ],
])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.kepala-cabor.store') }}" method="POST" enctype="multipart/form-data">@csrf
@include('admin.kepala-cabor._form')
<x-form-actions :cancel-url="route('admin.kepala-cabor.index')" />
</form></div></div></div>
@endsection
