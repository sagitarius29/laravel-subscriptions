<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('group', 100)->nullable();
            $table->integer('free_days')->default(0);
            $table->tinyInteger('sort_order');
            $table->boolean('is_enabled')->default(0);
            $table->boolean('is_default')->default(0);

            $table->timestamps();
        });

        Schema::create('plan_intervals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->decimal('price');
            $table->enum('type', ['day', 'month', 'year'])->nullable();
            $table->integer('unit')->nullable();

            $table->foreign('plan_id')
                ->references('id')->on('plans')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('code', 100);
            $table->integer('value')->default(0);
            $table->integer('sort_order')->nullable();
            $table->boolean('is_consumable')->default(0);

            $table->foreign('plan_id')
                ->references('id')->on('plans')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('subscriber_type')->nullable();
            $table->integer('subscriber_id')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->foreign('plan_id')
                ->references('id')->on('plans');
        });

        Schema::create('subscription_consumables', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('plan_feature_code', 100);
            $table->integer('subscription_id')->unsigned();
            $table->integer('available')->nullable();
            $table->integer('used')->nullable();
            $table->timestamps();

            $table->foreign('subscription_id')
                ->references('id')->on('subscriptions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_consumables');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plan_intervals');
        Schema::dropIfExists('plans');
    }
}
