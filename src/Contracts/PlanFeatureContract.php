<?php

namespace Sagitarius29\LaravelSubscriptions\Contracts;

use Illuminate\Database\Eloquent\Model;

interface PlanFeatureContract
{

    public function plan();

    public function getCode(): string;

    public function getValue();
}
