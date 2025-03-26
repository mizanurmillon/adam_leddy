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
        <a href="{{ route('admin.instructors.create') }}" class="button-lg">Register New Instructor</a>
    </div>
    <div class="se--main-layout">
        <div class="page-title">Instructors</div>
        <div class="se--overview--layout">
            <div class="se--overview-cards-layout">
                <div class="se--overview--card--layout">
                    <p class="se--card-head">Instructor Signups</p>
                    <div class="se--overview-card">
                        <div class="se--over--card-row">
                            <div class="se--month-row">
                                <p Month class="se--card-pera">This Month</p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                    fill="none">
                                    <g clip-path="url(#clip0_306_7732)">
                                        <path
                                            d="M14.283 5.86502L14.2775 5.85959L8.6839 0.258625C8.63945 0.214422 8.59035 0.17515 8.53746 0.141492L8.3764 0.0536332L8.26658 0.0170141H8.18603C8.0626 -0.00567136 7.93606 -0.00567136 7.81263 0.0170141H7.65157L7.52709 0.0829078C7.45772 0.120589 7.3937 0.167369 7.33672 0.222005L1.72113 5.85959C1.34509 6.23265 1.34265 6.83994 1.71571 7.21598L1.72113 7.2214C2.10019 7.58306 2.69653 7.58306 3.07562 7.2214L6.42155 3.88278C6.5659 3.74125 6.79769 3.74351 6.93923 3.8879C7.00509 3.95505 7.04257 4.04499 7.04387 4.13905V15.0408C7.04384 15.5705 7.47321 16 8.00293 16C8.53266 16 8.96206 15.5707 8.96213 15.0409V4.13905C8.96498 3.9369 9.13116 3.77529 9.3333 3.77814C9.42734 3.77948 9.51729 3.81692 9.58445 3.88278L12.9158 7.2214C13.2958 7.58773 13.8975 7.58773 14.2776 7.2214C14.6536 6.84834 14.656 6.24106 14.283 5.86502Z"
                                            fill="#29FF65" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_306_7732">
                                            <rect width="16" height="16" fill="white"
                                                transform="translate(16 16) rotate(-180)" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </div>
                            <p class="se--card--number">24</p>
                        </div>
                        <div class="se--over--card-row">
                            <div class="se--month-row">
                                <p Month class="se--card-pera">All Time</p>
                            </div>
                            <p class="se--card--number">478</p>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <div class="se--category-section">
            <button class="bg-danger se-category-btn">
                All
            </button>
            <button class=" se-category-btn">
                Active
            </button>
            <button class=" se-category-btn">
                Blocked
            </button>
        </div>

        <div class="se--table--layout">
            <div class="se--table--row1">
                <p class="se--subtext">Instructors List</p>

            </div>
            <div class="se-table-container">
                <table class="se-table">
                    <thead class="se-thead">
                        <tr class="se-tr">
                            <th class="se-th">Name</th>
                            <th class="se-th">Watch Time</th>
                            <th class="se-th">Block/Unblock</th>
                            <th class="se-th">Upload Course Permission</th>
                            <th class="se-th"></th>
                        </tr>
                    </thead>
                    <tbody class="se-tbody">
                        @foreach ($instructors as $instructor)
                            <tr class="se-tr">
                                <td class="se-td">{{ $instructor->first_name }} {{ $instructor->last_name }}</td>
                                <td class="se-td">
                                    @php
                                        $totalMinutes = $instructor->instructor->courses->flatMap->courseWatches->sum(
                                            'watch_time',
                                        );
                                        $hours = floor($totalMinutes / 60);
                                        $minutes = $totalMinutes % 60;
                                    @endphp
                                    {{ $hours }}h {{ $minutes }}m
                                </td>
                                <td class="se-td">
                                    @if ($instructor->status == 'active')
                                        <button class="btn btn-success">Active</button>
                                    @else
                                        <button class="btn btn-danger">Blocked</button>
                                    @endif
                                </td>
                                <td class="se-td">
                                    <div class="form-check form-switch ">
                                        <input class="form-check-input py-lg-3 py-2 px-3 px-lg-4" type="checkbox"
                                            @if ($instructor->status == 'active') checked @endif role="switch"
                                            id="flexSwitchCheckDefault">
                                    </div>
                                </td>
                                <td class="se-td">
                                    <a href="{{ route('admin.instructors.details') }}"
                                        class="text-decoration-underline fw-bold text-white">View
                                        Details</a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>


            </div>
        </div>

        <div class="d-flex justify-content-end">
            <div class="se--category-section">
                @foreach ($instructors->getUrlRange(1, $instructors->lastPage()) as $page => $url)
                    <a href="{{ $url }}">
                        <button class="{{ $page == $instructors->currentPage() ? 'bg-danger' : '' }} se-category-btn">
                            {{ $page }}
                        </button>
                    </a>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
@endpush
