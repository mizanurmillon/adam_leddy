@extends('backend.app')
@section('title', 'Users')
@push('style')
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiq.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiqResponsive.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        .se--profile-block {
            display: flex;
            padding: 8px;
            justify-content: center;
            align-items: center;
            gap: 4px;
            border-radius: 10000px;
            background: var(--Default-Success, #ff4040);
            color: #fff;
            text-align: center;
            font-family: Montserrat;
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 110%;
        }
    </style>
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
    <div class="robi-courde-content-header">
        <div class=" se--profile-content">
            <div class="se--profile-name-layout">
                <h1 class="se--profile-name">{{ $course->instructor->user->first_name }}
                    {{ $course->instructor->user->last_name }}</h1>
                @if ($course->instructor->user->status == 'active')
                    <p class="se--profile-status">Active</p>
                @else
                    <p class="se--profile-block">Inactive</p>
                @endif

            </div>

            <p class="se--profile-email">{{ $course->instructor->user->email }}</p>

        </div>
    </div>

    <div class="robi-course-content">
        <p>Course Content</p>
        <div>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                @foreach ($course->modules as $index => $module)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#flush-collapse{{ $index }}" aria-expanded="false"
                                aria-controls="flush-collapse{{ $index }}">
                                Chapter {{ $index + 1 }}: {{ $module->module_title }}
                            </button>
                        </h2>
                        <div id="flush-collapse{{ $index }}" class="accordion-collapse collapse"
                            data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">
                                @if ($module->videos && $module->videos->isNotEmpty())
                                    @foreach ($module->videos as $index => $video)
                                        @if ($video->video_url)
                                            <div class="robi-lesson-content">
                                                <a href="{{ $video->video_url }}">
                                                    <p class="robi-lesson">
                                                        Lesson {{ $index + 1 }}:
                                                        <span>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="18"
                                                                height="19" viewBox="0 0 18 19" fill="none">
                                                                <path
                                                                    d="M9 0.5C4.02944 0.5 0 4.52944 0 9.5C0 14.4706 4.02944 18.5 9 18.5C13.9706 18.5 18 14.4706 18 9.5C17.9947 4.53166 13.9684 0.505311 9 0.5ZM12.7903 9.78676C12.728 9.91176 12.6266 10.0131 12.5016 10.0754V10.0786L7.35879 12.65C7.04118 12.8087 6.65509 12.6799 6.49636 12.3623C6.45123 12.272 6.428 12.1724 6.42856 12.0714V6.92859C6.4284 6.57354 6.71607 6.28561 7.07113 6.28542C7.17098 6.28538 7.26947 6.30859 7.35879 6.35322L12.5016 8.92467C12.8194 9.08302 12.9486 9.469 12.7903 9.78676Z"
                                                                    fill="white" />
                                                            </svg>
                                                            {{ $video->video_title }}
                                                        </span>
                                                    </p>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                                
                                <div class="robi-lesson-content">
                                    <p class="robi-lesson">
                                        Lesson File:
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="18"
                                                viewBox="0 0 14 18" fill="none">
                                                <path
                                                    d="M1.58203 18H12.1289C13.0013 18 13.7109 17.2903 13.7109 16.418V5.27344H10.0195C9.14716 5.27344 8.4375 4.56377 8.4375 3.69141V0H1.58203C0.709664 0 0 0.709664 0 1.58203V16.418C0 17.2903 0.709664 18 1.58203 18ZM3.69141 7.41797H10.0195C10.311 7.41797 10.5469 7.65383 10.5469 7.94531C10.5469 8.23679 10.311 8.47266 10.0195 8.47266H3.69141C3.39993 8.47266 3.16406 8.23679 3.16406 7.94531C3.16406 7.65383 3.39993 7.41797 3.69141 7.41797ZM3.69141 9.52734H10.0195C10.311 9.52734 10.5469 9.76321 10.5469 10.0547C10.5469 10.3462 10.311 10.582 10.0195 10.582H3.69141C3.39993 10.582 3.16406 10.3462 3.16406 10.0547C3.16406 9.76321 3.39993 9.52734 3.69141 9.52734ZM3.69141 11.6367H10.0195C10.311 11.6367 10.5469 11.8726 10.5469 12.1641C10.5469 12.4555 10.311 12.6914 10.0195 12.6914H3.69141C3.39993 12.6914 3.16406 12.4555 3.16406 12.1641C3.16406 11.8726 3.39993 11.6367 3.69141 11.6367ZM3.69141 13.7461H7.91016C8.20164 13.7461 8.4375 13.982 8.4375 14.2734C8.4375 14.5649 8.20164 14.8008 7.91016 14.8008H3.69141C3.39993 14.8008 3.16406 14.5649 3.16406 14.2734C3.16406 13.982 3.39993 13.7461 3.69141 13.7461Z"
                                                    fill="white" />
                                                <path
                                                    d="M10.0195 4.21884H13.4019L9.49219 0.309082V3.6915C9.49219 3.98245 9.72858 4.21884 10.0195 4.21884Z"
                                                    fill="white" />
                                            </svg>
                                            {{ $module->file_url }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
        <div>
            <div class="robi-chart">
                <h1 class="robi-watch-time">Watch Time</h1>
                <div class="item" id="chart"></div>



            </div>
            {{-- <div class="robi-chart2">
                <h1 class="robi-click">Clicks</h1>
                <div class="item" id="charta"></div>


            </div> --}}

        </div>
        <div class="robi-course-comprason-bottom">
            <h5 class="robi-course-comprason-pera">Course Comparison</h5>
            <h1 class="robi-course-title">{{ $course->title }}</h1>
            <div class="robi-chart-comprason">

                <div class="item" id="chart3"></div>

                <div class="item" id="chart4"></div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/ashiq.js') }}"></script>
    <script>
        var watchTimes = @json($watchTimes);

        var options = {
            chart: {
                type: 'line',
                width: '100%',
                background: '#000',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Hours',
                data: watchTimes
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                    },
                    formatter: (value) => value + ' H'
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                colors: ['#ff0000']
            },
            markers: {
                size: 4,
                colors: ['#fff'],
                strokeColors: '#ff0000',
                strokeWidth: 2
            },
            title: {
                text: @json($course->title) + ' - Monthly Watch Time',
                align: 'center',
                style: {
                    color: '#fff',
                    fontSize: '16px'
                }
            },
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();
    </script>



    <!-- middle chart start  -->

    {{-- <script>
        var options = {
            chart: {
                type: 'bar',
                minHeight: 1000,
                width: "70%",
                background: '#000',
                toolbar: {
                    show: false
                } // Remove the toolbar


            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '70%',
                }
            },
            colors: ['#FF4040', '#FFD540', ], // Green, Yellow, Red
            series: [{
                name: 'Watch Time (Hours)',
                data: [{
                        x: 'Jan',
                        y: 18.1,
                        fillColor: '#FF4040'
                    },
                    {
                        x: "",
                        y: 15.9,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Feb',
                        y: 24.3,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 13.9,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Mar',
                        y: 4.4,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 8.5,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Apl',
                        y: 2.3,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 16.0,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'May',
                        y: 10.5,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 14.4,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Jun',
                        y: 8.9,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 18.2,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Jul',
                        y: 6.2,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 12.4,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Aug',
                        y: 6.3,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 10.9,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Sep',
                        y: 16.3,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 8.4,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Oct',
                        y: 13.8,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 13.8,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Nov',
                        y: 13.8,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 13.8,
                        fillColor: '#FFD540'
                    },
                    {
                        x: 'Dec',
                        y: 13.8,
                        fillColor: '#FF4040'
                    },
                    {
                        x: '',
                        y: 13.8,
                        fillColor: '#FFD540'
                    },
                ]
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
                labels: {
                    colors: ['#fff'],
                }
            },
            grid: {
                borderColor: '#444'
            },
            title: {
                text: 'Watch Time Of Instructors',
                style: {
                    color: '#fff',
                    fontSize: '20px'
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#charta"), options);
        chart.render();
    </script> --}}

    <!-- bottom chart start -->
    <script>
        var watchTimes = @json($watchTimes);

        var options = {
            chart: {
                type: 'line',
                height: 300,
                background: '#000',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Hours',
                data: watchTimes
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                    },
                    formatter: (value) => value + ' H'
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                colors: ['#ff0000']
            },
            markers: {
                size: 4,
                colors: ['#fff'],
                strokeColors: '#ff0000',
                strokeWidth: 2
            },
            title: {
                text: @json($course->title),
                align: 'center',
                style: {
                    color: '#fff',
                    fontSize: '16px'
                }
            },
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
    <script>
        var options = {
            chart: {
                type: 'line',
                width: '100%',
                background: '#000',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Hours',
                data: @json($topWatchTimes) // Dynamically passed data
            }],
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
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
                    },
                    formatter: (value) => value + ' H'
                }
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                colors: ['#ff0000']
            },
            markers: {
                size: 4,
                colors: ['#fff'],
                strokeColors: '#ff0000',
                strokeWidth: 2
            },
            title: {
                text: @json($topInstructor->instructor->user->first_name . ' ' . $topInstructor->instructor->user->last_name),
                align: 'center',
                style: {
                    color: '#fff',
                    fontSize: '16px'
                }
            },
            grid: {
                borderColor: '#444'
            }
        };

        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    </script>
@endpush
