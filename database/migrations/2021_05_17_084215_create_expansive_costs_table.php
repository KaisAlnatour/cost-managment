<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpansiveCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expansive_costs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('crater')->unsigned();
            $table->foreign('crater')->references('id')->on('users')->cascadeOnDelete();;
            $table->string('itemCode');
            $table->foreign('itemCode')->references('code')->on('item_costs')->cascadeOnDelete();
            $table->string('name');
            $table->integer('state');   // 1 -> paid / 2 -> unpaid
            $table->integer('paidValue');
            $table->date('date')->nullable();
            $table->integer('monthDate')->nullable();
            $table->integer('yearDate')->nullable();
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
        Schema::dropIfExists('expansive_costs');
    }
}
