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
                    @foreach ($tags as $tag)
                        <button class="se-trending-btn" style="background-color: #FFA640;">
                            <p>{{ $tag->name }}</p>
                        </button>
                    @endforeach
                    <button class="se--add-tags">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16"
                            fill="none">
                            <path d="M8.17383 0V16M0.173828 8H16.1738" stroke="white" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                </div>


                <!-- input tags -->
                <form action="{{ route('admin.tags.store') }}" method="post">
                    @csrf
                    <div class="se--input-tags-layout" id="tag-input-add">
                        <div class="se--input--tags">
                            <input type="text" placeholder="Tag Name" name="name" class="category__input @error('name') is-invalid @enderror" />

                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <button type="submit" class="se-add-tag">
                            Add
                        </button>

                    </div>
                </form>

            </div>

            <!-- category sections -->
            <div class="se-tags-layout">
                <p class="se--subtext">Category</p>
                <div class="se--Category">
                    @foreach ($categories as $category)
                        <button class="se--category-button">
                            {{ $category->name }}
                        </button>
                    @endforeach

                    <button class="se--add-category">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                            fill="none">
                            <path d="M8 0V16M0 8H16" stroke="#1A1A1A" stroke-width="3" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>


                </div>
                <form method="post" action="{{ route('admin.categories.store') }}" >
                    @csrf
                    <div class="se--input-tags-layout" id="category-input-add">
                        <div class="se--input--tags">
                            <input type="text" name="name" placeholder="Category Name"
                                class="category__input @error('name') is-invalid @enderror" value="{{ old('name') }}" />
                        </div>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <button type="submit" class="se-add-tag">
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
@endpush
