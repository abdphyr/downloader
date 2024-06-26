<?php

use App\Partials\File\File;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->text('info');
            $table->string('path');
            $table->bigInteger('fileable_id');
            $table->string('fileable_type');
            $table->tinyInteger('type')->default(File::FILE);
            $table->timestamps();
            $table->index('fileable_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
