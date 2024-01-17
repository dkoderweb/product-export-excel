<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Product;
use App\Models\BookingProduct;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\BookingReportExport;


class BookingController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $bookings = Booking::with('products')->latest()->get();

            return DataTables::of($bookings)
            ->addColumn('product_details', function ($booking) {
                $productDetails = $booking->products->map(function ($product) {
                    return sprintf('%s (Price: %s, Discount: %s)', $product->product_name, $product->price, $product->discount);
                })->implode('<br>');

                return $productDetails;
            })

                ->addColumn('total_price', function ($booking) {
                    return $booking->paid_amount;
                })
                ->addColumn('total_discount', function ($booking) {
                    return $booking->discount_amount;
                })
                ->addColumn('total_balance_amount', function ($booking) {
                   
                        return $booking-> total_amount;
                   
                })
                ->addColumn('action', function ($booking) {
                    return '<a href="' . url('bookings/export-booking-report/' . $booking->id) . '" class="btn btn-success export-btn" role="button">Export</a>';
                }) 
                ->rawColumns(['product_details','action'])  
                ->make(true);
        }

        return view('bookings.index');
    }
    public function exportBookingReport($id)
    {
        return Excel::download(new BookingReportExport($id), 'booking_report.xlsx');
    }

    public function create()
    {
        $products = Product::all();
        return view('bookings.create', compact('products'));
    } 

    public function store(Request $request)
    {
        $booking = Booking::create([
            'paid_amount' => $request->input('paid_amount'),
            'discount_amount' => $request->input('discount_amount'),
            'total_amount' => $request->input('total_amount'),
            'user_id' => auth()->user()->id,
        ]);

        foreach ($request->input('products') as $productId) {
            $product = Product::find($productId);

            if ($product) {
                BookingProduct::create([
                    'booking_id' => $booking->id,
                    'product_id' => $productId,
                    'price' => $product->price,
                    'discount' => $product->discount,
                ]);
            }
        }

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully.');
    }
}
