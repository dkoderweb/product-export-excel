@extends('layouts.app')

@section('content')
<div class="header_div">
    <h2>Booking List</h2>
    <a href="{{ route('bookings.create') }}" class="btn btn-success">Add Booking</a> 
</div>

<div>
    <table class="table table-bordered table-hover" id="booking-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Details</th>
                <th>Total Price</th>
                <th>Total Discount</th>
                <th>Total Balance Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('script')
<script>
    var table;
    $(document).ready(function () {
        table = $('#booking-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('bookings.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'product_details', name: 'product_details'},
                { data: 'total_price', name: 'total_price' },
                { data: 'total_discount', name: 'total_discount' },
                { data: 'total_balance_amount', name: 'total_balance_amount' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#booking-table').on('click', '.delete-btn', function () {
            var data = table.row($(this).closest('tr')).data();
            $('#deleteBookingModal').modal('show');

            $('#confirmDeleteBooking').off('click').on('click', function () {
                $.ajax({
                    url: "{{ route('bookings.destroy', ['booking' => '__id__']) }}".replace('__id__', data.id),
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (response) {
                        if (response.success) {
                            $('#deleteBookingModal').modal('hide');
                            table.draw();
                        }
                    }
                });
            });
        });

       
    });
</script>
@endsection
