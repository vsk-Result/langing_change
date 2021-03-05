<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('preview_url');
            $table->string('storage_url');
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('landing_id')->references('id')->on('landings');
            $table->foreignId('actual_version_id')->nullable();
            $table->string('title');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('project_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->string('title');
            $table->string('storage_url')->nullable();
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
        Schema::table('project_versions', function (Blueprint $table) {
            $table->dropForeign(['project_id']);
        });
        Schema::dropIfExists('project_versions');

        Schema::table('projects', function (Blueprint $table) {
            $table->dropForeign(['landing_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::dropIfExists('projects');
    }
}
