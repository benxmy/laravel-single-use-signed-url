<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSingleUseSignedUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('single_use_signed_urls', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('route_name');
            $table->string('key');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('expires_at')->nullable();
            $table->ipAddress('accessed_by_ip')->nullable();
            $table->timestamp('accessed_at')->nullable();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('single_use_signed_urls');
    }
}