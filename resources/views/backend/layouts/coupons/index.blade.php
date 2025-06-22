@extends('backend.app')
@section('title', 'Coupons')
@push('style')
<!-- custom css -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/ashiq.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/css/ashiqResponsive.css') }}" />

<link rel="stylesheet" href="{{ asset('backend/assets/css/akash.css') }}" />
<link rel="stylesheet" href="{{ asset('backend/assets/css/akashResponsive.css') }}" />
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
    <div class="page-title">Add Coupon</div>

    <form action="{{ route('admin.coupon.store') }}" method="post">
        @csrf
        <label for="">Code</label>
        <div class="gap-3 px-3 py-2 bg-white border rounded ak-w-34 d-flex justify-content-between border-dark">
            <input type="text" name="code" placeholder="Enter Coupon Code" value="{{ old('code') }}"
                class="bg-white text-black border-0 @error('code') is-invalid @enderror">
            @error('code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <br>
        <label for="">Percent Off</label>
        <div class="gap-3 px-3 py-2 bg-white border rounded ak-w-34 d-flex justify-content-between border-dark">
            <input type="text" name="percent_off" placeholder="Enter Percent Off" value="{{ old('percent_off') }}"
                class="bg-white text-black border-0 @error('percent_off') is-invalid @enderror">

            @error('percent_off')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <br>
        <label for="">Valid Until</label>
        <div class="gap-3 px-3 py-2 bg-white border rounded ak-w-34 d-flex justify-content-between border-dark">
            <input type="date" name="valid_until" placeholder="Enter Percent Off" value="{{ old('valid_until') }}"
                class="bg-white text-black border-0 @error('valid_until') is-invalid @enderror ">

            @error('valid_until')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <br />

        <button type="submit" class="btn btn-light fixed-width">Add Coupon</button>
    </form>

    <div class="se--table--layout">
        <div class="se--table--row1">
            <p class="se--subtext">Coupons List</p>

        </div>
        <div class="se-table-container">
            <table class="se-table">
                <thead class="se-thead">
                    <tr class="se-tr">
                        <th class="se-th">Code</th>
                        <th class="se-th">Percent Off</th>
                        <th class="se-th">Valid Until</th>
                        <th class="se-th">Status</th>
                        <th class="text-center se-th">Action</th>
                    </tr>
                </thead>
                <tbody class="se-tbody">
                    @forelse ($coupons as $coupon)
                    <tr class="se-tr" id="row-{{ $coupon->id }}">
                        <td class="se-td">
                            {{ $coupon->code }}
                        </td>
                        <td class="se-td">
                            {{ round($coupon->percent_off) }}%
                        </td>
                        <td class="se-td">
                            {{ $coupon->valid_until }}
                        </td>

                        <td class="se-td">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                    onclick="showStatusChangeAlert({{ $coupon->id }})"
                                    id="customSwitch{{ $coupon->id }}" {{ $coupon->is_active == 1 ? 'checked' : ''
                                }}>
                            </div>
                        </td>
                        <td class="se-td">
                            <button type='button' onclick="showDeleteConfirm({{ $coupon->id }})"
                                class="bg-transparent border-0 text-decoration-underline fw-bold text-danger">Delete</button>
                        </td>
                    </tr>
                    @empty
                    <tr class="text-center">
                        <td colspan="30">
                            <p class="py-4" style="color: red">No Coupons Found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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
            let url = "{{ route('admin.coupon.destroy', ':id') }}";
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
                        $("#row-" + id).remove();
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

    // Status Change Confirm Alert
        function showStatusChangeAlert(id) {
            event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'Block this Instructor?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                } else {
                    const checkbox = document.getElementById('customSwitch' + id);
                    checkbox.checked = !checkbox.checked;
                }
            });
        }

        // Status Change
        function statusChange(id) {
            let url = "{{ route('admin.coupon.status', ':id') }}".replace(':id', id);

            $.ajax({
                type: "POST",
                url: url,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
                },
                success: function(resp) {
                    if (resp.success) {
                        const checkbox = document.getElementById('customSwitch' + id);
                        const statusBtn = document.getElementById('statusBtn' + id);

                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.errors ? resp.errors[0] : resp.message);
                    }
                },
                error: function() {
                    toastr.error('Something went wrong!');
                }
            });
        }
</script>
@endpush