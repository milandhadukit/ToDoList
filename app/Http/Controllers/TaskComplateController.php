<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\TaskComplate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Input;

class TaskComplateController extends Controller
{
    public function complateTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'task_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            if (empty(Auth::user())) {
                return $this->wrongPass('Sorry', 'Plz Login');
            }
            $dateToday = Carbon::now();
            $submitTask = [
                'user_id' => auth()->user()->id,
                'date' => $dateToday->format('Y-m-d'),
                'task_id' => json_encode($request->task_id),
            ];

            TaskComplate::create($submitTask);
            return $this->sendResponse('success', 'successfully Submit');
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function searchTaskDate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date_format:Y-m-d',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }
            if (!empty(Auth::user())) {
                $dateToday = Carbon::now();

                $inputDate = $request->date;

                $date = TaskComplate::where('date', '=', $request->date)
                    ->where('user_id', '=', auth()->user()->id)
                    ->first();

                if (empty($date)) {
                    return $this->wrongPass('Sorry', 'No Data Found');
                }

                $taskId = TaskComplate::select('task_id')
                    ->where('date', '=', $inputDate)
                    ->get();

                $decode = json_decode($taskId[0]->task_id, true);
                $get = data_get($decode, '*.task_id');
                $names = Task::select('name')
                    ->whereIn('id', $get)
                    ->get()
                    ->toArray();
                return $this->sendResponse('success', $names);
            }

            return $this->wrongPass('Sorry', 'Plz Login');
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function searchToDo(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date_format:Y-m-d',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            if (!empty(Auth::user())) {
                $dateToday = Carbon::now();

                $inputDate = $request->date;

                // $date = TaskComplate::where('date', '=', $request->date)
                //     ->where('user_id', '=', auth()->user()->id)
                //     ->first();

                // if (empty($date)) {
                //     return $this->wrongPass('Sorry', 'No Data Found');
                // }
                // ->where('date','>=','%' . $inputDate . '%')
                // \DB::enableQueryLog();
                // //$dateAll = TaskComplate::select('date','task_id')->where('date', '>=', $inputDate)->get();
                // $dateAll = \DB::select("select `date`, `task_id` from `task_complates` where `date` >= ? AND date = ?",[$inputDate,$inputDate]);
                // dd(\DB::getQueryLog());

                # Enter date and date field data get
                $dateAll = TaskComplate::select('date', 'task_id')
                    ->where('date', '>=', $inputDate)
                    ->where('user_id', '=', auth()->user()->id)
                    ->get();

                #load data from Task table
                $getNameById = Task::select('name', 'id')
                    ->get()
                    ->pluck('name', 'id')
                    ->toArray();

                #init output
                $returnOut = [];

                #start looping logic
                foreach ($dateAll as $value) {
                    $decode = json_decode($value->task_id, true);
                    $get = data_get($decode, '*.task_id');

                    foreach ($get as $item) {
                        #matching id and get name
                        $foudnName = isset($getNameById[$item])
                            ? $getNameById[$item]
                            : null;

                        #if found then
                        if ($foudnName != null) {
                            #push found value and set output
                            $returnOut[] = $foudnName;
                        }
                    }
                }

                return $this->sendResponse('success', $returnOut);
            }

            return $this->wrongPass('Sorry', 'Plz Login');
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function updateCompalateTask(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'task_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            if (empty(Auth::user())) {
                return $this->wrongPass('Sorry', 'Plz Login');
            }
            $dateToday = Carbon::now();
            $updateData = TaskComplate::find($id);

            $submitedTaskUpdate = [
                'user_id' => auth()->user()->id,
                'date' => $dateToday->format('Y-m-d'),
                'task_id' => json_encode($request->task_id),
            ];

            $updateData->update($submitedTaskUpdate);
            return $this->sendResponse('success', 'successfully Update');
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }

    
}
