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
        <div class="page-title">Approvals</div>

        <div class="se--approval--layout">
            <div class="se--category-section">
                <button class="se-category-btn">All</button>
                <button class="se-category-btn">
                    pending

                    <p>23</p>
                </button>
                <button class="se-category-btn">
                    Approved

                    <p>24</p>
                </button>
                <button class="se-category-btn">
                    Rejected

                    <p>24</p>
                </button>
            </div>
            <button class="se--show-btn-filters" onclick="openModal('se--modal')">
                Show All Filters
            </button>
        </div>

        <div id="se--modal" class="se--modal" onclick="closeOutside(event, 'se--modal')">
            <div class="se--modal-content" onclick="event.stopPropagation()">
                <span class="se--close-btn" onclick="closeModal('se--modal')">&times;</span>
                <div class="se--filter--content-layout">
                    <div class="se--filter--course-category">
                        <p class="se--filter-title">Filter By Course Category</p>

                        <div class="se--category-section">
                            <button type="button" class="se-category-btn">All</button>
                            <button class="se-category-btn">
                                Programming

                                <p>23</p>
                            </button>
                            <button class="se-category-btn">
                                Design

                                <p>24</p>
                            </button>
                            <button class="se-category-btn">
                                Data analysis

                                <p>24</p>
                            </button>
                            <button class="se-category-btn">
                                Database

                                <p>24</p>
                            </button>
                            <button class="se-category-btn">
                                AI

                                <p>24</p>
                            </button>
                            <button class="se-category-btn">
                                Cloud Computing

                                <p>24</p>
                            </button>
                        </div>
                    </div>

                    <div class="se--filter--course-category">
                        <p class="se--filter-title">Filter By Request Type</p>

                        <div class="se--category-section">
                            <button type="button" class="se-category-btn">All</button>
                            <button class="se-category-btn">
                                New Course

                                <p>360</p>
                            </button>
                            <button class="se-category-btn">
                                Content Addition

                                <p>24</p>
                            </button>
                            <button class="se-category-btn">
                                Content Removal

                                <p>24</p>
                            </button>
                        </div>
                    </div>

                    <div class="se--filter--course-category">
                        <p class="se--filter-title">Filter By Status</p>

                        <div class="se--category-section">
                            <button type="button" class="se-category-btn">All</button>
                            <button class="se-category-btn">
                                Pending

                                <p>360</p>
                            </button>
                            <button class="se-category-btn">
                                Approved

                                <p>24</p>
                            </button>
                            <button class="se-category-btn">
                                Rejected

                                <p>24</p>
                            </button>
                        </div>
                    </div>

                    <div class="se--show-btn-layout">
                        <button class="se--show-results">Show 4 Results</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="se--course-table-layout">
        <!-- select menu -->
        <div class="se--course-table-layout">
            <!-- Select Menu -->
            <div class="se-table-container">
                <table class="se-table">
                    <thead class="se-thead">
                        <tr class="se-tr">
                            <th class="se-th">Course Name</th>
                            <th class="se-th">Instructor</th>
                            <th class="se-th">Category</th>
                            <th class="se-th">Request Type</th>
                            <th class="se-th">Status</th>
                            <th class="se-th"></th>
                        </tr>
                    </thead>
                    <tbody class="se-tbody">
                        <tr class="se-tr">
                            <td class="se-td">Free Artificial Intelligence Course</td>
                            <td class="se-td">Tadhg Kavanagh</td>
                            <td class="se-td">AI</td>
                            <td class="se-td">New Course</td>
                            <td class="se-td">
                                <span class="se--pending-btn">Pending</span>
                            </td>
                            <td class="se-td">
                                <a href="{{ route('admin.approval.view') }}" class="se-button">Take Action</a>
                            </td>
                        </tr>
                        <tr class="se-tr">
                            <td class="se-td">Beginning C++ Programming</td>
                            <td class="se-td">Eamon Kelly</td>
                            <td class="se-td">Programming</td>
                            <td class="se-td">Content Addition</td>
                            <td class="se-td">
                                <span class="se--rejected-btn">Rejected</span>
                            </td>
                            <td class="se-td">
                                <a href="{{ route('admin.approval.content') }}" class="se-button">View</a>
                            </td>
                        </tr>
                        <tr class="se-tr">
                            <td class="se-td">Web Development Crash Course</td>
                            <td class="se-td">Sarah Johnson</td>
                            <td class="se-td">Web Development</td>
                            <td class="se-td">Content Removal</td>
                            <td class="se-td">
                                <span class="se--approved-btn">Pending</span>
                            </td>
                            <td class="se-td">
                                <a href="{{ route('admin.approval.view') }}" class="se-button">Take Action</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
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
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
@endpush
