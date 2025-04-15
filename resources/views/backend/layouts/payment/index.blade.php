@extends('backend.app')
@section('title', 'Users')
@push('style')
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiq.css') }}" />
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ashiqResponsive.css') }}" />


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
        <!-- <a href="" class="button-lg">Register New Instructor</a> -->
    </div>
    <div class="se--main-layout">
        <div class="page-title">Payments</div>
        {{-- <p class="se--subtext">Payment Method</p> --}}

        {{-- <div class="se--category-section">
            <button class=" se-category-btn">
                <img src="{{ asset('backend/assets/images/visa.png') }}" alt="">
                1122
            </button>
            <button class=" se-category-btn">
                <span>Exp:</span>09/32
            </button>
        </div> --}}

        <a href="{{ route('admin.payments.method.change') }}">
            <button type="button" class="btn btn-light fixed-width"><i class="fa-solid fa-pen"></i>Change
                Payment
                Method</button>
        </a>

        <!-- pending payment table  -->
        <div class="se--table--layout">
            <div class="se--table--row1">
                <p class="se--subtext">Pending Payments</p>
            </div>
            <div class="se-table-container">
                <table class="se-table">
                    <thead class="se-thead">
                        <tr class="se-tr">
                            <th class="se-th">Instructor</th>
                            <th class="se-th">ID</th>
                            <th class="se-th">Total Earnings</th>
                            <th class="se-th">Last Payment Date</th>
                            <th class="se-th">Last Payment Amount</th>
                            <th class="se-th">Due</th>
                            <th class="se-th"></th>
                        </tr>
                    </thead>
                    <tbody class="se-tbody">
                        <tr class="se-tr">
                            <td class="se-td">Tadhg Kavanagh</td>
                            <td class="se-td">3617</td>
                            <td class="se-td">€ 7376</td>
                            <td class="se-td">27/09/2024</td>
                            <td class="se-td">€ 432</td>
                            <td class="se-td">€ 891</td>
                            <td class="se-td"><button class="ak-button" data-toggle="modal"
                                    data-target="#exampleModalCenter">Pay</button></td>
                        </tr>

                        <tr class="se-tr">
                            <td class="se-td">Tadhg Kavanagh</td>
                            <td class="se-td">3617</td>
                            <td class="se-td">€ 7376</td>
                            <td class="se-td">27/09/2024</td>
                            <td class="se-td">€ 432</td>
                            <td class="se-td">€ 891</td>
                            <td class="se-td"><button class="ak-button" data-toggle="modal"
                                    data-target="#exampleModalCenter">Pay</button></td>
                        </tr>

                        <tr class="se-tr">
                            <td class="se-td">Tadhg Kavanagh</td>
                            <td class="se-td">3617</td>
                            <td class="se-td">€ 7376</td>
                            <td class="se-td">27/09/2024</td>
                            <td class="se-td">€ 432</td>
                            <td class="se-td">€ 891</td>
                            <td class="se-td"><button class="ak-button" data-toggle="modal"
                                    data-target="#exampleModalCenter">Pay</button></td>
                        </tr>
                        <tr class="se-tr">
                            <td class="se-td">Tadhg Kavanagh</td>
                            <td class="se-td">3617</td>
                            <td class="se-td">€ 7376</td>
                            <td class="se-td">27/09/2024</td>
                            <td class="se-td">€ 432</td>
                            <td class="se-td">€ 891</td>
                            <td class="se-td"><button class="ak-button" data-toggle="modal"
                                    data-target="#exampleModalCenter">Pay</button></td>
                        </tr>
                        <tr class="se-tr">
                            <td class="se-td">Tadhg Kavanagh</td>
                            <td class="se-td">3617</td>
                            <td class="se-td">€ 7376</td>
                            <td class="se-td">27/09/2024</td>
                            <td class="se-td">€ 432</td>
                            <td class="se-td">€ 891</td>
                            <td class="se-td"><button class="ak-button" data-toggle="modal"
                                    data-target="#exampleModalCenter">Pay</button></td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- payment history table -->
        <div class="se--table--layout">
            <div class="se--table--row1">
                <p class="se--subtext">Payment History</p>
            </div>
            <div class="se-table-container">
                <table class="se-table">
                    <thead class="se-thead">
                        <tr class="se-tr">
                            <th class="se-th">Instructor Name</th>
                            <th class="se-th">Amount Paid</th>
                            <th class="se-th">Payment Date</th>
                            <th class="se-th"></th>
                        </tr>
                    </thead>
                    <tbody class="se-tbody">
                        @foreach ($payment_history as $history)
                            <tr class="se-tr">
                                <td class="se-td">{{ $history->instructor->user->first_name }}
                                    {{ $history->instructor->user->last_name }}</td>
                                <td class="se-td">€ {{ $history->price }}</td>
                                <td class="se-td">{{ \Carbon\Carbon::parse($history->created_at)->format('d/m/Y h:i A') }}
                                </td>
                                <td class="se-td"></td>
                            </tr>
                        @endforeach

                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Confirmation Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="p-4 text-white rounded modal-content ak-bg-black">
                    <div class="border-0 modal-header">
                        <h5 class="mx-auto modal-title">Transfer € 891 to Tadhg Kavanagh</h5>
                    </div>
                    <div class="p-0 border-0 modal-footer d-flex w-100">
                        <button type="button" class="py-2 text-white border btn btn-outline-black rounded-0 flex-grow-1"
                            data-dismiss="modal">Cancel</button>
                        <button type="button"
                            class="py-2 btn btn-danger confirm_btn rounded-0 flex-grow-1">Confirm</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="p-4 text-white rounded modal-content ak-bg-black">
                    <div class="border-0 modal-header position-relative">

                        <!-- Close Icon -->
                        <button type="button" class="close position-absolute" style="right: 1rem; top: 1rem;"
                            data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="text-white fa-solid fa-close"></i></span>
                        </button>
                    </div>
                    <div class="text-center modal-body">
                        <img src="./assets/images/confirmed.png" alt="">
                        <p class="ak-mt-3">Amount transferred!</p>
                    </div>
                    <div class="p-0 border-0 modal-footer d-flex w-100">

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
