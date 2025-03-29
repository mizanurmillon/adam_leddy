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
        .se--unblock {
            display: flex;
            padding: 12px;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border-radius: 3px;
            background: var(--Default-Alert, #29ff65);
            color: var(--Neutral-100, #000);
            border: none;
            outline: none;
            font-family: Montserrat;
            font-size: 16px;
            font-style: normal;
            font-weight: 600;
            line-height: 100%;
            letter-spacing: 0.5px;
        }

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

    @php
        $isActive = $instructor->user->status == 'active';
    @endphp
    <div class="page-header">
        <div class="desktop-sidebar-closer-container">
            <img class="desktop-sidebar-closer" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
        <div>
            <img class="menu-btn-mobile" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />

        </div>
        <button id="statusButton{{ $instructor->id }}" onclick="showStatusChangeAlert({{ $instructor->id }})" class="{{ $isActive ? 'se--block' : 'se--unblock' }}">
            {{ $isActive ? 'Block Instructor' : 'Unblock Instructor' }}
        </button>
    </div>
    <div class="se--main-layout">

        <div class=" se--profile-content">
            <div class="se--profile-name-layout">
                <h1 class="se--profile-name">{{ $instructor->user->first_name }} {{ $instructor->user->last_name }}</h1>
                <p id="statusText{{ $instructor->id }}" class="{{ $isActive ? 'se--profile-status' : 'se--profile-block' }}">
                    {{ $isActive ? 'Active' : 'Inactive' }}
                </p>

            </div>

            <p class="se--profile-email">{{ $instructor->user->email }}</p>

        </div>
        <div class="se--instructor-course-layout">
            <div class="se--course--permission">
                <p>Permission to upload courses/lessons</p>
                <div class="form-check form-switch ">
                    <input class="form-check-input py-lg-3 py-2 px-3 px-lg-4" type="checkbox"
                        @if ($instructor->user->status == 'active') checked @endif
                        onclick="showStatusChangeAlert({{ $instructor->id }})" role="switch"
                        id="customSwitch{{ $instructor->id }}">
                </div>

            </div>

            <div class="se--courses-layout">
                <p class="se--common-subtext">Courses From Fionn Byrne</p>
                <div class="se--course-content-layout">
                    @foreach ($courses as $course)
                        <a href="{{ route('admin.instructors.video.details', $course->id) }}" class="se--card">
                            <img class="se--image--card"
                                src="{{ asset($course->thumbnail ?? 'backend/assets/images/card1.png') }}"
                                alt="course" style="height: 168px;" />
                            <div class="se--card-font-layout">
                                <h2>
                                    {{ $course->title }}
                                </h2>
                                <p>{{ $course->instructor->user->first_name }} {{ $course->instructor->user->last_name }}</p>
                                <p>{{ $course->modules_count }} Chapters | {{ $course->videos_count }} Lessons</p>

                            </div>

                            <button type="button" class="se-card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15"
                                    fill="none">
                                    <path
                                        d="M7.27182 10.3115L7.91202 9.27269L9.06735 8.87867L9.19535 7.66524L10.0646 6.80951L9.65131 5.66091L10.0646 4.51228L9.19537 3.65656L9.06738 2.44316L7.91205 2.04913L7.27185 1.01026L6.06093 1.15647L5.03229 0.5L4.00364 1.15652L2.79275 1.01032L2.15255 2.04916L0.997227 2.44318L0.869231 3.65659L0 4.51231L0.413273 5.66094L0 6.80957L0.869203 7.66527L0.997199 8.8787L2.15253 9.27272L2.79273 10.3116L4.00364 10.1654L5.03229 10.8219L6.06093 10.1654L7.27182 10.3115ZM1.49321 5.66094C1.49321 3.7095 3.08085 2.12187 5.03229 2.12187C6.98373 2.12187 8.57136 3.7095 8.57136 5.66094C8.57136 7.61238 6.98373 9.20002 5.03229 9.20002C3.08085 9.20002 1.49321 7.61238 1.49321 5.66094Z"
                                        fill="white" />
                                    <path
                                        d="M5.03193 2.94238C3.53308 2.94238 2.31368 4.16178 2.31368 5.66062C2.31368 7.15947 3.53308 8.37887 5.03193 8.37887C6.53077 8.37887 7.75017 7.15947 7.75017 5.66062C7.75017 4.16178 6.53077 2.94238 5.03193 2.94238ZM3.80963 11.0152L2.36916 11.1891L1.60811 9.95409L1.34938 9.86587L0.233398 13.354L2.24248 13.2435L3.81434 14.4997L4.73932 11.6086L3.80963 11.0152ZM8.45575 9.95411L7.69466 11.1891L6.25422 11.0152L5.32453 11.6086L6.24952 14.4997L7.82137 13.2435L9.83045 13.354L8.71447 9.86587L8.45575 9.95411Z"
                                        fill="white" />
                                </svg>
                                @foreach ($course->tags as $tag)
                                    <span>{{ $tag->name }}</span>
                                @endforeach

                            </button>


                        </a>
                    @endforeach
                </div>

            </div>

            <div class="se--course-watch-layout">
                <p class="se--common-subtext">Watch Time</p>
                <div class="chart1-investor-course" id="chart1"></div>

            </div>
            {{-- <div class="se--course-watch-layout">

                <p class="se--common-subtext">Clicks</p>
                <div class="chart2-investor-course" id="chart2"></div>
            </div> --}}



        </div>


    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
    <script>
        var months = @json($months);
        var watchTime = @json($watchData).map(time => (time / 3600).toFixed(2));

        var options1 = {
            chart: {
                type: 'line',
                height: 300,
                background: '#202020',
                toolbar: {
                    show: false
                }
            },
            series: [{
                name: 'Hours',
                data: watchTime
            }],
            xaxis: {
                categories: months,
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
                    formatter: (value) => value + ' h'
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
                text: 'Course Watch Time per Month',
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

        var chart1 = new ApexCharts(document.querySelector("#chart1"), options1);
        chart1.render();
    </script>
    {{-- <script>
        var options2 = {
            chart: {
                type: 'bar',
                minHeight: 1000,
                width: "100%",
                background: '#202020',
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
                        x: 'Jan',
                        y: 18.1,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Feb',
                        y: 15.9,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Mar',
                        y: 24.3,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Apr',
                        y: 13.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'May',
                        y: 4.4,
                        fillColor: '#FF0000'
                    },
                    {
                        x: 'Jun',
                        y: 8.5,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Jul',
                        y: 2.3,
                        fillColor: '#FF0000'
                    },
                    {
                        x: 'Aug',
                        y: 16.0,
                        fillColor: '#00FF00'
                    },
                    {
                        x: 'Sep',
                        y: 10.5,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Oct',
                        y: 14.4,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Nov',
                        y: 8.9,
                        fillColor: '#FFC107'
                    },
                    {
                        x: 'Dec',
                        y: 18.2,
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

        var chart2 = new ApexCharts(document.querySelector("#chart2"), options2);
        chart2.render();
    </script> --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Block this Instructor?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                } else {
                    const checkbox = document.getElementById('customSwitch' + id);
                    checkbox.checked = !checkbox.checked;
                }
            });
        }

        // Status Change
        function statusChange(id) {
            let url = "{{ route('admin.instructors.status', ':id') }}".replace(':id', id);

            $.ajax({
                type: "POST",
                url: url,
                success: function(resp) {
                    if (resp.success === true) {
                        const checkbox = document.getElementById('customSwitch' + id);
                        if (checkbox) {
                            checkbox.checked = (resp.status === 'active');
                        }
                        // Correctly select the specific status text element for this instructor
                        let statusText = document.querySelector(`#statusText${id}`);
                        if (statusText) {
                            if (resp.status === 'active') {
                                statusText.classList.remove('se--profile-block');
                                statusText.classList.add('se--profile-status');
                                statusText.innerText = "Active";
                            } 

                            if(resp.status === 'inactive'){
                                statusText.classList.remove('se--profile-status');
                                statusText.classList.add('se--profile-block');
                                statusText.innerText = "Inactive";
                            }
                        }

                        // Correctly select the specific button for this instructor
                        let button = document.querySelector(`#statusButton${id}`);
                        if (button) {
                            if (resp.status === 'active') {
                                button.classList.remove('se--unblock');
                                button.classList.add('se--block');
                                button.innerText = "Block Instructor";
                            } 
                            if(resp.status === 'inactive'){
                                button.classList.remove('se--block');
                                button.classList.add('se--unblock');
                                button.innerText = "Unblock Instructor";
                            }
                        }

                        toastr.success(resp.message);
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    toastr.error('Something went wrong!');
                }
            });
        }
    </script>
@endpush
