@extends('backend.app')
@section('title', 'Dashboard')
@section('content')
<div class="page-header">
    <!-- sidebar desktop closer start -->
    <div class="desktop-sidebar-closer-container">
        <img class="desktop-sidebar-closer" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
    </div>
    <!-- sidebar desktop closer end -->
    <div>
        <img class="menu-btn-mobile" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
    </div>
    <a href="{{ route('admin.instructors.create') }}" class="button-lg">Register New Instructor</a>
</div>
<div class="se--main-layout">
    <div class="page-title">Dashboard</div>
    <div class="se--overview--layout">
        <p class="se--subtext">Overview</p>
        <div class="se--overview-cards-layout">
            <div class="se--overview--card--layout">
                <p class="se--card-head">Instructor Signups</p>
                <div class="se--overview-card">
                    <div class="se--over--card-row">
                        <div class="se--month-row">
                            <p Month class="se--card-pera">This Month</p>
                            @php
                            $arrowColor = $monthlyInstructorCount > 0 ? '#29FF65' : '#FF0000'; // green or red
                            $rotation = $monthlyInstructorCount > 0 ? 'rotate(0)' : 'rotate(180deg)'; // up or down
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
                                        <rect width="16" height="16" fill="white"
                                            transform="translate(16 16) rotate(-180)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <p class="se--card--number">{{ $monthlyInstructorCount }}</p>
                    </div>
                    <div class="se--over--card-row">
                        <div class="se--month-row">
                            <p Month class="se--card-pera">All Time</p>
                        </div>
                        <p class="se--card--number">{{ $totalInstructor }}</p>
                    </div>
                </div>
            </div>

            <div class="se--overview--card--layout">
                <p class="se--card-head">Student Signups</p>
                <div class="se--overview-card">
                    <div class="se--over--card-row">
                        <div class="se--month-row">
                            <p Month class="se--card-pera">This Month</p>
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
                                        <rect width="16" height="16" fill="white"
                                            transform="translate(16 16) rotate(-180)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <p class="se--card--number">{{ $monthlyUsersCount }}</p>
                    </div>
                    <div class="se--over--card-row">
                        <div class="se--month-row">
                            <p Month class="se--card-pera">All Time</p>
                        </div>
                        <p class="se--card--number">{{ $totalUsers }}</p>
                    </div>
                </div>
            </div>

            <div class="se--overview--card--layout">
                <p class="se--card-head">Total Revenue</p>
                <div class="se--overview-card">
                    <div class="se--over--card-row">
                        <div class="se--month-row">
                            <p Month class="se--card-pera">This Month</p>
                            @php
                            $arrowColor = $monthlyRevenue > 0 ? '#29FF65' : '#FF0000'; // green or red
                            $rotation = $monthlyRevenue > 0 ? 'rotate(0)' : 'rotate(180deg)'; // up or down
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
                                        <rect width="16" height="16" fill="white"
                                            transform="translate(16 16) rotate(-180)" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </div>
                        <p class="se--card--number">${{ $monthlyRevenue }}</p>
                    </div>
                    <div class="se--over--card-row">
                        <div class="se--month-row">
                            <p Month class="se--card-pera">All Time</p>
                        </div>
                        <p class="se--card--number">${{ $total_revenue }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="se--pending--approval-layout">
        <div class="se--pending-1">
            <p Month class="se--card-pera">Total Course</p>
            <p class="se--card--number">{{ $total_course }}</p>

        </div>
        {{-- <div class="se--pending-2">
            <p Month class="se--card-pera1">Total Category</p>
            <p style="color: black;" class="se--card--number">{{ $total_category }}</p>

        </div>
        <div class="se--pending-2">
            <p Month class="se--card-pera1">Total Tags</p>
            <p style="color: black;" class="se--card--number">{{ $total_tag }}</p>

        </div> --}}
    </div>

    {{-- <div class="se--table--layout">
        <div class="se--table--row1">
            <p class="se--subtext">Pending Approvals</p>
            <a style="text-decoration: underline !important;" href="#" class="se--subtext1">View All Approvals</a>

        </div>
        <div class="se-table-container">
            <table class="se-table">
                <thead class="se-thead">
                    <tr class="se-tr">
                        <th class="se-th">Course Name</th>
                        <th class="se-th">Instructor</th>
                        <th class="se-th">Category</th>
                        <th class="se-th">Request Type</th>
                        <th class="se-th">Status</th>
                        <th class="se-th"></th>
                    </tr>
                </thead>
                <tbody class="se-tbody">
                    <tr class="se-tr">
                        <td class="se-td">Free Artificial Intelligence Course</td>
                        <td class="se-td">Tadhg Kavanagh</td>
                        <td class="se-td">AI</td>
                        <td class="se-td">New Course</td>
                        <td class="se-td">
                            <span class="se--pending-btn">Pending</span>
                        </td>
                        <td class="se-td"><button class="se-button">Take Action</button></td>
                    </tr>
                    <tr class="se-tr">
                        <td class="se-td">Beginning C++ Programming</td>
                        <td class="se-td">Eamon Kelly</td>
                        <td class="se-td">Programming</td>
                        <td class="se-td">Content Addition</td>
                        <td class="se-td">
                            <span class="se--pending-btn">Pending</span>
                        </td>
                        <td class="se-td"><button class="se-button">Take Action</button></td>
                    </tr>
                    <tr class="se-tr">
                        <td class="se-td">Web Development Crash Course</td>
                        <td class="se-td">Sarah Johnson</td>
                        <td class="se-td">Web Development</td>
                        <td class="se-td">Content Removal</td>
                        <td class="se-td">
                            <span class="se--pending-btn">Pending</span>
                        </td>
                        <td class="se-td"><button class="se-button">Take Action</button></td>
                    </tr>
                </tbody>
            </table>


        </div>
    </div> --}}
</div>
@endsection