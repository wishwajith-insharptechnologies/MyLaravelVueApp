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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->string('title');
            $table->integer('rank');
            $table->integer('validity');
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 5, 2)->nullable();
            $table->text('images')->nullable();
            $table->string('status');
            $table->text('stripe_link')->nullable();
            $table->string('trial_period')->nullable();
            $table->unsignedBigInteger('projects_id')->nullable();
            $table->unsignedBigInteger('category_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('projects_id')->references('id')->on('projects')->onDelete('cascade');
            // $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('packages');
    }
};
