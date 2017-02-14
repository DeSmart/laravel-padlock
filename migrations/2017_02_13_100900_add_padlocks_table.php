<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPadlocksTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('padlocks');

        Schema::create('padlocks', function(Blueprint $table) {
            $table->string('name')->primary();
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('padlocks');
    }
}
