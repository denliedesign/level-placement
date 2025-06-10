@extends('layouts.app')
@section('content')
    <h1 class="text-center play">Level Placements 2025-2026</h1>
    <div class="container d-flex justify-content-center my-5 roboto">
        <table>
            <thead>
            <tr>
                <th class="px-4">First Name</th>
                <th class="px-4">Last Name</th>
                <th class="px-4">Email</th>
                <th class="px-4">Ballet</th>
                <th class="px-4">Jazz</th>
                <th class="px-4">Tap</th>
                <th class="px-4">Pointe</th>
                <th class="px-4">Acro</th>
            </tr>
            </thead>
            <tbody>
            @foreach($levels as $level)
                <tr>
                    <td class="px-4">{{ $level->first_name }}</td>
                    <td class="px-4">{{ $level->last_name }}</td>
                    <td class="px-4">{{ $level->email }}</td>
                    <td class="px-4">{{ $level->ballet }}</td>
                    <td class="px-4">{{ $level->jazz }}</td>
                    <td class="px-4">{{ $level->tap }}</td>
                    <td class="px-4">{{ $level->pointe }}</td>
                    <td class="px-4">{{ $level->acro }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
