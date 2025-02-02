<?php

namespace App\Http\Controllers;

use App\Models\Duty;
use App\Models\Employee;
use App\Models\Preference;
use App\Models\Shift;
use App\Models\Wish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DutyController extends Controller
{
    public function overview()
    {
        $employees = Employee::with('qualification')->get();

        return response()->json(['employees' => $employees]);
    }

    public function getDutiesData(Request $request, $year, $month, $employee_id)
    {
        $duties_db = Duty::with('shift');
        $duties_db->where('month', $month);
        $duties_db->where('year', $year);
        $duties_db->where('employee_id', $employee_id);
        $duties = $duties_db->get();

        return ['duties' => $duties];
    }

    public function getAllDutiesData(Request $request, $year, $month)
    {
        $duties_db = Duty::with('shift.shift_type');
        $duties_db->where('month', $month);
        $duties_db->where('year', $year);
        $duties = $duties_db->get();

        return ['duties' => $duties];
    }

    public function show(Request $request, $day, $month, $year, $employee_id)
    {
        $duty_check = Duty::where('employee_id', $request->employee_id);
        $duty_check->where('day', $request->day);
        $duty_check->where('month', $request->month);
        $duty_check->where('year', $request->year);

        $duty = $duty_check->get()->first();

        $shift_check = Shift::where('id', $duty->shift_id);
        $shift = $shift_check->get()->first();

        return ['shift' => $shift];
    }

    public function update(Request $request)
    {
        if ($request->dutyData['value'] !== null) {
            $shift_check = Shift::where('abrv', $request->dutyData['value']);
            $request_shift = $shift_check->get();

            if ($request_shift->isEmpty()) {
                return response()->json(['exception' => 'Schicht mit diesem Kürzel nicht gefunden.'], 404);
                // return 'Keine Schicht mit dieser Abkürzung!';
            }
        }

        $duty_check = Duty::where('employee_id', $request->dutyData['employee_id']);
        $duty_check->where('day', $request->dutyData['day']);
        $duty_check->where('month', $request->dutyData['month']);
        $duty_check->where('year', $request->dutyData['year']);
        $duty = $duty_check->get();

        if ($duty->isEmpty()) {
            $new_duty = new Duty();
            $new_duty->employee_id = $request->dutyData['employee_id'];
            $new_duty->shift_id = $request_shift[0]->id;
            $new_duty->day = $request->dutyData['day'];
            $new_duty->month = $request->dutyData['month'];
            $new_duty->year = $request->dutyData['year'];

            $wish_check = Wish::where('employee_id', $request->dutyData['employee_id']);
            $wish_check->where('day', $request->dutyData['day']);
            $wish_check->where('month', $request->dutyData['month']);
            $wish_check->where('year', $request->dutyData['year']);
            $wish = $wish_check->get();

            $preference_check = Preference::where('employee_id', $request->dutyData['employee_id']);
            $preference_check->where('shift_id', $request_shift[0]->id);
            $preference = $preference_check->get();

            if ($wish->isEmpty()) {
                $new_duty->wish_injury = 0;
            } else if ($wish[0]->shift_id == $request_shift[0]->id) {
                $new_duty->wish_injury = 0;
            } else {
                $new_duty->wish_injury = 1;
            }

            if ($preference->isEmpty()) {
                $new_duty->preference_injury = 1;
            } else if ($preference[0]->shift_id == $request_shift[0]->id) {
                $new_duty->preference_injury = 0;
            } else {
                $new_duty->preference_injury = 1;
            }

            // $new_duty->preference_injury = false;
            $new_duty->save();

            $duty = Duty::with('shift.shift_type')->where('id', $new_duty->id)->first();

            return ['new_duty' => $duty];
            // NEUE DUTY ANGELEGT
        } else if ($duty[0]->shift_id != $request_shift[0]->id) {
            $update_duty = Duty::where('id', $duty[0]->id)->first();
            $update_duty->shift_id = $request_shift[0]->id;

            $wish_check = Wish::where('employee_id', $duty[0]->employee_id);
            $wish_check->where('day', $duty[0]->day);
            $wish_check->where('month', $duty[0]->month);
            $wish_check->where('year', $duty[0]->year);
            $wish = $wish_check->first();

            if ($wish != null) {
                if ($wish->shift_id === $update_duty->shift_id) {
                    $update_duty->wish_injury = false;
                } else {
                    $update_duty->wish_injury = true;
                }
            }

            $preference_check = Preference::where('employee_id', $request->dutyData['employee_id']);
            $preference_check->where('shift_id', $request_shift[0]->id);
            $preference = $preference_check->get();

            if ($preference->isEmpty()) {
                $update_duty->preference_injury = 1;
            } else if ($preference[0]->shift_id == $request_shift[0]->id) {
                $update_duty->preference_injury = 0;
            } else {
                $update_duty->preference_injury = 1;
            }

            $update_duty->save();
            $duty = Duty::with('shift.shift_type')->where('id', $update_duty->id)->first();

            return ['new_duty' => $duty];
            // DUTY BEARBEITET;
        } else {
            return ['new_duty' => $duty];
            // KEINE ÄNDERUNG';
        }
    }

    public function showDutiesByShiftTypeAndDate(int $shift_type_id, $day, $month, $year)
    {
        $duties_db = Duty::with('shift.shift_type');
        $duties_db->where('day', $day);
        $duties_db->where('month', $month);
        $duties_db->where('year', $year);
        $duties = $duties_db->get();

        $duties = $duties->where('shift.shift_type_id', $shift_type_id);

        return ['duties' => $duties];
    }

    public function delete(Request $request, Duty $duty)
    {
        // Log::debug('DASDASDASD');
        // Log::debug($request);


        $duty_check = Duty::where('employee_id', $request->dutyData['employee_id']);
        $duty_check->where('day', $request->dutyData['day']);
        $duty_check->where('month', $request->dutyData['month']);
        $duty_check->where('year', $request->dutyData['year']);

        $deleted_duty = $duty_check->first();


        $duty_check->delete();


        return ['deleted_duty' => $deleted_duty];
    }
}
