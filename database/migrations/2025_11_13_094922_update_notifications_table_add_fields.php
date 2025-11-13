    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up()
        {
            Schema::table('notifications', function (Blueprint $table) {
                // Ajout des champs séparés
                $table->string('title')->nullable()->after('type');
                $table->text('body')->nullable()->after('title');
                $table->unsignedBigInteger('messe_id')->nullable()->after('body');
            });
        }

        public function down()
        {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropColumn(['title', 'body', 'messe_id']);
            });
        }
    };
