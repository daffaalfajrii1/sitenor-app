@extends('cabor.layouts.app')
@section('title', 'Tambah Artikel')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Tambah Artikel',
    'crumbs' => [
        ['label' => 'Artikel', 'url' => cabor_route('cabor.artikel.index')],
        ['label' => 'Tambah'],
    ],
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form id="artikel-form" action="{{ cabor_route('cabor.artikel.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.artikel._form', ['hideCaborSelect' => true])
                @include('admin.artikel._form-actions', [
                    'cancelUrl' => cabor_route('cabor.artikel.index'),
                ])
            </form>
        </div>
    </div>
</div>
@endsection
