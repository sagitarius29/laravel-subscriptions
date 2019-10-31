<?php

namespace Sagitarius29\LaravelSubscriptions\Traits;

use Illuminate\Database\Eloquent\Model;
use Sagitarius29\LaravelSubscriptions\Contracts\PlanIntervalContract;

trait HasSingleInterval
{
    public function setInterval(Model $interval): self
    {
        $intervalLoaded = $this->getInterval();

        if ($intervalLoaded == null) {
            $this->intervals()->save($interval);

            return $this;
        }

        $intervalLoaded->fill($interval->toArray());
        $intervalLoaded->save();

        return $this;
    }

    public function getInterval(): ?PlanIntervalContract
    {
        return $this->intervals()->first();
    }
}
