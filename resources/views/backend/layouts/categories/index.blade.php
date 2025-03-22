@extends('backend.app')
@section('title', 'Categories & Tags')
@push('style')
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
        <div class="page-title">Manage Tags & Categories</div>


        <div class="se--tag--category--layout">
            <!-- tags section -->
            <div class="se-tags-layout">
                <p class="se--subtext">Tags</p>
                <div class="se--allTags">
                    <button class="se-trending-btn" style="background-color: #FFA640;">


                        <p>Trending</p>
                    </button>
                    <button class="se-trending-btn" style="background-color: #29FF65; ">

                        <p>Newly Added</p>
                    </button>

                    <button class="se-trending-btn" style="background-color: #FF4040; color: white;">

                        <p>Staff Pick</p>
                    </button>

                    <button class="se--add-tags">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16"
                            fill="none">
                            <path d="M8.17383 0V16M0.173828 8H16.1738" stroke="white" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                </div>
                <!-- input tags -->
                <div class="se--input-tags-layout" id="tag-input-add">
                    <div class="se--input--tags">
                        <input type="text" placeholder="Tag Name" class="se--tag" />

                    </div>
                    <button class="se-add-tag">
                        Add
                    </button>

                </div>

            </div>

            <!-- category sections -->
            <div class="se-tags-layout">
                <p class="se--subtext">Category</p>
                <div class="se--Category">
                    <button class="se--category-button">
                        Programming
                    </button>
                    <button class="se--category-button">
                        Design
                    </button>
                    <button class="se--category-button">
                        Data Analysis
                    </button>
                    <button class="se--category-button">
                        Database
                    </button>
                    <button class="se--category-button">
                        AI
                    </button>
                    <button class="se--category-button">
                        Cloud Computing
                    </button>

                    <button class="se--add-category">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M8 0V16M0 8H16" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>


                </div>
                <form method="post" id="create-form">
                    @csrf
                    <div class="se--input-tags-layout" id="category-input-add">

                        <div class="se--input--tags">
                            <input type="text" placeholder="Category Name" class="se--tag" />

                        </div>
                        <button class="se-add-tag">
                            Add
                        </button>
                    </div>
                </form>

            </div>

        </div>

    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#create-form').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();
                let url = "{{ route('admin.categories.store') }}";
                $.ajax({
                    url,
                    method: 'POST',
                    data: formData,
                    success: function (resp) {
                        // Reload DataTable
                        $('#basic_tables').DataTable().ajax.reload();
                        if (resp.success === true) {
                            // show toast message
                            flasher.success(resp.message);
                            clearModal('create-category')
                        } else if (resp.errors) {
                            flasher.error(resp.errors[0]);
                        } else {
                            flasher.error(resp.message);
                        }
                    },
                    error: function (xhr) {
                        handleXhrErrors(xhr,'create-category')
                    }
                });
            });
        });
    </script>
@endpush
