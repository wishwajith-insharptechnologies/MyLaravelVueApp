<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('projects_id')->nullable();
            $table->unsignedBigInteger('packages_id')->nullable();
            $table->unsignedBigInteger('defined');
            $table->unsignedBigInteger('status');
            $table->json('limitation')->nullable();
            $table->timestamps();

            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('packages_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limitations');
    }
};
