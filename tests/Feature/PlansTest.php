<?php
namespace Orchestra\Testbench\Tests\Databases;
use Carbon\Carbon;
use Sagitarius29\LaravelSubscriptions\Entities\Plan;
use Sagitarius29\LaravelSubscriptions\Tests\Entities\User;
use Sagitarius29\LaravelSubscriptions\Tests\TestCase;

class PlansTest extends TestCase
{
    /** @test */
    public function it_runs_the_migrations()
    {
        $now = Carbon::now();
        \DB::table('users')->insert([
            'name' => 'Orchestra',
            'email' => 'hello@orchestraplatform.com',
            'password' => \Hash::make('456'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
        $users = \DB::table('users')->where('id', '=', 1)->first();
        $this->assertEquals('hello@orchestraplatform.com', $users->email);
        $this->assertTrue(\Hash::check('456', $users->password));

        User::create([
            'email' => 'gerardo@email.dev',
            'name' => 'Gerardo',
            'password' => 'password'
        ]);

        factory(Plan::class)->create();

        dd(\DB::table('plans')->get()->toArray());
    }
}
