<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHospitalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("hospitals", function (Blueprint $table) {
            $table->increments("id");
			$table->string("name");
			$table->lineString("pan");
//			$table->bigInteger("phone");
//			$table->string("email");
			$table->text("documents");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop("hospitals");
    }

}
