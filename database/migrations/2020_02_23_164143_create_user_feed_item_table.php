

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserfeeditemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_feed_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('feed_item_id')->unsigned();
            $table->bigInteger('user_feed_id')->unsigned();
            $table->tinyInteger('viewed');
            $table->tinyInteger('pinned');
            $table->datetime('opened');
            $table->timestamps();
            $table->index(["user_id"]);
            $table->index(["feed_item_id"]);
            $table->index(["user_feed_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_feed_item');
    }
}

