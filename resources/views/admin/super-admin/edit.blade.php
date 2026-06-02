@extends('admin.layouts.app')
@section('title', 'Edit Super Admin')

@section('content')
@include('admin.components.page-header', [
    'title' => 'Edit Super Admin',
    'crumbs' => [
        ['label' => 'Super Admin', 'url' => route('admin.super-admin.index')],
        ['label' => $superAdmin->name],
    ],
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.super-admin.update', $superAdmin) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.super-admin._form', ['superAdmin' => $superAdmin])
                <x-form-actions :cancel-url="route('admin.super-admin.index')" submit-label="Simpan Perubahan" />
            </form>
        </div>
    </div>
</div>
@endsection
