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
                <p class="se--card--number">€92,21,829</p>

            </div>

        </div>

        <div class="se--pending--approval-layout">
            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">This Month</p>
                <p class="se--card--number">€9,829</p>
            </div>

            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">Previous Month</p>
                <p class="se--card--number">€91,89</p>
            </div>

            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">Past 6 Months</p>
                <p class="se--card--number">€3,98,829</p>
            </div>

            <div class="se--pending-1 bg-dark">
                <p Month class="se--card-pera">Past Year</p>
                <p class="se--card--number">€7,22,829</p>
            </div>

        </div>


        <div class="ak-flex gap-3">
            <div class=" rounded item">
                <p class="se--subtext ak-my-3">Top Courses</p>

                <div class="bg-danger rounded-top-1 d-flex justify-content-between px-4 py-3">
                    <p class="fw-semibold">Courses</p>
                    <p class="fw-semibold">Watch Time</p>
                </div>
                <div class="  d-flex justify-content-between px-4 py-3 bg-dark">
                    <p>Free Artificial Intelligence Course</p>
                    <p>439 H</p>
                </div>
                <div class="  d-flex justify-content-between px-4 py-3 bg-dark">
                    <p>Beginning C++ Programming</p>
                    <p>439 H</p>
                </div>
                <div class="  d-flex justify-content-between px-4 py-3 bg-dark">
                    <p>AI Fundamentals</p>
                    <p>439 H</p>
                </div>
            </div>

            <div class=" rounded item">
                <p class="se--subtext ak-my-3">Top Instructors</p>

                <div class="bg-danger rounded-top-1 d-flex justify-content-between px-4 py-3">
                    <p class="fw-semibold">Instructor</p>
                    <p class="fw-semibold">Watch Time</p>
                </div>
                <div class="  d-flex justify-content-between px-4 py-3 bg-dark">
                    <p>Tadhg Kavanagh</p>
                    <p>439 H</p>
                </div>
                <div class="  d-flex justify-content-between px-4 py-3 bg-dark">
                    <p>Tadhg Kavanagh</p>
                    <p>439 H</p>
                </div>
                <div class="  d-flex justify-content-between px-4 py-3 bg-dark">
                    <p>Tadhg Kavanagh</p>
                    <p>439 H</p>
                </div>
            </div>
        </div>

        <!-- graph  -->
        <div class="ak-graph-flex align-items-center ">
            <div class="w-75">
                <p class="se--subtext ak-my-3">Watch Time Of Instructors</p>
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
        var options = {
            chart: {
                type: 'bar',
                width: "100%",
                background: '#191919',
                toolbar: {
                    show: false
                } // Remove the toolbar
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    barHeight: '30%',
                }
            },
            colors: ['#00FF00', '#FFC107', '#FF0000'], // Green, Yellow, Red
            series: [{
                name: 'Watch Time (Hours)',
                data: [{
                        x: 'Programming',
                        y: 10.5,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Design',
                        y: 14.4,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Data Analysis',
                        y: 8.8,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Database',
                        y: 18.2,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'AI',
                        y: 8.2,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Cloud Computing',
                        y: 12.4,
                        fillColor: '#00FF00'
                    }
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
                position: 'top', // Position the legend at the top
                labels: {
                    colors: ['#fff'], // Legend text color
                    useSeriesColors: false // Disable automatic color assignment
                },
                markers: {
                    fillColors: ['#00FF00', '#FFC107', '#FF0000'] // Custom colors for legend markers
                },
                formatter: function(seriesName, opts) {
                    // Custom legend labels
                    if (seriesName === 'Watch Time (Hours)') {
                        return [
                            '<span style="color: #00FF00;">High Watch Time</span>',
                            '<span style="color: #FFC107;">Medium Watch Time</span>',
                            '<span style="color: #FF0000;">Low Watch Time</span>'
                        ].join(' | ');
                    }
                    return seriesName;
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
