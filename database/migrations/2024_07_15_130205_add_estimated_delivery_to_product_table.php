<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->integer('estimated_delivery')->nullable(); // Estimated delivery in days
        });
    }
    
    public function down()
    {
        Schema::table('product', function (Blueprint $table) {
            $table->dropColumn('estimated_delivery');
        });
    }
};
