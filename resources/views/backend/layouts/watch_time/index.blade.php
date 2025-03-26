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
                    barHeight: '70%',
                }
            },
            colors: ['#00FF00', '#FFC107', '#FF0000'], // Green, Yellow, Red
            series: [{
                name: 'Watch Time (Hours)',
                data: [{
                        x: 'Feb',
                        y: 18.1,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Patrick',
                        y: 15.9,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Sean',
                        y: 24.3,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Connor',
                        y: 13.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Liam',
                        y: 4.4,
                        fillColor: '#FF0000'
                    },
                    {
                        x: 'Finn',
                        y: 8.5,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Cillian',
                        y: 2.3,
                        fillColor: '#FF0000'
                    },
                    {
                        x: 'Aiden',
                        y: 16.0,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Brendan',
                        y: 10.5,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Declan',
                        y: 14.4,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Eoin',
                        y: 8.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Killian',
                        y: 18.2,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Niall',
                        y: 6.2,
                        fillColor: '#FF0000'
                    },
                    {
                        x: 'Oisin',
                        y: 12.4,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Ronan',
                        y: 6.3,
                        fillColor: '#FF0000'
                    },
                    {
                        x: 'Tadhg',
                        y: 10.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Darragh',
                        y: 16.3,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Eoghan',
                        y: 8.4,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Cormac',
                        y: 13.8,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Tadhg',
                        y: 10.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Darragh',
                        y: 16.3,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Eoghan',
                        y: 8.4,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Cormac',
                        y: 13.8,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Tadhg',
                        y: 10.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Darragh',
                        y: 16.3,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Eoghan',
                        y: 8.4,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Cormac',
                        y: 13.8,
                        fillColor: '#FFC107'
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
