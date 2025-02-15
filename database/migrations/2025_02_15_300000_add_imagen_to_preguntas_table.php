use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagenToPreguntasTable extends Migration
{
    public function up()
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->string('imagen')->nullable()->after('tipo');
        });
    }

    public function down()
    {
        Schema::table('preguntas', function (Blueprint $table) {
            $table->dropColumn('imagen');
        });
    }
} 