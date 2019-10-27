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
        Schema::create('plans', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name', 150);
            $table->text('details')->nullable();
            $table->string('group', 100)->nullable();
            $table->integer('free_days')->default(0);
            $table->boolean('sys_active')->default(0);
            $table->boolean('sys_default')->default(0);

            $table->timestamps();

        });

        Schema::create('plans_periods', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->integer('price')->nullable();
            $table->enum('period', ['day',  'month',  'year'])->nullable();
            $table->integer('interval')->nullable();

            $table->index('plan_id','fk_plans_periods_plans1_idx');

            $table->foreign('plan_id')
                ->references('id')->on('plans');

            $table->timestamps();

        });

        Schema::create('plans_features', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('name', 100)->nullable();
            $table->integer('value')->nullable();
            $table->boolean('consumible')->nullable();

            $table->index('plan_id','fk_plans_features_plans1_idx');

            $table->foreign('plan_id')
                ->references('id')->on('plans');

            $table->timestamps();

        });

        Schema::create('subscriptions', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            $table->string('subscriber_type', 200)->nullable();
            $table->integer('subscriber_id')->nullable();
            $table->integer('start_at')->nullable();
            $table->integer('end_at')->nullable();
            $table->integer('cancelled_at')->nullable();

            $table->index('plan_id','fk_subscriptions_plans1_idx');

            $table->foreign('plan_id')
                ->references('id')->on('plans');

            $table->timestamps();

        });

        Schema::create('plan_consumible', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('plan_features_id')->unsigned();
            $table->integer('available')->nullable();
            $table->integer('used')->nullable();
            $table->string('subscriber_type', 250)->nullable();
            $table->integer('subscriber_id')->nullable();

            $table->index('plan_features_id','fk_plan_consumible_plans_features1_idx');

            $table->foreign('plan_features_id')
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
        Schema::dropIfExists('plan_consumible');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans_features');
        Schema::dropIfExists('plans_periods');
        Schema::dropIfExists('plans');
    }
}
