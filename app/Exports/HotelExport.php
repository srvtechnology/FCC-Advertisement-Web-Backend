<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HotelExport implements FromArray, WithHeadings
{
    protected $hotels;

    public function __construct(array $hotels)
    {
        $this->hotels = $hotels;
    }

    public function array(): array
    {
        return $this->hotels;
    }

    public function headings(): array
    {
        return [
            'HotelCode', 'Name', 'Address', 'Location', 'Lat', 'Lon', 'Country ISO',
            'Hotelbeds Hotel ID', 'Category', 'Mail', 'Phone', 'Web', 'PlaceID',
            'GiataID', 'TTIID', 'IsActive'
        ];
    }
}
