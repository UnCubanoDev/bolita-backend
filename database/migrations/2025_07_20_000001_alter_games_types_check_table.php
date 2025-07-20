use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Elimina la restricción actual
        DB::statement("ALTER TABLE games DROP CONSTRAINT IF EXISTS games_type_check");
        // Crea una nueva restricción que incluya 'parle'
        DB::statement("ALTER TABLE games ADD CONSTRAINT games_type_check CHECK (type IN ('pick3', 'pick4', 'fijo', 'corrido', 'parle'))");
    }

    public function down(): void
    {
        // Elimina la restricción con 'parle'
        DB::statement("ALTER TABLE games DROP CONSTRAINT IF EXISTS games_type_check");
        // Restaura la restricción original (ajusta según tus valores originales)
        DB::statement("ALTER TABLE games ADD CONSTRAINT games_type_check CHECK (type IN ('pick3', 'pick4', 'fijo', 'corrido'))");
    }
};
