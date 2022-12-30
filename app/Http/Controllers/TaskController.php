<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use App\Models\Task;
use Auth;

class TaskController extends Controller
{
    //
    public function addTask(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            if (empty(Auth::user())) {
                return $this->wrongPass('Sorry', 'Plz Login');
            }
            $authUser = Auth::user()->role; 
            if ($authUser == 'Admin') {
                $addTask = [
                    'name' => $request->name,
                ];

                Task::create($addTask);
                return $this->sendResponse('success', 'successfully Add');
            } else {
                return $this->wrongPass('Sorry', ' Sorry Not Access');
            }
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }


    public function deleteTask($id)
    {
        try {
        
            if (empty(Auth::user())) {
                return $this->wrongPass('Sorry', 'Plz Login');
            }
            $authUser = Auth::user()->role; 
            if ($authUser == 'Admin') {

                $taskDelete=Task::find($id);
                $taskDelete->delete();

                return $this->sendResponse('success', 'successfully Delete');
            } else {
                return $this->wrongPass('Sorry', ' Sorry Not Access');
            }
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function updateTask(Request $request,$id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->toJson(), 400);
            }

            if (empty(Auth::user())) {
                return $this->wrongPass('Sorry', 'Plz Login');
            }
            $authUser = Auth::user()->role; 
            if ($authUser == 'Admin') {
                $updateTask = [
                    'name' => $request->name,
                ];

                $updateData=Task::find($id);
                $updateData->update($updateTask);
                return $this->sendResponse('success', 'successfully Update');
            } else {
                return $this->wrongPass('Sorry', ' Sorry Not Access');
            }
        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }

    public function viewTask()
    {
        try {
        
            if (empty(Auth::user())) {
                return $this->wrongPass('Sorry', 'Plz Login');
            }
            $authUser = Auth::user()->role; 
           
            $taskView=Task::all();
            return $this->sendResponse('success', $taskView);


        } catch (\Exception $e) {
            return $this->sendError('error', $e->getMessage());
        }
    }


 



}
