<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateTaggableTable.
 *
 * @author DraperStudio <hello@draperstudio.tech>
 */
class CreateTaggableTable extends Migration
{
    /**
     *
     */
    public function up()
    {
        Schema::dropIfExists('tags'); // needed because a previous module create one already (altough it was never used)
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
        });

        Schema::create('taggables', function (Blueprint $table) {
            $table->integer('tag_id');
            $table->morphs('taggable');
            $table->timestamps();
        });
    }

    /**
     *
     */
    public function down()
    {
        Schema::dropIfExists('tags');
        Schema::dropIfExists('taggables');
    }
}
