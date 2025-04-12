@extends('backend.app')
@section('title', 'Users')
@push('style')
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiq.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiqResponsive.css') }}" />

    <link rel="stylesheet" href="{{ asset('backend/assets/css/akash.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/akashResponsive.css') }}" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
        <div class="page-title ">Revenue</div>

        <div class="se--pending--approval-layout">
            <div class="se--pending-1">
                <p Month class="se--card-pera">All Time</p>
                <p class="se--card--number">€{{ $total_revenue }}</p>

            </div>

        </div>

        <div class="se--pending--approval-layout">
            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">This Month</p>
                <p class="se--card--number">€{{ $monthlyRevenue }}</p>
            </div>

            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">Previous Month</p>
                <p class="se--card--number">€{{ $previousMonthlyRevenue }}</p>
            </div>

            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">Past 6 Months</p>
                <p class="se--card--number">€{{ $pastSixMonthsRevenue }}</p>
            </div>

            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">Past Year</p>
                <p class="se--card--number">€{{ $pastYearRevenue }}</p>
            </div>

        </div>


        <div class="ak-flex gap-3">
            <div class=" rounded item">
                <p class="se--subtext ak-my-3">Top Courses</p>

                <div class="bg-danger rounded-top-1 d-flex justify-content-between px-4 py-3">
                    <p class="fw-semibold">Courses</p>
                    <p class="fw-semibold">Watch Time</p>
                </div>
                @foreach ($courses as $course)
                    @php
                        $totalSeconds = $course->courseWatches->sum('watch_time');
                        $time = ($totalSeconds / 1000);
                    @endphp
                    <div class="d-flex justify-content-between px-4 py-3 bg-dark">
                        <p>{{ $course->title }}</p>
                        <p>{{ gmdate("H:i:s", $time) }}</p>
                    </div>
                @endforeach
            </div>

            <div class=" rounded item">
                <p class="se--subtext ak-my-3">Top Instructors</p>

                <div class="bg-danger rounded-top-1 d-flex justify-content-between px-4 py-3">
                    <p class="fw-semibold">Instructor</p>
                    <p class="fw-semibold">Watch Time</p>
                </div>
                @foreach ($courses as $course)
                    @php
                        $totalSeconds = $course->courseWatches->sum('watch_time');
                        $time = ($totalSeconds / 1000);
                    @endphp
                    <div class="d-flex justify-content-between px-4 py-3 bg-dark">
                        <p>{{ $course->instructor->user->first_name }} {{ $course->instructor->user->last_name }}</p>
                        <p>{{ gmdate("H:i:s", $time) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- graph  -->
        <div class="ak-graph-flex align-items-center ">
            <div class="w-75">
                <p class="se--subtext ak-my-3">Watch Time Of Courses</p>
                <div id="chart" class=""></div>
            </div>
            <div class="d-lg-flex flex-column gap-3">
                <div class="ak-flex gap-3">
                    <img src="{{ asset('backend/assets/images/1.png') }}" alt="">
                    <p>Above 14 H</p>
                </div>
                <div class="ak-flex gap-3">
                    <img src="{{ asset('backend/assets/images/2.png') }}" alt="">
                    <p>8-14 H</p>
                </div>
                <div class="ak-flex gap-3">
                    <img src="{{ asset('backend/assets/images/3.png') }}" alt="">
                    <p>Less Than 8 H</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        var courseData = @json($courseData); 

        var options = {
            chart: {
                type: 'bar',
                width: "100%",
                background: '#191919',
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '30%',
                }
            },
            colors: ['#00FF00', '#FFC107', '#FF0000'], 
            series: [{
                name: 'Watch Time (Hours)',
                data: courseData.map(function(course) {
                    return {
                        x: course.name, 
                        y: course.watch_time, 
                        fillColor: getFillColor(course
                            .watch_time) 
                    };
                })
            }],
            xaxis: {
                title: {
                    text: 'Hours'
                },
                labels: {
                    style: {
                        colors: '#fff'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#fff'
                    }
                }
            },
            legend: {
                show: true,
                position: 'top',
                labels: {
                    colors: ['#fff'],
                    useSeriesColors: false
                },
                markers: {
                    fillColors: ['#00FF00', '#FFC107', '#FF0000']
                }
            },
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        // Function to dynamically get color based on watch time
        function getFillColor(watchTime) {
            if (watchTime > 14) {
                return '#00FF00'; // High Watch Time (Green)
            } else if (watchTime >= 8 && watchTime <= 14) {
                return '#FFC107'; // Medium Watch Time (Yellow)
            } else {
                return '#FF0000'; // Low Watch Time (Red)
            }
        }
    </script>
@endpush
