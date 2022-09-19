<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->after('email');
            $table->unsignedBigInteger('position_id')->after('phone');
            $table->string('photo')->after('position_id');

            $table->index('position_id', 'user_position_idx');
            $table->foreign('position_id', 'user_position_fk')->on('positions')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('user_position_fk');
            $table->dropIndex('user_position_idx');

            $table->dropColumn('phone');
            $table->dropColumn('position_id');
            $table->dropColumn('photo');
        });
    }
};
