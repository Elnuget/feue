<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cuestionarios', function (Blueprint $table) {
            if (!Schema::hasColumn('cuestionarios', 'permite_revision')) {
                $table->boolean('permite_revision')->default(false)->after('activo');
            }
            if (!Schema::hasColumn('cuestionarios', 'retroalimentacion')) {
                $table->text('retroalimentacion')->nullable()->after('permite_revision');
            }
            if (!Schema::hasColumn('cuestionarios', 'config_revision')) {
                $table->json('config_revision')->nullable()->after('retroalimentacion');
            }
        });
    }

    public function down()
    {
        Schema::table('cuestionarios', function (Blueprint $table) {
            $table->dropColumn(['permite_revision', 'retroalimentacion', 'config_revision']);
        });
    }
}; 