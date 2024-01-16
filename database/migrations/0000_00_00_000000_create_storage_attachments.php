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
        if (! Schema::hasTable('storage_attachments')) {
            Schema::create('storage_attachments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->morphs('storable');
                $table->string('original', 255);
                $table->string('filename', 255)->uniqid();
                $table->string('type')->nullable();
                $table->text('metadata')->nullable();
                $table->bigInteger('size')->nullable();
                $table->string('checksum', 255)->nullable();
                $table->string('service', 255)->default('s3');
                $table->boolean('deleted')->default(false);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('storage_attachments');
    }
};
