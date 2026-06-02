<?php

$modules = [
    'atlet' => ['singular' => 'Atlet', 'form' => 'admin.atlet._form', 'item' => 'atlet', 'extra' => true],
    'pelatih' => ['singular' => 'Pelatih', 'form' => 'admin.pelatih._form', 'item' => 'pelatih', 'extra' => false],
    'wasit' => ['singular' => 'Wasit', 'form' => 'admin.wasit._form', 'item' => 'wasit', 'extra' => false],
    'juri' => ['singular' => 'Juri', 'form' => 'admin.juri._form', 'item' => 'juri', 'extra' => false],
];

foreach ($modules as $name => $m) {
    $dir = __DIR__.'/../resources/views/cabor/'.$name;
    if (! is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    $plural = $name;
    $item = $m['item'];
    $prestasiBtn = $m['extra']
        ? '<a href="{{ route(\'cabor.prestasi.create\', [\'atlet_id\' => $'.$item.'->id]) }}" class="btn btn-sm btn-light-brand">Prestasi</a> '
        : '';

    file_put_contents($dir.'/index.blade.php', "@extends('cabor.layouts.app')
@section('title', '{$m['singular']}')
@section('content')
@include('admin.components.page-header', [
    'title' => 'Data {$m['singular']}',
    'crumbs' => [['label' => '{$m['singular']}']],
    'actions' => '<a href=\"{{ route(\'cabor.{$name}.create\') }}\" class=\"btn btn-primary\"><i class=\"feather-plus me-2\"></i>Tambah</a>',
])
<div class=\"main-content\">
<div class=\"card mb-3\"><div class=\"card-body\"><form method=\"GET\" class=\"row g-2\"><motion class=\"col-md-8\"><input type=\"text\" name=\"search\" class=\"form-control\" placeholder=\"Cari nama...\" value=\"{{ request('search') }}\"></div><div class=\"col-md-2\"><button class=\"btn btn-light-brand w-100\">Filter</button></div></form></div></div>
<div class=\"card\"><div class=\"card-body p-0\"><table class=\"table table-hover mb-0\"><thead><tr><th>Nama</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
@forelse (\${$plural} as \${$item})
<tr><td>{{ \${$item}->name }}</td><td><span class=\"badge {{ \${$item}->is_active ? 'bg-soft-success text-success' : 'bg-soft-danger text-danger' }}\">{{ \${$item}->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
<td class=\"d-inline-flex flex-nowrap gap-2\">{$prestasiBtn}<a href=\"{{ route('cabor.{$name}.edit', \${$item}) }}\" class=\"btn btn-sm btn-light-brand\">Edit</a>
<form action=\"{{ route('cabor.{$name}.destroy', \${$item}) }}\" method=\"POST\" class=\"d-inline\" onsubmit=\"return confirm('Hapus?')\">@csrf @method('DELETE')<button class=\"btn btn-sm btn-danger\">Hapus</button></form></td></tr>
@empty<tr><td colspan=\"3\" class=\"text-center text-muted py-4\">Belum ada data.</td></tr>@endforelse
</tbody></table><div class=\"p-3\">{{ \${$plural}->links() }}</div></div></div>
</div>
@endsection
");

    file_put_contents($dir.'/create.blade.php', "@extends('cabor.layouts.app')
@section('title', 'Tambah {$m['singular']}')
@section('content')
@include('admin.components.page-header', ['title' => 'Tambah {$m['singular']}', 'crumbs' => [['label' => '{$m['singular']}', 'url' => route('cabor.{$name}.index')], ['label' => 'Tambah']]])
<div class=\"main-content\"><div class=\"card\"><div class=\"card-body\">
<form action=\"{{ route('cabor.{$name}.store') }}\" method=\"POST\" enctype=\"multipart/form-data\">@csrf
@include('{$m['form']}', ['hideCaborSelect' => true".($name === 'atlet' ? ", 'hideNik' => true" : '')."])
<div class=\"mt-4\"><button type=\"submit\" class=\"btn btn-primary\">Simpan</button><a href=\"{{ route('cabor.{$name}.index') }}\" class=\"btn btn-light ms-2\">Batal</a></div>
</form></div></motion></div>
@endsection
");

    file_put_contents($dir.'/edit.blade.php', "@extends('cabor.layouts.app')
@section('title', 'Edit {$m['singular']}')
@section('content')
@include('admin.components.page-header', ['title' => 'Edit {$m['singular']}', 'crumbs' => [['label' => '{$m['singular']}', 'url' => route('cabor.{$name}.index')], ['label' => 'Edit']]])
<div class=\"main-content\"><div class=\"card\"><div class=\"card-body\">
<form action=\"{{ route('cabor.{$name}.update', \${$item}) }}\" method=\"POST\" enctype=\"multipart/form-data\">@csrf @method('PUT')
@include('{$m['form']}', ['hideCaborSelect' => true".($name === 'atlet' ? ", 'hideNik' => true" : '').", '{$item}' => \${$item}])
<div class=\"mt-4\"><button type=\"submit\" class=\"btn btn-primary\">Simpan</button><a href=\"{{ route('cabor.{$name}.index') }}\" class=\"btn btn-light ms-2\">Batal</a></div>
</form></div></div></div>
@endsection
");
}

echo "ok\n";
