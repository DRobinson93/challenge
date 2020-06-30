
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
</head>
<body>
<div id="app">
    <div class="flex flex-wrap -mx-1 lg:-mx-4">
        @foreach ($teams as $team)
            <div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/4">

                <div class="flex flex-col items-center justify-center bg-white p-4 shadow rounded-lg">

                    <h2 class="mt-1 font-bold text-xl">
                        {{$team['name']}}
                        <span type="button" class="rounded-full px-4 bg-blue-600 text-white p-2 rounded leading-none">
                            {{$team['rank']}}
                        </span>
                    </h2>

                    <div class="text-xs text-gray-500 text-center mt-3">
                        <table class="table-auto">
                            <thead>
                            <tr>
                                <th class="px-4 py-2">Player</th>
                                <th class="px-4 py-2">Position</th>
                                <th class="px-4 py-2">Rank</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($team['users'] as $user)
                                <tr>
                                    <td class="border px-4 py-2">{{$user->first_name}} {{$user->last_name}}</td>
                                    <td class="border px-4 py-2">{{$user->can_play_goalie ? 'Goalie' : 'Player'}}</td>
                                    <td class="border px-4 py-2">{{$user->ranking}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Scripts -->
<script src="{{ mix('js/app.js') }}"></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.0.1/dist/alpine.js" defer></script>
</body>
