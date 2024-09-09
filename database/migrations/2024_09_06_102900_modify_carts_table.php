<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyCartsTable extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            // Vérifiez si la contrainte existe déjà
            if (!Schema::hasColumn('carts', 'user_id')) {
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            } else {
                // Optionnel : mettre à jour si nécessaire
                $table->dropForeign(['user_id']);
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
    }
}
