<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdAndAddedByToProspectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->nullable()->after('id');
            $table->unsignedInteger('added_by')->nullable()->after('user_id');

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('added_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('prospects', function (Blueprint $table) {
            $table->dropForeign('prospects_user_id_foreign');
            $table->dropColumn('user_id');

            $table->dropForeign('prospects_added_by_foreign');
            $table->dropColumn('added_by');
        });
    }
}
