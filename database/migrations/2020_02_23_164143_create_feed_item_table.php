

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeeditemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feed_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('feed_id')->unsigned();
            $table->string('guid', 255)->unique();
            $table->string('title', 255);
            $table->string('description', 255);
            $table->longtext('url');
            $table->tinyInteger('pinned');
            $table->timestamps();
            $table->index(["feed_id"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feed_item');
    }
}

