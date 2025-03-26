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
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to
                            demonstrate the <code>.accordion-flush</code> class. This is the first item's
                            accordion body.</div>
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
                        <div class="accordion-body">Placeholder content for this accordion, which is intended to
                            demonstrate the <code>.accordion-flush</code> class. This is the second item's
                            accordion body. Let's imagine this being filled with some actual content.</div>
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
                            <!-- robi-approve-and-reject-buttons  -->
                            <div class="robi-approve-and-reject-buttons">
                                <button data-bs-target="#exampleModalToggle" data-bs-toggle="modal"
                                    class="robi-approve-and-reject-btn1">Approve Content</button>
                                <button data-bs-target="#exampleModalToggle1" data-bs-toggle="modal"
                                    class="robi-approve-and-reject-btn2">Reject Request</button>
                            </div>

                            <!-- Modal for approve start -->
                            <div class="modal fade" id="exampleModalToggle1" aria-hidden="true"
                                aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <div class="robi-alert-modal-icon">
                                                <img src="./assets/images/alert.png" alt="">
                                            </div>
                                            Reject the content addition request for course “Machine Learning
                                            A-Z”?
                                            <p class="robi-modal-text-action">This action is not reversible!
                                            </p>
                                            <div class="robi-modal-footer-buttons">
                                                <button class="robi-modal-footer-btn1"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button class="robi-modal-footer-btn2">Confirm</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- Modal for approve end -->
                        <!-- Modal for Confirm start -->
                        <div class="modal fade" id="exampleModalToggle" aria-hidden="true"
                            aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <div class="robi-alert-modal-icon">
                                            <img src="./assets/images/confirm.png" alt="">
                                        </div>
                                        <p class="robi-modal-text-action"> Content addition request for course
                                            “Machine Learning A-Z” is approved!
                                        </p>


                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Modal for Confirm end -->

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
@endpush
