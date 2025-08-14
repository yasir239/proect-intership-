@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h2 class="text-2xl font-bold mb-4">Students</h2>
    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td class="border px-4 py-2">{{ $student->id }}</td>
                <td class="border px-4 py-2">{{ $student->name }}</td>
                <td class="border px-4 py-2">{{ $student->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
