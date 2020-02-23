<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function ($table) {
            $table->foreign('user_id', 'fk_notes_user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });

        Schema::table('user_settings', function ($table) {
            $table->foreign('user_id', 'fk_settings_user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });

        Schema::table('user_feed', function ($table) {
            $table->foreign('user_id', 'fk_uf_user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('NO ACTION');
            $table->foreign('feed_id', 'fk_uf_feed_id')->references('id')->on('feed')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });

        Schema::table('feed_item', function ($table) {
            $table->foreign('feed_id', 'fk_fi_feed_id')->references('id')->on('feed')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });

        Schema::table('user_feed_item', function ($table) {
            $table->foreign('user_id', 'fk_ufi_user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('NO ACTION');
            $table->foreign('feed_item_id', 'fk_ufi_feed_item_id')->references('id')->on('feed_item')->onDelete('CASCADE')->onUpdate('NO ACTION');
            $table->foreign('user_feed_id', 'fk_ufi_user_feed_id')->references('id')->on('user_feed')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });

        Schema::table('checklist_item', function ($table) {
            $table->foreign('user_id', 'fk_checklist_user_id')->references('id')->on('users')->onDelete('CASCADE')->onUpdate('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
