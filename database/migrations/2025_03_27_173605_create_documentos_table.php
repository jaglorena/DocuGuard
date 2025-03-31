<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->bigIncrements('id_documento');  
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('ruta_archivo');
            $table->timestamp('fecha_subida')->useCurrent();
            $table->integer('version');
            $table->enum('estado', ['activo', 'archivado', 'en revision']);
            $table->unsignedBigInteger('id_usuario_subida');
            $table->foreign('id_usuario_subida')->references('id_usuario')->on('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documentos');
    }
};
