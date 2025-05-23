@extends('backend.app')
@section('title', 'Users')
@push('style')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
    <div class="robi-courde-content-header">
        <div class="page-title">{{ $course->title }}</div>
        @foreach ($course->tags as $tag)
            <button class="robi-trending-btn" data-tag-id="{{ $tag->id }}" data-course-id="{{ $course->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15" fill="none">
                    <path
                        d="M8.88464 5.8136C8.84688 5.76629 8.8011 5.77559 8.77712 5.78524C8.75703 5.79339 8.71098 5.81942 8.71735 5.88466C8.72501 5.963 8.7293 6.04288 8.73012 6.12209C8.73354 6.45071 8.60171 6.77265 8.36847 7.00538C8.13671 7.23659 7.83188 7.36117 7.50731 7.35756C7.06396 7.3519 6.69624 7.12066 6.44388 6.68882C6.23522 6.33173 6.32693 5.87118 6.42403 5.38356C6.48085 5.09815 6.53961 4.803 6.53961 4.5221C6.53961 2.3349 5.06923 1.07304 4.19275 0.515528C4.17462 0.504016 4.15737 0.499969 4.14209 0.499969C4.11723 0.499969 4.09754 0.510688 4.08783 0.517251C4.06902 0.529993 4.03892 0.559032 4.0486 0.610438C4.38361 2.38948 3.38436 3.45947 2.32643 4.59226C1.23596 5.75992 0 7.08339 0 9.47028C0 12.2437 2.25632 14.5 5.02975 14.5C7.31328 14.5 9.32662 12.908 9.92578 10.6284C10.3344 9.07412 9.9062 7.09422 8.88464 5.8136ZM5.15523 13.4264C4.46075 13.4581 3.80029 13.209 3.29582 12.7267C2.79677 12.2495 2.51054 11.5835 2.51054 10.8995C2.51054 9.61599 3.0013 8.67373 4.3213 7.42281C4.3429 7.40232 4.36502 7.39584 4.3843 7.39584C4.40177 7.39584 4.41692 7.40118 4.42734 7.40618C4.44929 7.41676 4.48539 7.44296 4.48052 7.49961C4.43332 8.04881 4.43414 8.50466 4.48292 8.85455C4.60761 9.74828 5.26187 10.3488 6.11103 10.3488C6.52736 10.3488 6.92393 10.1921 7.22769 9.90759C7.23942 9.89632 7.25372 9.88809 7.26936 9.88362C7.285 9.87916 7.3015 9.87859 7.31741 9.88197C7.33739 9.88629 7.36416 9.89854 7.37819 9.93236C7.50414 10.2365 7.5685 10.5592 7.56949 10.8917C7.57351 12.2295 6.49048 13.3666 5.15523 13.4264Z"
                        fill="black"></path>
                </svg>
                {{ $tag->name }}
            </button>
        @endforeach
    </div>
    <div class="robi-course-content">
        <p>Course Content</p>
        <div>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                @foreach ($course->modules as $index => $module)
                    <div class="accordion-item" id="module-{{ $module->id }}">
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
                                <button onclick="showDeleteConfirm({{ $module->id }})"
                                    class="text-decoration-underline fw-bold text-danger bg-transparent border-0 float-end">Delete
                                    Chapter</button>
                                @if ($module->videos && $module->videos->isNotEmpty())
                                    @foreach ($module->videos as $index => $video)
                                        @if ($video->video_url)
                                            <div class="robi-lesson-content" id="video-{{ $video->id }}">
                                                <button onclick="showDeleteVideoConfirm({{ $video->id }})"
                                                    class="bg-transparent">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="#FF0000" stroke-width="1"
                                                        stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M4 7l16 0" />
                                                        <path d="M10 11l0 6" />
                                                        <path d="M14 11l0 6" />
                                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                    </svg>
                                                </button>
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
                                        @if ($module->file_url != null)
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
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="robi-select-tags">
            <h1>Select Tags</h1>
            <div class="robi-select-tags-buttons">
                @foreach ($tags as $tag)
                    <button class="robi-trending-btn @if ($course->tags->contains($tag)) active @endif"
                        data-tag-id="{{ $tag->id }}" data-course-id="{{ $course->id }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15"
                            fill="none">
                            <path
                                d="M8.88464 5.8136C8.84688 5.76629 8.8011 5.77559 8.77712 5.78524C8.75703 5.79339 8.71098 5.81942 8.71735 5.88466C8.72501 5.963 8.7293 6.04288 8.73012 6.12209C8.73354 6.45071 8.60171 6.77265 8.36847 7.00538C8.13671 7.23659 7.83188 7.36117 7.50731 7.35756C7.06396 7.3519 6.69624 7.12066 6.44388 6.68882C6.23522 6.33173 6.32693 5.87118 6.42403 5.38356C6.48085 5.09815 6.53961 4.803 6.53961 4.5221C6.53961 2.3349 5.06923 1.07304 4.19275 0.515528C4.17462 0.504016 4.15737 0.499969 4.14209 0.499969C4.11723 0.499969 4.09754 0.510688 4.08783 0.517251C4.06902 0.529993 4.03892 0.559032 4.0486 0.610438C4.38361 2.38948 3.38436 3.45947 2.32643 4.59226C1.23596 5.75992 0 7.08339 0 9.47028C0 12.2437 2.25632 14.5 5.02975 14.5C7.31328 14.5 9.32662 12.908 9.92578 10.6284C10.3344 9.07412 9.9062 7.09422 8.88464 5.8136ZM5.15523 13.4264C4.46075 13.4581 3.80029 13.209 3.29582 12.7267C2.79677 12.2495 2.51054 11.5835 2.51054 10.8995C2.51054 9.61599 3.0013 8.67373 4.3213 7.42281C4.3429 7.40232 4.36502 7.39584 4.3843 7.39584C4.40177 7.39584 4.41692 7.40118 4.42734 7.40618C4.44929 7.41676 4.48539 7.44296 4.48052 7.49961C4.43332 8.04881 4.43414 8.50466 4.48292 8.85455C4.60761 9.74828 5.26187 10.3488 6.11103 10.3488C6.52736 10.3488 6.92393 10.1921 7.22769 9.90759C7.23942 9.89632 7.25372 9.88809 7.26936 9.88362C7.285 9.87916 7.3015 9.87859 7.31741 9.88197C7.33739 9.88629 7.36416 9.89854 7.37819 9.93236C7.50414 10.2365 7.5685 10.5592 7.56949 10.8917C7.57351 12.2295 6.49048 13.3666 5.15523 13.4264Z"
                                fill="white"></path>
                        </svg>
                        {{ $tag->name }}
                    </button>
                @endforeach
            </div>
        </div>
        <div>
            <h1 class="robi-comp-watch-time">Compare Watch Time</h1>
        </div>
        <div class="robi-chart-comprason">
            <div id="chart3" class="item"></div>
            <div id="chart4" class="item"></div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/ashiq.js') }}"></script>
    <!-- first graph -->
    <script>
        var watchTimes = @json($watchTimesInSeconds);

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
                    formatter: function(value) {
                        const hours = Math.floor(value / 3600);
                        const minutes = Math.floor((value % 3600) / 60);
                        const seconds = value % 60;
                        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    }
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

        var chart = new ApexCharts(document.querySelector("#chart3"), options);
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
                    formatter: function(value) {
                        const hours = Math.floor(value / 3600);
                        const minutes = Math.floor((value % 3600) / 60);
                        const seconds = value % 60;
                        return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    }
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

    <script>
        $(document).ready(function() {
            $(".robi-trending-btn").on("click", function() {
                var tagId = $(this).data("tag-id");
                var courseId = $(this).data("course-id");
                var button = $(this);

                $.ajax({
                    url: "{{ route('course.tag.store') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tag_id: tagId,
                        course_id: courseId
                    },
                    success: function(response) {
                        if (response.success) {
                            button.addClass("active");
                            toastr.success(response.message);
                        }
                        if (response.error) {
                            button.removeClass("active");
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 400) {
                            toastr.warning(xhr.responseJSON.message);
                        } else {
                            toastr.error("Something went wrong!");
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        // Delete module

        function showDeleteConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Button
        function deleteItem(id) {
            let url = "{{ route('admin.modules.destroy', ':id') }}";
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {

                    if (resp.success === true) {
                        // show toast message
                        toastr.success(resp.message);
                        $("#module-" + id).remove();
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    // location.reload();
                }
            })
        }

        // Delete video
        function showDeleteVideoConfirm(id) {
            event.preventDefault();
            Swal.fire({
                title: 'Are you sure you want to delete this record?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Button
        function deleteItem(id) {
            let url = "{{ route('admin.videos.destroy', ':id') }}";
            let csrfToken = '{{ csrf_token() }}';
            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: function(resp) {

                    if (resp.success === true) {
                        // show toast message
                        toastr.success(resp.message);
                        $("#video-" + id).remove();
                    } else if (resp.errors) {
                        toastr.error(resp.errors[0]);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(error) {
                    // location.reload();
                }
            })
        }
    </script>
@endpush
