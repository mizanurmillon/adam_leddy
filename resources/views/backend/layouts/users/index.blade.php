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
    <div class="page-title">User</div>
    <div class="robi-user-signup">
        <p>User Sign up</p>
        <div class="robi-user-signup-total">
            <div class="robi-user-signup-total-this-month">
                <p class="">This Month</p>
                @php
                    $arrowColor = $monthlyUsersCount > 0 ? '#29FF65' : '#FF0000'; // green or red
                    $rotation = $monthlyUsersCount > 0 ? 'rotate(0)' : 'rotate(180deg)'; // up or down
                @endphp

                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                    style="transform: {{ $rotation }};" viewBox="0 0 16 16" fill="none">
                    <g clip-path="url(#clip0_306_7732)">
                        <path
                            d="M14.283 5.86502L14.2775 5.85959L8.6839 0.258625C8.63945 0.214422 8.59035 0.17515 8.53746 0.141492L8.3764 0.0536332L8.26658 0.0170141H8.18603C8.0626 -0.00567136 7.93606 -0.00567136 7.81263 0.0170141H7.65157L7.52709 0.0829078C7.45772 0.120589 7.3937 0.167369 7.33672 0.222005L1.72113 5.85959C1.34509 6.23265 1.34265 6.83994 1.71571 7.21598L1.72113 7.2214C2.10019 7.58306 2.69653 7.58306 3.07562 7.2214L6.42155 3.88278C6.5659 3.74125 6.79769 3.74351 6.93923 3.8879C7.00509 3.95505 7.04257 4.04499 7.04387 4.13905V15.0408C7.04384 15.5705 7.47321 16 8.00293 16C8.53266 16 8.96206 15.5707 8.96213 15.0409V4.13905C8.96498 3.9369 9.13116 3.77529 9.3333 3.77814C9.42734 3.77948 9.51729 3.81692 9.58445 3.88278L12.9158 7.2214C13.2958 7.58773 13.8975 7.58773 14.2776 7.2214C14.6536 6.84834 14.656 6.24106 14.283 5.86502Z"
                            fill="{{ $arrowColor }}" />
                    </g>
                    <defs>
                        <clipPath id="clip0_306_7732">
                            <rect width="16" height="16" fill="white" transform="translate(16 16) rotate(-180)" />
                        </clipPath>
                    </defs>
                </svg>

            </div>
            <h1>{{ $monthlyUsersCount }}</h1>
            <p>All Time</p>
            <h1>{{ $users->total() }}</h1>
        </div>
        <div class="robi-pagination">
            @if ($users->onFirstPage())
                <a href="{{ $users->previousPageUrl() }}" class="robi-page">&laquo;</a>
            @else
                <a href="{{ $users->previousPageUrl() }}" class="robi-page">&laquo;</a>
            @endif

            @php
                $start = max(1, $users->currentPage() - 2);
                $end = min($users->lastPage(), $users->currentPage() + 2);
            @endphp

            @if ($start > 1)
                <button class="robi-page">1</button>
            @endif

            @if ($start > 2)
                <span class="robi-dots">...</span>
            @endif

            @for ($page = $start; $page <= $end; $page++)
                @if ($page == $users->currentPage())
                    <button class="robi-page active">{{ $page }}</button>
                @else
                    <a href="{{ $users->url($page) }}" class="robi-page">{{ $page }}</a>
                @endif
            @endfor

            @if ($end < $users->lastPage() - 1)
                <span class="robi-dots">...</span>
            @endif

            @if ($end < $users->lastPage())
                <button class="robi-page">{{ $users->lastPage() }}</button>
            @endif

            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}" class="robi-page">&raquo;</a>
            @endif
        </div>
    </div>
    <!-- Table start -->
    <div class="robi-container">
        <table class="robi-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $user->first_name }}</td>
                        <td>{{ $user->last_name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('script')
@endpush
