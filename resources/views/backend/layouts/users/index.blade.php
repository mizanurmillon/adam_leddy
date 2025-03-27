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
                <img src="{{ asset('backend/assets/images/arrowUP.png') }}" alt="">

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
