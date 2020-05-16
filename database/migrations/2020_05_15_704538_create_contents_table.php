<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("contents", function (Blueprint $table) {
            $table->bigIncrements("id");
			$table->text("title");
			$table->bigInteger("image_id")->unsigned();
			$table->foreign("image_id")->references("id")->on("images")->onUpdate("RESTRICT")->onDelete("CASCADE");
			$table->bigInteger("file_id")->unsigned();
			$table->foreign("file_id")->references("id")->on("files")->onUpdate("RESTRICT")->onDelete("CASCADE");
			$table->longText("description");
			$table->string("type");
			$table->string("posted_by");
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
        Schema::drop("contents");
    }

}