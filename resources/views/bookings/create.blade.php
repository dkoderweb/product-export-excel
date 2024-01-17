@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Booking</h2>

        <form action="{{ route('bookings.store') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="products">Select products:</label>
                <select class="form-control" id="products" name="products[]" multiple="multiple" required>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-discount="{{ $product->discount }}">{{ $product->product_name }}</option>
                    @endforeach
                </select>
            </div>

            <div id="selected-products">
            </div>

            <div class="mb-3">
                <label for="paid_amount">Paid Amount:</label>
                <input type="text" class="form-control" id="paid_amount" name="paid_amount" required readonly>
            </div>

            <div class="mb-3">
                <label for="discount_amount">Discount Amount:</label>
                <input type="text" class="form-control" id="discount_amount" name="discount_amount" required readonly>
            </div>

            <div class="mb-3">
                <label for="total_amount">Total Amount:</label>
                <input type="text" class="form-control" id="total_amount" name="total_amount" required readonly>
            </div>

            <button type="submit" class="btn btn-primary">Create Booking</button>
        </form>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $('#products').select2();

            $('#products').on('change', function () {
                updateSelectedProducts();
            });

            function updateSelectedProducts() {
                var selectedProducts = $('#products').select2('data');

                $('#selected-products').empty();

                selectedProducts.forEach(function (product) {
                    var price = parseFloat(product.element.getAttribute('data-price'));
                    var discount = parseFloat(product.element.getAttribute('data-discount'));

                    $('#selected-products').append('<div><strong>' + product.text + '</strong> - Price: ' + price + ' - Discount: ' + discount + '</div>');
                });

                updateTotalAmount();
            }

            function updateTotalAmount() {
                var totalPaidAmount = 0;
                var totalDiscountAmount = 0;
                var totalAmount = 0;

                $('#products').select2('data').forEach(function (product) {
                    var price = parseFloat(product.element.getAttribute('data-price'));
                    var discount = parseFloat(product.element.getAttribute('data-discount'));

                    totalPaidAmount += price;
                    totalDiscountAmount += discount;
                    totalAmount += price -  discount;
                });

                $('#paid_amount').val(totalPaidAmount.toFixed(2));
                $('#discount_amount').val(totalDiscountAmount.toFixed(2));
                $('#total_amount').val(totalAmount.toFixed(2));
            }
        });
    </script>
@endsection
