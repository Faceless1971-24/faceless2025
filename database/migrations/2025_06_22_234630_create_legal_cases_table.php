<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLegalCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('member_id');
            $table->enum('case_type', ['গ্রেপ্তার', 'হুমকি', 'মামলা']);
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'closed'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
$table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legal_cases');
    }
}
