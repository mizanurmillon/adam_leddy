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
        <div class="page-title ">Watch Time</div>
        <div class=" ak-flex gap-3">
            <div class="rounded item">
                <p class="se--subtext ak-my-3">Top Courses</p>

                <div class="bg-danger rounded-top-1 d-flex justify-content-between px-4 py-3">
                    <p class="fw-semibold">Courses</p>
                    <p class="fw-semibold">Watch Time</p>
                </div>

                @foreach ($courses as $course)
                    @php
                        $totalSeconds = $course->courseWatches->sum('watch_time');
                        $hours = floor($totalSeconds / 3600);
                        $minutes = floor(($totalSeconds % 3600) / 60);
                    @endphp
                    <div class="d-flex justify-content-between px-4 py-3 bg-dark">
                        <p>{{ $course->title }}</p>
                        <p>{{ $hours }}H {{ $minutes }}M</p>
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
                        $hours = floor($totalSeconds / 3600);
                        $minutes = floor(($totalSeconds % 3600) / 60);
                    @endphp
                    <div class="d-flex justify-content-between px-4 py-3 bg-dark">
                        <p>{{ $course->instructor->user->first_name }} {{ $course->instructor->user->last_name }}</p>
                        <p>{{ $hours }}H {{ $minutes }}M</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- graph  -->
        <div class="ak-graph-flex align-items-center ">
            <div class="item">
                <p class="se--subtext ak-my-3">Watch Time Of Instructors</p>
                <div id="chart"></div>
            </div>
            <div class="ak-legend-flex">
                <div class="d-flex gap-3">
                    <img src="{{ asset('backend/assets/images/1.png') }}" alt="">
                    <p>Above 14 H</p>
                </div>
                <div class="d-flex gap-3">
                    <img src="{{ asset('backend/assets/images/2.png') }}" alt="">
                    <p>8-14 H</p>
                </div>
                <div class="d-flex gap-3">
                    <img src="{{ asset('backend/assets/images/3.png') }}" alt="">
                    <p>Less Than 8 H</p>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        var instructors = @json($instructors);

        var chartData = instructors.map(instructor => ({
            x: instructor.name,
            y: instructor.watch_time,
            fillColor: instructor.watch_time > 14 ? '#00FF00' : (instructor.watch_time >= 8 ? '#FFC107' :
                '#FF0000')
        }));

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
                    barHeight: '70%'
                }
            },
            colors: ['#00FF00', '#FFC107', '#FF0000'],
            series: [{
                name: 'Watch Time (Hours)',
                data: chartData
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
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endpush
