@extends('layouts.app') 

@section('content')
<div class="header_div">
    <h2>Product List</h2>
    <button class="btn btn-success" id="addProductBtn">Add Product</button>
</div>

<div>
    <table class="table table-bordered table-hover" id="product-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Discount</th>
                <th>Actions</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal for Add/Edit Product -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    @csrf
                    <input type="hidden" name="id" id="product_id">
                    <div class="mb-3">
                        <label for="product_name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                        <div class="text-danger" id="product_name_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                        <div class="text-danger" id="price_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="discount" class="form-label">Discount</label>
                        <input type="number" class="form-control" id="discount" name="discount" required>
                        <div class="text-danger" id="discount_error"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveProductButton" onclick="saveProduct()">Save Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var table;
    $(document).ready(function () {
        table = $('#product-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.index') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'product_name', name: 'product_name' },
                { data: 'price', name: 'price' },
                { data: 'discount', name: 'discount' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        $('#addProductBtn').click(function () {
            $('#productModalLabel').text('Add Product');
            $('#productForm')[0].reset();
            $('#productModal').modal('show');
        });

        $('#productForm').submit(function (e) {
            e.preventDefault();
            saveProduct();
            table.draw();
        });

        // Edit Product
        $('#product-table').on('click', '.edit-btn', function () {
            var data = table.row($(this).closest('tr')).data();
            $('#productModalLabel').text('Edit Product');
            $('#product_id').val(data.id);
            $('#product_name').val(data.product_name);
            $('#price').val(data.price);
            $('#discount').val(data.discount);
            $('#productModal').modal('show');
        });

        // Delete Product
        $('#product-table').on('click', '.delete-btn', function () {
                var data = table.row($(this).closest('tr')).data();
                var confirmDelete = confirm('Are you sure you want to delete this product?');

                if (confirmDelete) {
                    $.ajax({
                        url: "{{ route('products.destroy', ['product' => '__id__']) }}".replace('__id__', data.id),
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            if (response.success) {
                                table.draw();
                            }
                        }
                    });
                }
            });
    });

    function saveProduct() {
        var id = $('#product_id').val();
        var formData = {
            'product_name': $('#product_name').val(),
            'price': $('#price').val(),
            'discount': $('#discount').val()
        };

        var url = id ? "{{ route('products.update', ['product' => '__id__']) }}" : "{{ route('products.store') }}";
        url = url.replace('__id__', id);

        $.ajax({
            url: url,
            method: id ? 'PUT' : 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function (response) {
                if (response.success) {
                    $('#productModal').modal('hide');
                    table.draw();
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    displayValidationError('product_name', errors.product_name);
                    displayValidationError('price', errors.price);
                    displayValidationError('discount', errors.discount);
                }  
            }
        });
    }
    function displayValidationError(field, messages) {
        $('#' + field + '_error').html(messages ? messages.join('<br>') : '');
    }
</script>
@endsection
