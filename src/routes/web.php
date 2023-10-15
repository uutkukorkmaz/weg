<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/',\App\Http\Controllers\AssignmentController::class)->name('assignment');

//Route::get('/', function () {
//    $manager = new \App\Managers\TaskManager();
//
//    $manager->massAssignUsing(\App\Strategies\QualityStrategy::class);
//    $result = [];
//    foreach ($manager->getPlan() as $developer => $weeks) {
//        $iteration = ['developer' => $developer];
//        foreach ($weeks as $week => $tasks) {
//            $weekCount = $week + 1;
//            $iteration['weeks'][$week] = [
//                'name' => 'Week '.$weekCount,
//                'tasks' => [],
//                'effort' => $tasks->sum('estimated_duration_in_hours')
//            ];
//            foreach ($tasks as $task) {
//                $iteration['weeks'][$week]['tasks'][] = $task->toArray();
//            }
//        }
//        $result[] = $iteration;
//    }
//
//    return $result;
//});
