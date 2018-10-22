<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WarehouseApiLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_api_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url', 500);
            $table->string('method', 255);
            $table->longText('request');
            $table->longText('response');
            $table->bigInteger('user_id');
            $table->string('ip_address', 100);
            $table->integer('status_code');
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
        Schema::dropIfExists('warehouse_api_logs');
    }
}
