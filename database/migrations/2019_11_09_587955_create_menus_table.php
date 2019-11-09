<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("menus", function (Blueprint $table) {
            $table->increments("id");
			$table->string("name");
			$table->string("icon");
			$table->string("url_type");
			$table->string("url");
			$table->boolean("open_in_new_tab");
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
        Schema::drop("menus");
    }

}