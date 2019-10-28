<?php


namespace Sagitarius29\LaravelSubscriptions;


use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\GroupContract;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanContract;

abstract class Plan extends Model implements PlanContract
{
    protected $table = 'plans';

    protected $fillable = [
        'name', 'description', 'free_days', 'sort_order', 'is_active', 'is_default'
    ];

    public function features()
    {
        return $this->hasMany(config('subscriptions.entities.plan_feature'));
    }

    public function prices()
    {
        return $this->hasMany(config('subscriptions.entities.plan_price'), 'plan_id')->orderBy('amount');
    }

    public function subscriptions()
    {
        return $this->hasMany(config('subscriptions.entities.subscription'));
    }

    public function isDefault(): bool
    {
        return $this->is_default;
    }

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isFree(): bool
    {
        return $this->prices()->count() == 0;
    }

    public static function create(
        string $name, string $description, int $free_days, int $sort_order, bool $is_active = false, bool $is_default = false
    ): Model
    {
        $attributes = [
            'name'          => $name,
            'description'   => $description,
            'free_days'     => $free_days,
            'sort_order'    => $sort_order,
            'is_active'     => $is_active,
            'is_default'    => $is_default
        ];

        $calledClass = get_called_class();

        $plan = new $calledClass($attributes);
        $plan->save();
        return $plan;
    }

    public function setFree()
    {
        $this->prices()->delete();
    }

    public function myGroup(): ?GroupContract
    {
        return empty($this->group) ? null : new $this->group;
    }

    public function toGroup(GroupContract $group): void
    {
        // TODO: Implement toGroup() method.
    }
}