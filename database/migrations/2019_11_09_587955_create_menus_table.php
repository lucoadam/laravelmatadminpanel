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
            $table->bigIncrements("id");
			$table->string("name");
			$table->string("icon")->nullable();
			$table->string("url_type")->nullable();
			$table->string("url");
			$table->integer('parent_id')->default(0);
			$table->boolean('backend')->default(1);
			$table->boolean("open_in_new_tab")->nullable();
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
        Schema::dropIfExists("menus");
    }

}
