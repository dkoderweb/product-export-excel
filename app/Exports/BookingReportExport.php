<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bookingId;

    public function __construct($bookingId)
    {
        $this->bookingId = $bookingId;
    }

    public function collection()
    {
        return Booking::with('products')->where('id', $this->bookingId)->get();
    }

    public function headings(): array
    {
        $firstRow = [
            'Booking Date',
            'Booking Id',
            'User First Name',
            'User last name',
            'User Email',
            'User Mobile Number',
        ];

        $secondRow = [
            '',
            '',
            '',
            '',
            '',
            '',
        ];
        $products = Booking::with('products')->where('id', $this->bookingId)->get();
        $booking = $products->first();
 
        $products = $booking->products; 

        foreach ($products as $key => $product) {
            $firstRow[] = '';
            $firstRow[] = 'Product ' . ($key + 1);
            $firstRow[] = '';

            $secondRow[] = 'Name';
            $secondRow[] = 'Price';
            $secondRow[] = 'Discount';
        }
        $firstRow[]  = 'Total Paid Price';
        $firstRow[]  = 'Total Discount';
        $firstRow[]  = 'Total Amount';


        return [$firstRow, $secondRow];
    }
    
    

    public function map($booking): array
    {
        $rows = [
            [
                'Booking Date' => $booking->created_at->format('Y-m-d'),
                'Booking Id' => $booking->id,
                'User First Name' => $booking->user ? $booking->user->first_name : '',
                'User Last Name' => $booking->user ? $booking->user->last_name : '',
                'User Email' => $booking->user ? $booking->user->email : '',
                'User Mobile Number' => $booking->user ? $booking->user->mobile_number : '',
            ]
        ];
    
        foreach ($booking->products as $product) {
            $rows[0]['Product ' . $product->id . ' Name Detail'] = $product->product_name;
            $rows[0]['Product ' . $product->id . ' Price Detail'] = $product->price;
            $rows[0]['Product ' . $product->id . ' Discount Detail'] = $product->discount;
        }
    
        $rows[0]['Total Paid Price'] = $booking->paid_amount;
        $rows[0]['Total Discount'] = $booking->products->sum('discount');
        $rows[0]['Total Amount'] = $booking->total_amount;

    
        return $rows;
    }
    
    


    
}
