@extends('admin.layouts.app')

@section('title', 'Edit Cabor')

@section('content')
    @include('admin.components.page-header', [
        'title' => 'Edit: '.$cabor->name,
        'crumbs' => [
            ['label' => 'Cabang Olahraga', 'url' => route('admin.cabor.index')],
            ['label' => 'Edit'],
        ],
    ])

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.cabor.update', $cabor) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.cabor._form', ['cabor' => $cabor])
                    <div class="mt-4 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="feather-save me-2"></i>Perbarui
                        </button>
                        <a href="{{ route('admin.cabor.index') }}" class="btn btn-light">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
