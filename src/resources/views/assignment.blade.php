<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WEG Case Study</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200">

<div class="max-w-7xl mx-auto py-4">
    @if($result['error'] ?? false)
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">{{ $result['error'] }}</strong>
        </div>
    @else
        <div class="flex items-center justify-between">
            <h1>Assignments</h1>
            <form action="{{route('assignment')}}">
                @csrf
                Plan tasks with <input type="number" name="working_hours" value="{{$workingHours}}"> hours of work using
                <select name="strategy">
                    @foreach($strategies as $strategy)
                        <option value="{{urlencode(base64_encode($strategy))}}"
                                @if($strategy == $selectedStrategy) selected @endif>{{$strategy}}</option>

                    @endforeach
                </select>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Generate</button>
            </form>
        </div>

        @foreach($result as $plan)
            <div class="bg-white rounded p-2 shadow my-4">
               <span class="text-xl"> {{$plan['developer']->name}} - Seniority Level: {{$plan['developer']->seniority}}</span>
                <hr>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($plan['weeks'] as $week)
                        <div class="p-2 @if($loop->iteration % 5 == 0 || $loop->iteration == 1) border-0 @else border-l @endif">
                            <div class="flex items-center justify-between border-b px-2">
                                <b class="text-lg">{{$week['name']}}</b>
                                <small class="font-regular">{{$week['effort']}} hours occupied</small>
                            </div>
                            <div class="px-4">
                                @foreach($week['tasks'] as $task)
                                    <div class="border-b py-2">
                                        <div class="block">{{$task->name}}</div>
                                        <div class="flex items-center justify-between">
                                            <small class="font-regular">Estimated Duration</small>
                                            <small class="font-regular">{{$task->estimated_duration_in_hours}} hrs</small>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <small class="font-regular">Difficulty</small>
                                            <small class="font-regular">{{$task->difficulty}}</small>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <small class="font-regular">Effort</small>
                                            <small class="font-regular">{{$task->effort}} hrs</small>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>

</body>
</html>