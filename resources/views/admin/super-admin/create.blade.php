@extends('admin.layouts.app')
@section('title', 'Tambah Super Admin')

@section('content')
@include('admin.components.page-header', [
    'title' => 'Tambah Super Admin',
    'crumbs' => [
        ['label' => 'Super Admin', 'url' => route('admin.super-admin.index')],
        ['label' => 'Tambah'],
    ],
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.super-admin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.super-admin._form')
                <x-form-actions :cancel-url="route('admin.super-admin.index')" submit-label="Simpan Super Admin" />
            </form>
        </div>
    </div>
</div>
@endsection
