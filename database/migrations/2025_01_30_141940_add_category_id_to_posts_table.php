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
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable(); // Add the category_id column
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade'); // Add foreign key constraint
        });
    }
    
    
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Drop the foreign key
            $table->dropColumn('category_id'); // Drop the category_id column
        });
    }

};
