@extends('cabor.layouts.app')
@section('title', 'Edit Artikel')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Edit Artikel',
    'crumbs' => [
        ['label' => 'Artikel', 'url' => cabor_route('cabor.artikel.index')],
        ['label' => $artikel->title],
    ],
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form id="artikel-form" action="{{ cabor_route('cabor.artikel.update', $artikel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.artikel._form', [
                    'hideCaborSelect' => true,
                    'artikel' => $artikel,
                ])
                @include('admin.artikel._form-actions', [
                    'cancelUrl' => cabor_route('cabor.artikel.index'),
                ])
            </form>
        </div>
    </div>
</div>
@endsection
