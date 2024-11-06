<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Models\Activity;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ActivityLogController extends BackendController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware(['permission:activity-log'])->only('index');
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        $this->data['activities'] = Activity::latest()->get();

        return view('backend.activity-log.index', $this->data);
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function show(int $id)
    {
        $this->data['activity'] = Activity::findOrfail($id);

        $this->convertActivityChanges();

        return view('backend.activity-log.show', $this->data);
    }

    /**
     * @return void
     */
    public function clear()
    {
        if(auth()->id() == 1) {
            Activity::truncate();
            return redirect(route('admin.activity-log.index'))->withSuccess('The activity log clear successfully.');
        }
        abort(404);
    }

    public function convertActivityChanges() {
        $activityChanges = $this->data['activity']->changes;

        $i = 0;
        $activityChangeArray = [];
        if(!blank($activityChanges)) {
            foreach ($activityChanges['old'] as $oldActivityKey => $oldActivity) {
                if(strpos($oldActivityKey, '_at')) {
                    continue;
                }

                $activityChangeArray[$i]['key'] = $oldActivityKey;
                $activityChangeArray[$i]['old'] = $oldActivity;
                $activityChangeArray[$i]['new'] = $activityChanges['attributes'][$oldActivityKey] ?? '';

                $i++;
            }
        }
        $this->data['activityChanges'] = $activityChangeArray;
    }

}
