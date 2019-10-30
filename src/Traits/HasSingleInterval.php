<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanIntervalContract;

trait HasSingleInterval
{
    public function setInterval(Model $interval): void
    {
        $intervalLoaded = $this->getInterval();

        if ($intervalLoaded == null) {
            $this->intervals()->save($interval);

            return;
        }

        $intervalLoaded->fill($interval->toArray());
        $intervalLoaded->save();
    }

    public function getInterval(): ?PlanIntervalContract
    {
        return $this->intervals()->first();
    }
}
