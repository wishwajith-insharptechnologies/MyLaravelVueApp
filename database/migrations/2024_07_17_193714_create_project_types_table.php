<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('status')->default(true); // Add status column
            $table->timestamps();
        });

        // Insert default project types
        DB::table('project_types')->insert([
            ['id' => 1, 'name' => 'Web', 'status' => true],
            ['id' => 2, 'name' => 'Mobile', 'status' => true],
            ['id' => 3, 'name' => 'SASS', 'status' => true],
            ['id' => 4, 'name' => 'Solution', 'status' => true],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_types');
    }
}
