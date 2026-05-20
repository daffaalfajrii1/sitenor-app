<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('cabor_id')->nullable()->after('id')->constrained('cabors')->nullOnDelete();
        });

        Schema::create('atlets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabor_id')->constrained('cabors')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('nik', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['laki-laki', 'perempuan'])->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['cabor_id', 'slug']);
        });

        Schema::create('pelatihs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabor_id')->constrained('cabors')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('license_number')->nullable();
            $table->string('level')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['cabor_id', 'slug']);
        });

        Schema::create('wasits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabor_id')->constrained('cabors')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('license_number')->nullable();
            $table->string('level')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['cabor_id', 'slug']);
        });

        Schema::create('juris', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabor_id')->constrained('cabors')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('license_number')->nullable();
            $table->string('level')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['cabor_id', 'slug']);
        });

        Schema::create('artikels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabor_id')->nullable()->constrained('cabors')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::create('prestasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('atlet_id')->constrained('atlets')->cascadeOnDelete();
            $table->string('title');
            $table->string('event_name')->nullable();
            $table->enum('level', ['kabupaten', 'provinsi', 'nasional', 'internasional'])->default('kabupaten');
            $table->string('rank')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasis');
        Schema::dropIfExists('pengumumans');
        Schema::dropIfExists('artikels');
        Schema::dropIfExists('juris');
        Schema::dropIfExists('wasits');
        Schema::dropIfExists('pelatihs');
        Schema::dropIfExists('atlets');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('cabor_id');
        });

        Schema::dropIfExists('cabors');
    }
};
