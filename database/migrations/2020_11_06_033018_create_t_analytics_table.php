<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTAnalyticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('pagePath',2000);
			$table->string('dateHourMinute');
            $table->integer('users');
			$table->integer('newUsers');
			$table->integer('sessions');
			$table->float('sessionsPerUser');
			$table->integer('pageviews');
			$table->float('pageviewsPerSession');
			$table->float('avgSessionDuration');
			$table->float('bounceRate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_analytics');
    }
}
