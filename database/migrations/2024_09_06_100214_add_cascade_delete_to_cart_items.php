<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeDeleteToCartItems extends Migration
{
    /**
     * La migration est en cours.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Supprimer la contrainte existante
            $table->foreign('product_id') // Ajouter la contrainte avec cascade
                  ->references('id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * La migration est annulée.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']); // Supprimer la contrainte avec cascade
            $table->foreign('product_id') // Ajouter la contrainte sans cascade
                  ->references('id')
                  ->on('products');
        });
    }
}
