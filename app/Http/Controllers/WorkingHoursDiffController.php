<?php

namespace App\Http\Controllers;

use App\Models\WorkingHoursDiff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WorkingHoursDiffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workingHoursDiffs = WorkingHoursDiff::with('employee')->get();

        return response()->json(['workingHoursDiffs' => $workingHoursDiffs]);

        // Log::debug($workingHoursDiffs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $workingHoursDiffCheck = WorkingHoursDiff::where('employee_id', $request->workingHoursDiffData['employee_id']);
        $workingHoursDiffCheck->where('month', $request->workingHoursDiffData['month']);
        $workingHoursDiffCheck->where('year', $request->workingHoursDiffData['year']);
        $workingHoursDiff = $workingHoursDiffCheck->get();

        if ($workingHoursDiff->isEmpty()) {
            $new_working_hours_diff = new WorkingHoursDiff();
            $new_working_hours_diff->employee_id = $request->workingHoursDiffData['employee_id'];
            $new_working_hours_diff->month = $request->workingHoursDiffData['month'];
            $new_working_hours_diff->year = $request->workingHoursDiffData['year'];
            $new_working_hours_diff->diff = $request->workingHoursDiffData['diff'];
            $new_working_hours_diff->save();

            $working_hours_diff = WorkingHoursDiff::with('employee')->where('id', $new_working_hours_diff->id)->first();
            // Log::debug($working_hours_diff);

            Log::debug($working_hours_diff);
            return ['new_working_hours_diff' => $working_hours_diff];
        } else {

            $update_working_hours_diff = WorkingHoursDiff::where('id', $workingHoursDiff[0]->id)->first();

            $update_working_hours_diff->diff = $request->workingHoursDiffData['diff'];
            $update_working_hours_diff->save();

            $working_hours_diff = WorkingHoursDiff::with('employee')->where('id', $update_working_hours_diff->id)->first();

            Log::debug($working_hours_diff);
            return ['new_working_hours_diff' => $working_hours_diff];
            // Log::debug($update_workingHoursDiff);
            // Log::debug('NOT Empty');
        };
        // $workingHoursDiff = new WorkingHoursDiff();
        // Log::debug($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WorkingHoursDiff  $workingHoursDiff
     * @return \Illuminate\Http\Response
     */
    public function show(WorkingHoursDiff $workingHoursDiff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WorkingHoursDiff  $workingHoursDiff
     * @return \Illuminate\Http\Response
     */
    public function edit(WorkingHoursDiff $workingHoursDiff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WorkingHoursDiff  $workingHoursDiff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WorkingHoursDiff $workingHoursDiff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WorkingHoursDiff  $workingHoursDiff
     * @return \Illuminate\Http\Response
     */
    public function destroy(WorkingHoursDiff $workingHoursDiff)
    {
        //
    }
}
