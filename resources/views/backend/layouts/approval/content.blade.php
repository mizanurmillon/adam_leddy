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
            <img class="desktop-sidebar-closer" src="./assets/images/menu.png" alt="" />
        </div>
        <div>
            <img class="menu-btn-mobile" src="./assets/images/menu.png" alt="" />
        </div>
        <!-- <a href="" class="button-lg">Register New Instructor</a> -->
    </div>
    <div class="robi-courde-content-header">
        <div class="page-title">Machine Learning A-Z</div>
        <button class="robi-trending-btn"><svg xmlns="http://www.w3.org/2000/svg" width="11" height="15"
                viewBox="0 0 11 15" fill="none">
                <path
                    d="M8.88464 5.8136C8.84688 5.76629 8.8011 5.77559 8.77712 5.78524C8.75703 5.79339 8.71098 5.81942 8.71735 5.88466C8.72501 5.963 8.7293 6.04288 8.73012 6.12209C8.73354 6.45071 8.60171 6.77265 8.36847 7.00538C8.13671 7.23659 7.83188 7.36117 7.50731 7.35756C7.06396 7.3519 6.69624 7.12066 6.44388 6.68882C6.23522 6.33173 6.32693 5.87118 6.42403 5.38356C6.48085 5.09815 6.53961 4.803 6.53961 4.5221C6.53961 2.3349 5.06923 1.07304 4.19275 0.515528C4.17462 0.504016 4.15737 0.499969 4.14209 0.499969C4.11723 0.499969 4.09754 0.510688 4.08783 0.517251C4.06902 0.529993 4.03892 0.559032 4.0486 0.610438C4.38361 2.38948 3.38436 3.45947 2.32643 4.59226C1.23596 5.75992 0 7.08339 0 9.47028C0 12.2437 2.25632 14.5 5.02975 14.5C7.31328 14.5 9.32662 12.908 9.92578 10.6284C10.3344 9.07412 9.9062 7.09422 8.88464 5.8136ZM5.15523 13.4264C4.46075 13.4581 3.80029 13.209 3.29582 12.7267C2.79677 12.2495 2.51054 11.5835 2.51054 10.8995C2.51054 9.61599 3.0013 8.67373 4.3213 7.42281C4.3429 7.40232 4.36502 7.39584 4.3843 7.39584C4.40177 7.39584 4.41692 7.40118 4.42734 7.40618C4.44929 7.41676 4.48539 7.44296 4.48052 7.49961C4.43332 8.04881 4.43414 8.50466 4.48292 8.85455C4.60761 9.74828 5.26187 10.3488 6.11103 10.3488C6.52736 10.3488 6.92393 10.1921 7.22769 9.90759C7.23942 9.89632 7.25372 9.88809 7.26936 9.88362C7.285 9.87916 7.3015 9.87859 7.31741 9.88197C7.33739 9.88629 7.36416 9.89854 7.37819 9.93236C7.50414 10.2365 7.5685 10.5592 7.56949 10.8917C7.57351 12.2295 6.49048 13.3666 5.15523 13.4264Z"
                    fill="black" />
            </svg>Trending</button>
    </div>
    <p class="robi-programing">Programming</p>
    <div class="robi-course-content">
        <p>Course Content</p>
        <div>
            <div class="accordion accordion-flush" id="accordionFlushExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                            Chapter 1: Basics
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="robi-lesson-content">
                                <p class="robi-lesson">Lesson 01: <span><svg xmlns="http://www.w3.org/2000/svg"
                                            width="18" height="19" viewBox="0 0 18 19" fill="none">
                                            <path
                                                d="M9 0.5C4.02944 0.5 0 4.52944 0 9.5C0 14.4706 4.02944 18.5 9 18.5C13.9706 18.5 18 14.4706 18 9.5C17.9947 4.53166 13.9684 0.505311 9 0.5ZM12.7903 9.78676C12.728 9.91176 12.6266 10.0131 12.5016 10.0754V10.0786L7.35879 12.65C7.04118 12.8087 6.65509 12.6799 6.49636 12.3623C6.45123 12.272 6.428 12.1724 6.42856 12.0714V6.92859C6.4284 6.57354 6.71607 6.28561 7.07113 6.28542C7.17098 6.28538 7.26947 6.30859 7.35879 6.35322L12.5016 8.92467C12.8194 9.08302 12.9486 9.469 12.7903 9.78676Z"
                                                fill="white" />
                                        </svg> Introduction.mp4</span> </p>

                            </div>
                            <div class="robi-lesson-content">
                                <p class="robi-lesson">Lesson 02: <span><svg xmlns="http://www.w3.org/2000/svg"
                                            width="14" height="18" viewBox="0 0 14 18" fill="none">
                                            <path
                                                d="M1.58203 18H12.1289C13.0013 18 13.7109 17.2903 13.7109 16.418V5.27344H10.0195C9.14716 5.27344 8.4375 4.56377 8.4375 3.69141V0H1.58203C0.709664 0 0 0.709664 0 1.58203V16.418C0 17.2903 0.709664 18 1.58203 18ZM3.69141 7.41797H10.0195C10.311 7.41797 10.5469 7.65383 10.5469 7.94531C10.5469 8.23679 10.311 8.47266 10.0195 8.47266H3.69141C3.39993 8.47266 3.16406 8.23679 3.16406 7.94531C3.16406 7.65383 3.39993 7.41797 3.69141 7.41797ZM3.69141 9.52734H10.0195C10.311 9.52734 10.5469 9.76321 10.5469 10.0547C10.5469 10.3462 10.311 10.582 10.0195 10.582H3.69141C3.39993 10.582 3.16406 10.3462 3.16406 10.0547C3.16406 9.76321 3.39993 9.52734 3.69141 9.52734ZM3.69141 11.6367H10.0195C10.311 11.6367 10.5469 11.8726 10.5469 12.1641C10.5469 12.4555 10.311 12.6914 10.0195 12.6914H3.69141C3.39993 12.6914 3.16406 12.4555 3.16406 12.1641C3.16406 11.8726 3.39993 11.6367 3.69141 11.6367ZM3.69141 13.7461H7.91016C8.20164 13.7461 8.4375 13.982 8.4375 14.2734C8.4375 14.5649 8.20164 14.8008 7.91016 14.8008H3.69141C3.39993 14.8008 3.16406 14.5649 3.16406 14.2734C3.16406 13.982 3.39993 13.7461 3.69141 13.7461Z"
                                                fill="white" />
                                            <path
                                                d="M10.0195 4.21884H13.4019L9.49219 0.309082V3.6915C9.49219 3.98245 9.72858 4.21884 10.0195 4.21884Z"
                                                fill="white" />
                                        </svg> Exercise.pdf</span> </p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                            Chapter 2: Data Preprocessing
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="robi-lesson-content">
                                <p class="robi-lesson">Lesson 01: <span><svg xmlns="http://www.w3.org/2000/svg"
                                            width="18" height="19" viewBox="0 0 18 19" fill="none">
                                            <path
                                                d="M9 0.5C4.02944 0.5 0 4.52944 0 9.5C0 14.4706 4.02944 18.5 9 18.5C13.9706 18.5 18 14.4706 18 9.5C17.9947 4.53166 13.9684 0.505311 9 0.5ZM12.7903 9.78676C12.728 9.91176 12.6266 10.0131 12.5016 10.0754V10.0786L7.35879 12.65C7.04118 12.8087 6.65509 12.6799 6.49636 12.3623C6.45123 12.272 6.428 12.1724 6.42856 12.0714V6.92859C6.4284 6.57354 6.71607 6.28561 7.07113 6.28542C7.17098 6.28538 7.26947 6.30859 7.35879 6.35322L12.5016 8.92467C12.8194 9.08302 12.9486 9.469 12.7903 9.78676Z"
                                                fill="white" />
                                        </svg> Introduction.mp4</span> </p>

                            </div>
                            <div class="robi-lesson-content">
                                <p class="robi-lesson">Lesson 02: <span><svg xmlns="http://www.w3.org/2000/svg"
                                            width="14" height="18" viewBox="0 0 14 18" fill="none">
                                            <path
                                                d="M1.58203 18H12.1289C13.0013 18 13.7109 17.2903 13.7109 16.418V5.27344H10.0195C9.14716 5.27344 8.4375 4.56377 8.4375 3.69141V0H1.58203C0.709664 0 0 0.709664 0 1.58203V16.418C0 17.2903 0.709664 18 1.58203 18ZM3.69141 7.41797H10.0195C10.311 7.41797 10.5469 7.65383 10.5469 7.94531C10.5469 8.23679 10.311 8.47266 10.0195 8.47266H3.69141C3.39993 8.47266 3.16406 8.23679 3.16406 7.94531C3.16406 7.65383 3.39993 7.41797 3.69141 7.41797ZM3.69141 9.52734H10.0195C10.311 9.52734 10.5469 9.76321 10.5469 10.0547C10.5469 10.3462 10.311 10.582 10.0195 10.582H3.69141C3.39993 10.582 3.16406 10.3462 3.16406 10.0547C3.16406 9.76321 3.39993 9.52734 3.69141 9.52734ZM3.69141 11.6367H10.0195C10.311 11.6367 10.5469 11.8726 10.5469 12.1641C10.5469 12.4555 10.311 12.6914 10.0195 12.6914H3.69141C3.39993 12.6914 3.16406 12.4555 3.16406 12.1641C3.16406 11.8726 3.39993 11.6367 3.69141 11.6367ZM3.69141 13.7461H7.91016C8.20164 13.7461 8.4375 13.982 8.4375 14.2734C8.4375 14.5649 8.20164 14.8008 7.91016 14.8008H3.69141C3.39993 14.8008 3.16406 14.5649 3.16406 14.2734C3.16406 13.982 3.39993 13.7461 3.69141 13.7461Z"
                                                fill="white" />
                                            <path
                                                d="M10.0195 4.21884H13.4019L9.49219 0.309082V3.6915C9.49219 3.98245 9.72858 4.21884 10.0195 4.21884Z"
                                                fill="white" />
                                        </svg> Exercise.pdf</span> </p>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                            Chapter 3: Linear Regression
                        </button>
                    </h2>
                    <div id="flush-collapseThree" class="accordion-collapse collapse"
                        data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            <div class="robi-lesson-content">
                                <p class="robi-lesson">Lesson 01: <span><svg xmlns="http://www.w3.org/2000/svg"
                                            width="18" height="19" viewBox="0 0 18 19" fill="none">
                                            <path
                                                d="M9 0.5C4.02944 0.5 0 4.52944 0 9.5C0 14.4706 4.02944 18.5 9 18.5C13.9706 18.5 18 14.4706 18 9.5C17.9947 4.53166 13.9684 0.505311 9 0.5ZM12.7903 9.78676C12.728 9.91176 12.6266 10.0131 12.5016 10.0754V10.0786L7.35879 12.65C7.04118 12.8087 6.65509 12.6799 6.49636 12.3623C6.45123 12.272 6.428 12.1724 6.42856 12.0714V6.92859C6.4284 6.57354 6.71607 6.28561 7.07113 6.28542C7.17098 6.28538 7.26947 6.30859 7.35879 6.35322L12.5016 8.92467C12.8194 9.08302 12.9486 9.469 12.7903 9.78676Z"
                                                fill="white" />
                                        </svg> Introduction.mp4</span> </p>
                                <p class="robi-new">New</p>
                            </div>




                        </div>
                        <!-- Modal for approve end -->


                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="robi-select-tags">
        <h1>Select Tags</h1>
        <p class="robi-select-tags-peta">Ignore this if you don’t want to add any tags right now. <br>
            You can always add them later by going to courses section and select the course you want to add tags
            to</p>
        <div class="robi-select-tags-buttons">
            <button class="robi-trending-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15"
                    fill="none">
                    <path
                        d="M8.88464 5.8136C8.84688 5.76629 8.8011 5.77559 8.77712 5.78524C8.75703 5.79339 8.71098 5.81942 8.71735 5.88466C8.72501 5.963 8.7293 6.04288 8.73012 6.12209C8.73354 6.45071 8.60171 6.77265 8.36847 7.00538C8.13671 7.23659 7.83188 7.36117 7.50731 7.35756C7.06396 7.3519 6.69624 7.12066 6.44388 6.68882C6.23522 6.33173 6.32693 5.87118 6.42403 5.38356C6.48085 5.09815 6.53961 4.803 6.53961 4.5221C6.53961 2.3349 5.06923 1.07304 4.19275 0.515528C4.17462 0.504016 4.15737 0.499969 4.14209 0.499969C4.11723 0.499969 4.09754 0.510688 4.08783 0.517251C4.06902 0.529993 4.03892 0.559032 4.0486 0.610438C4.38361 2.38948 3.38436 3.45947 2.32643 4.59226C1.23596 5.75992 0 7.08339 0 9.47028C0 12.2437 2.25632 14.5 5.02975 14.5C7.31328 14.5 9.32662 12.908 9.92578 10.6284C10.3344 9.07412 9.9062 7.09422 8.88464 5.8136ZM5.15523 13.4264C4.46075 13.4581 3.80029 13.209 3.29582 12.7267C2.79677 12.2495 2.51054 11.5835 2.51054 10.8995C2.51054 9.61599 3.0013 8.67373 4.3213 7.42281C4.3429 7.40232 4.36502 7.39584 4.3843 7.39584C4.40177 7.39584 4.41692 7.40118 4.42734 7.40618C4.44929 7.41676 4.48539 7.44296 4.48052 7.49961C4.43332 8.04881 4.43414 8.50466 4.48292 8.85455C4.60761 9.74828 5.26187 10.3488 6.11103 10.3488C6.52736 10.3488 6.92393 10.1921 7.22769 9.90759C7.23942 9.89632 7.25372 9.88809 7.26936 9.88362C7.285 9.87916 7.3015 9.87859 7.31741 9.88197C7.33739 9.88629 7.36416 9.89854 7.37819 9.93236C7.50414 10.2365 7.5685 10.5592 7.56949 10.8917C7.57351 12.2295 6.49048 13.3666 5.15523 13.4264Z"
                        fill="white" />
                </svg>Trending
            </button>
            <button class="robi-trending-btn1">
                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="15" viewBox="0 0 11 15"
                    fill="none">
                    <path
                        d="M7.3519 10.3115L7.9921 9.27269L9.14743 8.87867L9.27542 7.66524L10.1447 6.80951L9.73138 5.66091L10.1447 4.51228L9.27545 3.65656L9.14746 2.44316L7.99213 2.04913L7.35193 1.01026L6.14101 1.15647L5.11237 0.5L4.08372 1.15652L2.87283 1.01032L2.23263 2.04916L1.0773 2.44318L0.949309 3.65659L0.0800781 4.51231L0.493352 5.66094L0.0800781 6.80957L0.949281 7.66527L1.07728 8.8787L2.23261 9.27272L2.8728 10.3116L4.08372 10.1654L5.11237 10.8219L6.14101 10.1654L7.3519 10.3115ZM1.57329 5.66094C1.57329 3.7095 3.16093 2.12187 5.11237 2.12187C7.06381 2.12187 8.65144 3.7095 8.65144 5.66094C8.65144 7.61238 7.06381 9.20002 5.11237 9.20002C3.16093 9.20002 1.57329 7.61238 1.57329 5.66094Z"
                        fill="white" />
                    <path
                        d="M5.11298 2.94269C3.61413 2.94269 2.39474 4.16208 2.39474 5.66093C2.39474 7.15978 3.61413 8.37917 5.11298 8.37917C6.61183 8.37917 7.83122 7.15978 7.83122 5.66093C7.83122 4.16208 6.61183 2.94269 5.11298 2.94269ZM3.89069 11.0155L2.45022 11.1894L1.68916 9.95439L1.43043 9.86618L0.314453 13.3543L2.32353 13.2438L3.89539 14.5L4.82037 11.6089L3.89069 11.0155ZM8.5368 9.95442L7.77572 11.1894L6.33527 11.0155L5.40559 11.6089L6.33057 14.5L7.90243 13.2438L9.91151 13.3543L8.79553 9.86618L8.5368 9.95442Z"
                        fill="white" />
                </svg>Staff Pick
            </button>
            <button class="robi-trending-btn2">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 15 15"
                    fill="none">
                    <path
                        d="M13.9065 7.12967L11.8936 6.55446C10.9871 6.2989 10.1614 5.815 9.49547 5.14906C8.82953 4.48311 8.34563 3.65741 8.09008 2.75095L7.51486 0.738073C7.48593 0.667649 7.43672 0.607415 7.37348 0.565024C7.31024 0.522633 7.23582 0.5 7.15969 0.5C7.08356 0.5 7.00914 0.522633 6.9459 0.565024C6.88266 0.607415 6.83345 0.667649 6.80452 0.738073L6.2293 2.75095C5.97375 3.65741 5.48985 4.48311 4.8239 5.14906C4.15796 5.815 3.33226 6.2989 2.4258 6.55446L0.412922 7.12967C0.335634 7.15161 0.267611 7.19816 0.219174 7.26225C0.170738 7.32635 0.144531 7.4045 0.144531 7.48484C0.144531 7.56518 0.170738 7.64333 0.219174 7.70743C0.267611 7.77153 0.335634 7.81808 0.412922 7.84001L2.4258 8.41523C3.33226 8.67078 4.15796 9.15468 4.8239 9.82063C5.48985 10.4866 5.97375 11.3123 6.2293 12.2187L6.80452 14.2316C6.82646 14.3089 6.873 14.3769 6.9371 14.4254C7.0012 14.4738 7.07935 14.5 7.15969 14.5C7.24003 14.5 7.31818 14.4738 7.38228 14.4254C7.44637 14.3769 7.49292 14.3089 7.51486 14.2316L8.09008 12.2187C8.34563 11.3123 8.82953 10.4866 9.49547 9.82063C10.1614 9.15468 10.9871 8.67078 11.8936 8.41523L13.9065 7.84001C13.9837 7.81808 14.0518 7.77153 14.1002 7.70743C14.1486 7.64333 14.1748 7.56518 14.1748 7.48484C14.1748 7.4045 14.1486 7.32635 14.1002 7.26225C14.0518 7.19816 13.9837 7.15161 13.9065 7.12967Z"
                        fill="white" />
                </svg>Newly Added
            </button>

        </div>
        <!-- robi-approve-and-reject-buttons  -->
        <div class="robi-approve-and-reject-buttons">
            <button data-bs-target="#exampleModalToggle" data-bs-toggle="modal"
                class="robi-approve-and-reject-btn1">Approve Content</button>
            <button data-bs-target="#exampleModalToggle1" data-bs-toggle="modal"
                class="robi-approve-and-reject-btn2">Reject Request</button>
        </div>
        <!-- Modal for approve start -->
        <div class="modal fade" id="exampleModalToggle1" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="robi-alert-modal-icon">
                            <img src="/assets/images/alert.png" alt="">
                        </div>
                        Reject the content addition request for course “Machine Learning
                        A-Z”?
                        <p class="robi-modal-text-action">This action is not reversible!
                        </p>
                        <div class="robi-modal-footer-buttons">
                            <button class="robi-modal-footer-btn1" data-bs-dismiss="modal">Cancel</button>
                            <button class="robi-modal-footer-btn2">Confirm</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal for Confirm start -->
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="robi-alert-modal-icon">
                            <img src="/assets/images/confirm.png" alt="">
                        </div>
                        <p class="robi-modal-text-action"> Content addition request for course “Machine Learning
                            A-Z” is approved!
                        </p>


                    </div>
                </div>

            </div>
        </div>
        <!-- Modal for Confirm end -->
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
@endpush
