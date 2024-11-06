<?php

namespace App\Services\Modules;

use App\Models\Unit;

class UnitService
{

    public function saveUnit(Unit $unit, $request): Unit
    {
        $unit->name = $request->name;
        $unit->status = $request->status;
        $unit->save();

        return $unit;
    }
}