@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-white py-10">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-end mb-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-xl shadow hover:bg-red-800 transition-all duration-150">العوده الى myqu</button>
            </form>
        </div>
        <h1 class="text-3xl font-extrabold mb-8 text-gray-800 text-center tracking-tight drop-shadow">جدول التسجيل</h1>
        <div class="flex flex-col md:flex-row gap-8">
            <!-- قائمة المقررات -->
            <div class="md:w-1/4 w-full bg-white border rounded-2xl shadow-lg p-5 mb-6 md:mb-0">
                <h2 class="text-gray-800 text-2xl font-bold mb-6 text-center">المقررات</h2>
                @foreach($courses as $course)
                    <div class="mb-4">
                        <button onclick="toggleSections('course-{{ $course->id }}')" class="w-full font-bold bg-gray-100 border border-gray-300 p-3 rounded-xl text-gray-800 shadow-sm text-center text-lg hover:bg-gray-200 transition-colors duration-150">
                            ({{ $course->code }})<br>
                            <span class="text-base">{{ $course->name }}</span>
                        </button>
                        <div id="course-{{ $course->id }}" class="ml-2 mt-2 hidden">
                            @foreach($course->sections as $section)
                                <button class="section-btn mt-2 px-3 py-2 rounded-xl border text-sm w-full text-right transition-all duration-150 {{ $section->is_closed ? 'border-red-200 bg-red-50 cursor-not-allowed opacity-75' : 'border-blue-200 bg-gray-50 hover:bg-blue-100 hover:scale-105' }} shadow-sm"
                                    data-section='@json($section)'
                                    data-course='@json($course)'
                                    {{ $section->is_closed ? 'disabled' : '' }}>
                                    {{ $section->id }} - {{ __($section->day) }}<br>
                                    <span class="text-xs">{{ $section->start_time }}-{{ $section->end_time }}</span>
                                    @if($section->is_closed)<span class="text-red-500 text-xs block">مغلقة</span>@endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- جدول الأسبوع -->
            <div class="md:w-3/4 w-full">
                <div class="flex flex-row justify-between items-center mb-4">
                    <h2 class="text-gray-800 text-2xl font-bold text-center">جدول الأسبوع</h2>
                    <button id="download-image" class="px-5 py-2 bg-gray-800 text-white rounded-xl shadow hover:bg-black transition-all duration-150">طباعة صورة للجدول</button>
                </div>
                <div class="overflow-x-auto rounded-2xl shadow-lg bg-white">
                    <table id="schedule-table-image" class="w-full border text-center rounded-2xl overflow-hidden bg-white">
                        <thead>
                            <tr class="bg-blue-50">
                                <th class="border p-3 text-lg">الوقت</th>
                                <th class="border p-3 text-lg">الأحد</th>
                                <th class="border p-3 text-lg">الاثنين</th>
                                <th class="border p-3 text-lg">الثلاثاء</th>
                                <th class="border p-3 text-lg">الأربعاء</th>
                                <th class="border p-3 text-lg">الخميس</th>
                            </tr>
                        </thead>
                        <tbody id="schedule-table">
                            @php
                                $days = ['Sunday'=>'الأحد','Monday'=>'الاثنين','Tuesday'=>'الثلاثاء','Wednesday'=>'الأربعاء','Thursday'=>'الخميس'];
                                $fixedTimeSlots = [
                                    ['start' => '08:00:00', 'end' => '10:00:00'],
                                    ['start' => '09:00:00', 'end' => '10:30:00'],
                                    ['start' => '11:00:00', 'end' => '12:30:00'],
                                    ['start' => '12:00:00', 'end' => '14:00:00'],
                                    ['start' => '13:00:00', 'end' => '14:30:00'],
                                ];
                            @endphp
                            @foreach($fixedTimeSlots as $slot)
                                <tr data-time="{{ $slot['start'] }}-{{ $slot['end'] }}">
                                    <td class="border p-3 text-base font-semibold bg-gray-50">{{ $slot['start'] }} - {{ $slot['end'] }}</td>
                                    @foreach($days as $eng=>$ar)
                                        <td class="border p-3 min-w-[110px] h-16 align-middle bg-white" data-day="{{ $eng }}"></td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script>
// Toggle sections visibility
function toggleSections(courseId) {
    const sectionsDiv = document.getElementById(courseId);
    const allSectionDivs = document.querySelectorAll('[id^="course-"]');
    
    // Close all other sections first
    allSectionDivs.forEach(div => {
        if (div.id !== courseId) {
            div.classList.add('hidden');
        }
    });
    
    // Toggle the clicked section
    sectionsDiv.classList.toggle('hidden');
}

window.onload = function() {
    // Clear any existing schedules from previous sessions
    localStorage.removeItem('currentSchedule');
    
    // Initialize empty schedule
    window.schedule = {};

    // Load registered sections from backend
    const registeredSections = @json($registeredSections ?? []);

    document.getElementById('download-image').onclick = function() {
        const table = document.getElementById('schedule-table-image');
        html2canvas(table).then(function(canvas) {
            const link = document.createElement('a');
            link.download = 'schedule.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    };

    // --- Interactive scheduling logic ---
    const schedule = {}; // { day: { time: {course, section} } }
    const days = ['Sunday','Monday','Tuesday','Wednesday','Thursday'];
    const sectionButtons = document.querySelectorAll('.section-btn');
    const table = document.getElementById('schedule-table');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Helper: Check for time conflict
    function hasConflict(day, start, end) {
        if (!schedule[day]) schedule[day] = {};
        
        // Convert times to minutes for easier comparison
        start = start.split(':').map(Number);
        end = end.split(':').map(Number);
        const startTime = start[0] * 60 + start[1];
        const endTime = end[0] * 60 + end[1];
        
        for (const slot in schedule[day]) {
            const [slotStart, slotEnd] = slot.split('-');
            const [sHour, sMin] = slotStart.split(':').map(Number);
            const [eHour, eMin] = slotEnd.split(':').map(Number);
            const slotStartTime = sHour * 60 + sMin;
            const slotEndTime = eHour * 60 + eMin;
            
            // Check if times overlap
            if (!(endTime <= slotStartTime || startTime >= slotEndTime)) {
                return true;
            }
        }
        return false;
    }

    // Helper: Add section to schedule (with optimistic update)
    function addSectionToSchedule(section, course) {
        const day = section.day;
        const start = section.start_time;
        const end = section.end_time;
        const slotKey = `${start}-${end}`;
        if (!schedule[day]) schedule[day] = {};

        // Optimistically update UI first
        schedule[day][slotKey] = {course, section};
        const cell = table.querySelector(`tr[data-time='${slotKey}'] td[data-day='${day}']`);
        if (cell) {
            cell.innerHTML = `<div class='bg-blue-200 rounded p-1 cursor-pointer remove-course' data-day='${day}' data-slot='${slotKey}'>${course.code}<br><span class='text-xs'>${section.id}</span></div>`;
        }

        // Then sync with backend
        fetch('/schedule/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ section_id: section.id })
        })
        .then(res => {
            if (!res.ok) {
                // If error, rollback the UI change
                delete schedule[day][slotKey];
                if (cell) cell.innerHTML = '';
                return res.json().then(data => { throw data; });
            }
            return res.json();
        })
        .catch(err => {
            alert(err.message || 'حدث خطأ أثناء إضافة المقرر.');
        });
    }

    // Helper: Remove section from schedule (with optimistic update)
    async function removeSectionFromSchedule(day, slotKey) {
        const section = schedule[day][slotKey]?.section;
        if (!section) return;

        // Store current state for potential rollback
        const oldState = schedule[day][slotKey];
        const cell = table.querySelector(`tr[data-time='${slotKey}'] td[data-day='${day}']`);
        const oldHtml = cell?.innerHTML;

        // Optimistically update UI first
        delete schedule[day][slotKey];
        if (cell) cell.innerHTML = '';

        try {
            const res = await fetch('/schedule/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ section_id: section.id })
            });

            if (!res.ok) {
                // If error, rollback the UI change
                schedule[day][slotKey] = oldState;
                if (cell) cell.innerHTML = oldHtml;
                throw new Error('فشل في حذف المقرر');
            }
        } catch (err) {
            // Rollback on network error
            schedule[day][slotKey] = oldState;
            if (cell) cell.innerHTML = oldHtml;
            alert(err.message || 'حدث خطأ أثناء حذف المقرر.');
        }
    }

    // Load and display registered sections
    if (registeredSections && Array.isArray(registeredSections)) {
        registeredSections.forEach(section => {
            if (!section || !section.course) return;
            
            const day = section.day;
            const start = section.start_time;
            const end = section.end_time;
            const slotKey = `${start}-${end}`;
            
            // Initialize the schedule object properly
            if (!schedule[day]) schedule[day] = {};
            schedule[day][slotKey] = {
                course: section.course,
                section: section
            };
            
            // Update the UI
            const cell = table.querySelector(`tr[data-time='${slotKey}'] td[data-day='${day}']`);
            if (cell) {
                cell.innerHTML = `<div class='bg-blue-200 rounded p-1 cursor-pointer remove-course' data-day='${day}' data-slot='${slotKey}'>${section.course.code}<br><span class='text-xs'>${section.id}</span></div>`;
            }
        });
    }

    // Section button click
    sectionButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const section = JSON.parse(this.getAttribute('data-section'));
            const course = JSON.parse(this.getAttribute('data-course'));
            
            // Prevent adding closed sections
            if (section.is_closed) {
                alert('هذا القسم مغلق ولا يمكن التسجيل فيه.');
                return;
            }

            const day = section.day;
            const start = section.start_time;
            const end = section.end_time;
            const slotKey = `${start}-${end}`;
            
            // Prevent adding if already scheduled
            if (schedule[day] && schedule[day][slotKey]) {
                alert('تمت إضافة هذا القسم بالفعل في هذا الوقت.');
                return;
            }
            // Prevent time conflict
            if (hasConflict(day, start, end)) {
                alert('يوجد تعارض في الوقت مع مقرر آخر.');
                return;
            }
            addSectionToSchedule(section, course);
        });
    });

    // Remove course from schedule on cell click
    table.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-course')) {
            const day = e.target.getAttribute('data-day');
            const slotKey = e.target.getAttribute('data-slot');
            removeSectionFromSchedule(day, slotKey);
        }
    });
};
</script>
@endsection
