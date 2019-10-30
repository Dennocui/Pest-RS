<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePestsTable extends Migration
{
    public function up()
    {
        Schema::create('pests', function (Blueprint $table) {
            $table->increments('id');

            $table->string('pest_name');

            $table->longText('pest_desc')->nullable();

            $table->timestamps();

            $table->softDeletes();
        });
    }
}
