@extends('backend.app')
@section('title', 'Users')
@push('style')
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiq.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiqResponsive.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
@section('content')
    <div class="page-header">
        <div class="desktop-sidebar-closer-container">
            <img class="desktop-sidebar-closer" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
        <div>
            <img class="menu-btn-mobile" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
        <!-- <a href="" class="button-lg">Register New Instructor</a> -->
    </div>

    <div class="se--main-layout">
        <div class="page-title">Courses </div>
        <div class="se--category-section">
            <!-- 'All' button to show courses from all categories -->
            <a href="javascript:void(0);" class="efg">
                <button  class="se-category-btn{{ empty(request('category')) || request('category') == 'undefined' ? ' se-active' : '' }}"
                    data-id="">
                    All
                </button>
            </a>

            @foreach ($categories as $category)
                <a class="efg" href="javascript:void(0);">
                    <button class="se-category-btn{{ request('category') == $category->id ? ' se-active' : '' }}"
                        data-id="{{ $category->id }}">
                        {{ $category->name }}
                    </button>
                </a>
            @endforeach
        </div>
        <div class=" se--course-table-layout">
            <!-- select menu -->
            <div class="se--course-table-layout">
                <!-- Select Menu -->
                <div class="se-select-layout">
                    <select class="se--select-menu" id="sortCourses">
                        <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Sort By Default
                        </option>
                        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Recently Added</option>
                        <option value="watch-high" {{ request('sort') == 'watch-high' ? 'selected' : '' }}>Watch Time (High
                            To Low)</option>
                        <option value="watch-low" {{ request('sort') == 'watch-low' ? 'selected' : '' }}>Watch Time (Low To
                            High)</option>
                    </select>
                </div>
                <div class="se-table-container">
                    <table class="se-table">
                        <thead class="se-thead">
                            <tr class="se-tr">
                                <th class="se-th">Name</th>
                                <th class="se-th">Instructor</th>
                                <th class="se-th">Watch Time</th>
                                <th class="se-th">Category</th>
                                <th class="se-th"></th>
                            </tr>
                        </thead>
                        <tbody class="se-tbody">

                            @foreach ($courses as $course)
                                <tr class="se-tr">
                                    <td class="se-td">{{ $course->title }}</td>
                                    <td class="se-td">{{ $instructorNames[$course->id] ?? 'N/A' }}</td>
                                    <td class="se-td">{{ gmdate('H:i:s', $course->total_watch_time) }}</td>
                                    <td class="se-td">{{ $course->category->name ?? 'N/A' }}</td>
                                    <td class="se-td">
                                        <a href="{{ route('admin.courses.content', $course->id) }}"
                                            class="se--view-btn">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="robi-pagination">
                    {{-- Previous Button --}}
                    @if ($courses->onFirstPage())
                        <span class="robi-page disabled">&laquo;</span>
                    @else
                        <a href="{{ $courses->appends(request()->query())->previousPageUrl() }}" class="robi-page">&laquo;</a>
                    @endif
                
                    @php
                        $start = max(1, $courses->currentPage() - 2);
                        $end = min($courses->lastPage(), $courses->currentPage() + 2);
                    @endphp
                
                    {{-- First Page --}}
                    @if ($start > 1)
                        <a href="{{ $courses->appends(request()->query())->url(1) }}" class="robi-page">1</a>
                    @endif
                
                    {{-- Dots before current range --}}
                    @if ($start > 2)
                        <span class="robi-dots">...</span>
                    @endif
                
                    {{-- Page Numbers --}}
                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page == $courses->currentPage())
                            <span class="robi-page active">{{ $page }}</span>
                        @else
                            <a href="{{ $courses->appends(request()->query())->url($page) }}" class="robi-page">{{ $page }}</a>
                        @endif
                    @endfor
                
                    {{-- Dots after current range --}}
                    @if ($end < $courses->lastPage() - 1)
                        <span class="robi-dots">...</span>
                    @endif
                
                    {{-- Last Page --}}
                    @if ($end < $courses->lastPage())
                        <a href="{{ $courses->appends(request()->query())->url($courses->lastPage()) }}" class="robi-page">{{ $courses->lastPage() }}</a>
                    @endif
                
                    {{-- Next Button --}}
                    @if ($courses->hasMorePages())
                        <a href="{{ $courses->appends(request()->query())->nextPageUrl() }}" class="robi-page">&raquo;</a>
                    @else
                        <span class="robi-page disabled">&raquo;</span>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        document.getElementById('sortCourses').addEventListener('change', function() {
            window.location.href = '?sort=' + this.value;
        });
    </script>
    <script>
        $('#sortCourses').on('change', function() {
            let search_category = {{ request('category') ?? 'undefined' }};
            let page = {{ request('page') ?? 1 }};
            let sort_by = $('#sortCourses').val();
            let url = "{{ route('admin.courses.index') }}?" + "category=" + search_category + "&sort=" + sort_by + "&page=" + page;
            window.location.href = url;
            // alert(search_category);
        });
    </script>
    <script>
        let list = document.querySelectorAll(".efg");
        let list_arr = Array.from(list);
        list_arr.map(item => {
            item.addEventListener('click', function(e) {
                if (e.target.className == 'se-category-btn') {
                    let search_category = e.target.dataset.id;
                    let sort_by = $('#sortCourses').val();
                    let page = {{ request('page') ?? 1 }};
                    let url = "{{ route('admin.courses.index') }}?" + "category=" + search_category + "&sort=" +
                        sort_by  + "&page=" + page;
                    window.location.href = url;
                    // alert(search_category);
                }
            })
        });
    </script>
@endpush
