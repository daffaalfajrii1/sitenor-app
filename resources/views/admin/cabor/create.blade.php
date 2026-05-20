@extends('admin.layouts.app')

@section('title', 'Tambah Cabor')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Tambah Cabang Olahraga',
        'crumbs' => [
            ['label' => 'Cabang Olahraga', 'url' => route('admin.cabor.index')],
            ['label' => 'Tambah'],
        ],
    ])

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.cabor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.cabor._form')
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="feather-save me-2"></i>Simpan
                        </button>
                        <a href="{{ route('admin.cabor.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
