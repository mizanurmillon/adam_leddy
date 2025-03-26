@extends('backend.app')
@section('title', 'Instructors Create')
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
        <div class="page-title">Instructors Create</div>
        <div class="se--overview--layout">
            <div class="row">
                <div class="col-md-12">
                    <div class="card bg-black">
                        <div class="card-body">
                            <form action="{{ route('admin.instructors.store') }}" method="POST">
                                <div class="mb-3">
                                    <label for="f_name" class="form-label text-white" style="font-weight: bold">First Name</label>
                                    <input type="text" class="form-control bg-black" id="f_name" name="first_name" aria-describedby="emailHelp" placeholder="First Name">
                                  </div>
                                  <div class="mb-3">
                                    <label for="l_name" class="form-label text-white " style="font-weight: bold">Last Name</label>
                                    <input type="text" class="form-control bg-black" id="l_name" name="last_name" aria-describedby="emailHelp" placeholder="Last Name">
                                  </div>
                                <div class="mb-3">
                                  <label for="exampleInputEmail1" class="form-label text-white" style="font-weight: bold">Email address</label>
                                  <input type="email" class="form-control bg-black" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                                  <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="expertise" class="form-label text-white" style="font-weight: bold">Expertise</label>
                                    <input type="text" class="form-control bg-black" id="expertise" name="expertise" placeholder="Expertise">
                                  </div>
                                <div class="mb-3">
                                  <label for="exampleInputPassword1" class="form-label text-white" style="font-weight: bold">Password</label>
                                  <input type="password" class="form-control bg-black" id="exampleInputPassword1" name="password" placeholder="Password">
                                </div>
                               
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('admin.instructors.index') }}" class="btn btn-danger">Cancel</a>
                              </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('backend/assets/js/setu.js') }}"></script>
@endpush
