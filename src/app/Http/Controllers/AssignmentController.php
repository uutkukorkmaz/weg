<?php

namespace App\Http\Controllers;

use App\Managers\TaskManager;
use App\Models\Developer;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __invoke(Request $request, TaskManager $manager)
    {
        /**
         * **BEST PRACTICE**
         *  1. Use Service layer to simplify controller
         *  2. Use API endpoint to fetch assignment dataset
         */
        $defaultStrategy = urlencode(base64_encode(config('assignment.default_strategy')));
        $strategy = base64_decode(urldecode($request->get('strategy', $defaultStrategy)));

        try {
            $workingHours = $request->get('working_hours', config('assignment.developer.working_hours'));
            config(['assignment.developer.working_hours' => $workingHours]);
            $tasks = $manager->massAssignUsing($strategy);
        } catch (\Exception $e) {
            $tasks = false;
            $result = [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ];
        }
        if ($tasks) {
            $result = [];
            $plan = $manager->getPlan();
            $developers = Developer::whereIn('id', $plan->keys()->toArray())->get()->groupBy('id');
            foreach ($manager->getPlan() as $developer => $weeks) {
                $iteration = ['developer' => $developers->get($developer)->first()];
                foreach ($weeks as $week => $tasks) {
                    $weekCount = $week + 1;
                    $iteration['weeks'][$week] = [
                        'name' => 'Week '.$weekCount,
                        'tasks' => [],
                        'effort' => $tasks->sum('effort')
                    ];
                    foreach ($tasks as $task) {
                        $iteration['weeks'][$week]['tasks'][] = $task;
                    }
                }
                $result[] = $iteration;
            }
        }

        return view(
            'assignment',
            [
                'result' => $result,
                'workingHours' => $workingHours,
                'selectedStrategy' => $strategy,
                'strategies' => $manager->getAllStrategies()
            ]
        );
    }
}
