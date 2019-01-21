<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emailtemplates', function (Blueprint $table) {
        $table->increments('id');
        $table->string('title');
        $table->string('subject');
        $table->text('message');
        $table->enum('status', array('0', '1','2'))->comment('0 = Inactive 1 = Active 2 = Deleted');
        $table->dateTime('created_at');
        $table->dateTime('updated_at');
        $table->bigInteger('created_by');
        $table->bigInteger('updated_by');
        });
	
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('email_templates');
    }
}
