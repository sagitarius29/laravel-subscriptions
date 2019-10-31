<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->boolean('is_active')->default(0);
            $table->boolean('is_default')->default(0);

            $table->timestamps();
        });

        Schema::create('plan_intervals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->decimal('price');
            $table->enum('interval', ['day',  'month',  'year'])->nullable();
            $table->integer('interval_unit')->nullable();

            $table->foreign('plan_id')
                ->references('id')->on('plans');

            $table->timestamps();
        });

        Schema::create('plan_features', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('code', 100);
            $table->integer('value')->default(0);
            $table->integer('sort_order');
            $table->boolean('is_consumable')->default(0);

            $table->foreign('plan_id')
                ->references('id')->on('plans');

            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('subscriber_type')->nullable();
            $table->timestamp('subscriber_id')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->foreign('plan_id')
                ->references('id')->on('plans');

            $table->timestamps();
        });

        Schema::create('subscriber_consumables', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_feature_id')->unsigned();
            $table->string('subscriber_type');
            $table->integer('subscriber_id');
            $table->integer('available')->nullable();
            $table->integer('used')->nullable();

            $table->foreign('plan_feature_id')
                ->references('id')->on('plans_features');

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
        Schema::dropIfExists('subscriber_consumables');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plan_features');
        Schema::dropIfExists('plan_prices');
        Schema::dropIfExists('plans');
    }
}
