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
        <p>User Signups</p>
        <div class="robi-user-signup-total">
            <div class="robi-user-signup-total-this-month">
                <p class="">This Month</p>
                <img src="{{ 'backend/assets/images/arrowUP.png' }}" alt="">

            </div>
            <h1>578</h1>
            <p>All Time</p>
            <h1>9382</h1>
        </div>
        <div class="robi-pagination">
            <button class="robi-page active">1</button>
            <button class="robi-page">2</button>
            <button class="robi-page">3</button>
            <span class="robi-dots">...</span>
            <button class="robi-page">78</button>
            <button class="robi-page">79</button>
            <button class="robi-page">79</button>
            <button class="robi-page">80</button>
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
                <tr>
                    <td>1</td>
                    <td>John</td>
                    <td>Doe</td>
                    <td>john.doe@example.com</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Jane</td>
                    <td>Smith</td>
                    <td>jane.smith@example.com</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Michael</td>
                    <td>Johnson</td>
                    <td>michael.johnson@example.com</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Emily</td>
                    <td>Brown</td>
                    <td>emily.brown@example.com</td>
                </tr>
                <tr>
                    <td>7</td>
                    <td>Christopher</td>
                    <td>Miller</td>
                    <td>christopher.miller@example.com</td>
                </tr>
                <tr>
                    <td>8</td>
                    <td>Amanda</td>
                    <td>Wilson</td>
                    <td>amanda.wilson@example.com</td>
                </tr>
                <tr>
                    <td>9</td>
                    <td>Ryan</td>
                    <td>Moore</td>
                    <td>ryan.moore@example.com</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection

@push('script')
    
@endpush
