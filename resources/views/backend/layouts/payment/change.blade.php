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
@endpush
@section('content')
    <div class="page-header">
        <div class="desktop-sidebar-closer-container">
            <img class="desktop-sidebar-closer" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
        <div>
            <img class="menu-btn-mobile" src="{{ asset('backend/assets/images/menu.png') }}" alt="" />
        </div>
    </div>
    <div class="se--main-layout">
        <div class="page-title">Change Payment Method</div>

        {{-- <div class="se--category-section">
            <button class=" se-category-btn">
                <img src="{{ asset('backend/assets/images/visa.png') }}" alt="">
                1122
            </button>
            <button class=" se-category-btn">
                <span>Exp:</span>09/32
            </button>
        </div> --}}

        <form action="{{ route('admin.payments.method.update') }}" method="post">
            @csrf
            <label for="">STRIPE_PUBLIC_KEY</label>
            <div class="bg-black py-2 border rounded gap-3 px-3 ak-w-34 d-flex justify-content-between border-dark">
                <input type="text" name="STRIPE_SECRET" placeholder="Enter Stripe Public Key" value="{{ old('STRIPE_SECRET', env('STRIPE_PUBLIC')) }}"  class="bg-black text-white border-0 @error('STRIPE_SECRET') is-invalid @enderror">
                @error('STRIPE_SECRET')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <br>
            <label for="">STRIPE_SECRET_KEY</label>
            <div class="bg-black py-2 border rounded gap-3 px-3 ak-w-34 d-flex justify-content-between border-dark">
                <input type="text" name="STRIPE_SECRET" placeholder="Enter Stripe Secret Key" value="{{ old('STRIPE_SECRET', env('STRIPE_SECRET')) }}" class="bg-black text-white border-0 @error('STRIPE_SECRET') is-invalid @enderror">

                @error('STRIPE_SECRET')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            </div>
            
            <br/>

        <button type="submit" class="btn btn-light fixed-width" >Change Payment
            Method</button>
        </form>


        <!-- Payment Confirmation Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content ak-bg-black text-white p-4 rounded">
                    <div class="modal-header border-0 d-flex flex-column justify-content-center align-content-center">
                        <img src="./assets/images/caution.png" class="mx-auto" alt="">
                        <p class=" ">
                            You are about to change your current payment method. After which, all the payments
                            will be transferred from your new payment method
                        </p>
                    </div>
                    <div class="modal-footer border-0 d-flex w-100 p-0">
                        <button type="button" class="btn btn-outline-black text-white border py-2 rounded-0 flex-grow-1"
                            data-dismiss="modal">Cancel</button>
                        <button type="button"
                            class="btn btn-warning confirm_btn py-2 rounded-0 flex-grow-1">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            // Ensure previous modal is closed before opening success modal
            $(".confirm_btn").on("click", function() {
                $("#exampleModalCenter").modal("hide"); // Close the payment modal
            });

            // Wait until the payment modal is fully hidden before showing the success modal
            $("#exampleModalCenter").on("hidden.bs.modal", function() {
                $("#successModal").modal("show");
            });

            // Ensure close button properly hides the success modal
            $("#successModal .close, #successModal .btn-danger").on("click", function() {
                $("#successModal").modal("hide");
            });
        });
    </script>
@endpush
