@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Sections</h2>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Course ID</th>
                <th class="border px-4 py-2">Day</th>
                <th class="border px-4 py-2">Start Time</th>
                <th class="border px-4 py-2">End Time</th>
                <th class="border px-4 py-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sections as $section)
            <tr>
                <td class="border px-4 py-2">{{ $section->id }}</td>
                <td class="border px-4 py-2">{{ $section->course_id }}</td>
                <td class="border px-4 py-2">{{ $section->day }}</td>
                <td class="border px-4 py-2">{{ $section->start_time }}</td>
                <td class="border px-4 py-2">{{ $section->end_time }}</td>
                <td class="border px-4 py-2">{{ $section->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
