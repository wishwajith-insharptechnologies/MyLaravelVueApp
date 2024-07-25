<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnvironmentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('environment_types', function (Blueprint $table) {
            $table->id();
            $table->integer('value')->unique();
            $table->string('name');
            $table->boolean('status')->default(true); // Add status column
            $table->timestamps();
        });

        // Insert default environment types
        DB::table('environment_types')->insert([
            ['value' => 0, 'name' => 'Web Base', 'status' => true],
            ['value' => 1, 'name' => 'Stand Alone', 'status' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('environment_types');
    }
}
