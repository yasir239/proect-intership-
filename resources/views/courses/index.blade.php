@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Courses</h2>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Code</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $course)
            <tr>
                <td class="border px-4 py-2">{{ $course->id }}</td>
                <td class="border px-4 py-2">{{ $course->code }}</td>
                <td class="border px-4 py-2">{{ $course->name }}</td>
                <td class="border px-4 py-2">{{ $course->description }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
