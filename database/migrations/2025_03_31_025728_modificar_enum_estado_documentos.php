<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE documentos DROP CONSTRAINT documentos_estado_check");
        DB::table('documentos')->where('estado', 'borrador')->update(['estado' => 'revision']);
        DB::statement("ALTER TABLE documentos ADD CONSTRAINT documentos_estado_check CHECK (estado IN ('activo', 'archivado', 'revision'))");
    }

    public function down()
    {
        DB::statement("ALTER TABLE documentos DROP CONSTRAINT documentos_estado_check");
        DB::statement("ALTER TABLE documentos ADD CONSTRAINT documentos_estado_check CHECK (estado IN ('activo', 'archivado', 'borrador'))");
    }
};
