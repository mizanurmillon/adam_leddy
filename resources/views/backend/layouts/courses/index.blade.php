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
            <img class="desktop-sidebar-closer" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
        <div>
            <img class="menu-btn-mobile" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
        <!-- <a href="" class="button-lg">Register New Instructor</a> -->
    </div>

    <div class="se--main-layout">
        <div class="page-title">Courses</div>
        <div class="se--category-section">
            <button class=" se-category-btn">
                All
            </button>
            <button class=" se-category-btn">
                Programming
            </button>
            <button class=" se-category-btn">
                Design
            </button>
            <button class=" se-category-btn">
                Data Analysis
            </button>
            <button class=" se-category-btn">
                Database
            </button>
            <button class=" se-category-btn">
                AI
            </button>
            <button class=" se-category-btn">
                Cloud Computing
            </button>

        </div>

        <div class=" se--course-table-layout">
            <!-- select menu -->
            <div class="se--course-table-layout">
                <!-- Select Menu -->
                <div class="se-select-layout">
                    <select class="se--select-menu">
                        <option value="default">Sort By Default</option>
                        <option value="recent">Recently Edited</option>
                        <option value="watch-high">Watch Time (High To Low)</option>
                        <option value="watch-low">Watch Time (Low To High)</option>
                    </select>

                </div>
                <div class="se-table-container">
                    <table class="se-table">
                        <thead class="se-thead">
                            <tr class="se-tr">
                                <th class="se-th">Name</th>
                                <th class="se-th">Instructor</th>
                                <th class="se-th">Watch Time</th>
                                <th class="se-th">Category</th>
                                <th class="se-th"></th>
                            </tr>
                        </thead>
                        <tbody class="se-tbody">
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>
                            <tr class="se-tr">
                                <td class="se-td">Free Artificial Intelligence Course</td>
                                <td class="se-td">Tadhg Kavanagh</td>
                                <td class="se-td">22 H</td>
                                <td class="se-td">Programming</td>

                                <td class="se-td"><a href="{{ route('admin.courses.content') }}" class="se--view-btn">View Details</a>
                                </td>
                            </tr>

                        </tbody>
                    </table>


                </div>
                <div class="robi-pagination">
                    <button class="robi-page active">1</button>
                    <button class="robi-page">2</button>
                    <button class="robi-page">3</button>
                    <span class="robi-dots">...</span>
                    <button class="robi-page">78</button>
                    <button class="robi-page">79</button>
                    <button class="robi-page">79</button>
                    <button class="robi-page">80</button>
                </div>
            </div>


        </div>

    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
@endpush
