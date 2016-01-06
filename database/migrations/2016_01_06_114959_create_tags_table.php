<?php

use App\Model\Tag;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('filename');
            $table->string('name');
            $table->string('slug');
            $table->string('description')->default('');
        });

        $tags = [
            [
                'name'     => 'Party',
                'filename' => 'noun_132751_cc',
            ],
            [
                'name'     => 'Konzert',
                'filename' => 'noun_14878_cc',
            ],
            [
                'name'     => 'Vortrag',
                'filename' => 'noun_57144_cc',
            ],
        ];

        collect($tags)->each(function ($tag) {
            Tag::create($tag);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tags');
    }
}
