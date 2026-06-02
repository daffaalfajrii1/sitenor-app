@extends('admin.layouts.app')
@section('title', 'Edit Kepala Cabor')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Edit Kepala Cabor',
    'crumbs' => [
        ['label' => 'Kepala Cabor', 'url' => route('admin.kepala-cabor.index')],
        ['label' => 'Edit'],
    ],
])
<div class="main-content"><div class="card"><div class="card-body">
<form action="{{ route('admin.kepala-cabor.update', $kepalaCabor) }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
@include('admin.kepala-cabor._form', ['kepalaCabor' => $kepalaCabor])
<x-form-actions :cancel-url="route('admin.kepala-cabor.index')" />
</form></div></div></div>
@endsection
