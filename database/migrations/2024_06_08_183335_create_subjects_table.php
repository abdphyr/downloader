<?php

use App\Enums\SubjectStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            // info
            $table->string('code');
            $table->json('info')->default('[]');

            // enums
            $table->integer('status')->default(SubjectStatusEnum::DRAFT);
            $table->integer('degree')->default(0);

            // keys 
            $table->bigInteger('type_id');
            $table->bigInteger('lang_id');
            $table->bigInteger('category_id');
            $table->bigInteger('user_id');
            $table->bigInteger('author_id');

            // counts
            $table->bigInteger('downloads')->default(0);
            $table->bigInteger('views')->default(0);
            $table->bigInteger('reviews')->default(0);
            $table->bigInteger('sumreviews')->default(0);

            $table->timestamps();
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->index(['code', 'type_id', 'type_id', 'status', 'degree', 'category_id', 'user_id']);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
