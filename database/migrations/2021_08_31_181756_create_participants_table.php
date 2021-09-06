<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('department',['HANNAH','A51 CSR','PAYOUT','APP REQUEST','LIVECHAT','OPERATOR','FACILITIES','ADMIN','COMPANY NURSE','SPECIAL OPS','AUDIT','IT','DEVS','MARKETING','OCB']);
            $table->enum('shift',['MORNING','MID','GY']);
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
        Schema::dropIfExists('participants');
    }
}
