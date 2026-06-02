@extends('admin.layouts.app')
@section('title', 'Edit Artikel')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Edit Artikel',
    'crumbs' => [
        ['label' => 'Artikel', 'url' => route('admin.artikel.index')],
        ['label' => $artikel->title],
    ],
])
<div class="main-content">
    <div class="card">
        <div class="card-body">
            <form id="artikel-form" action="{{ route('admin.artikel.update', $artikel) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('admin.artikel._form', [
                    'artikel' => $artikel,
                    'editorDraftKey' => 'sitenor_artikel_draft_admin_'.$artikel->id,
                ])
                @include('admin.artikel._form-actions')
            </form>
        </div>
    </div>
</div>
@endsection
