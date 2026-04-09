<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Freetown City Council</title>
        <style>
        body {
        font-family: Arial, sans-serif;
        text-align: center;
        margin: 20px;
        /*background: url('{{storage_path('app/public/image4.png')}}') no-repeat center center;*/
        background-size: cover;
        /*  opacity: 0.1;
        z-index: -1;*/
        }

        .watermark {
    /* position: relative; */
}
.watermark:before{
content: '';
display: block;
width: 100%;
background: url('{{storage_path('app/public/image4.png')}}') no-repeat center center;
background-size: 50%;
background-repeat: no-repeat;
background-position: center;
position: absolute;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
width: 100%;
height: 100%;
opacity: .15;
}

        .container {
        width: 80%;
        margin: auto;
        color: #1e398d;
        }
        .header {
        font-size: 24px; /* Reduced header font size */
        font-weight: 900;
        color: #1e398d;
        }
        .sub-header {
        font-size: 16px; /* Reduced sub-header font size */
        font-weight: 700;
        color: #1e398d;
        margin-bottom: 15px;
        }
        .table-container {
        border: 2px solid #1e398d;
        width: 100%;
        border-collapse: collapse;
        margin: auto;
        color: black;
        }
        td {
        padding: 10px; /* Reduced padding */
        vertical-align: top;
        font-size: 10px; /* Reduced font size */
        font-weight: bold; /* Added bold font weight */
        }
        .left, .right {
        width: 40%;
        text-align: left;
        font-size: 10px; /* Reduced font size */
        line-height: 1.5;
        font-weight: bold; /* Added bold font weight */
        }
        .center {
        text-align: center;
        width: 20%;
        }
        .logo {
        width: 60px; /* Reduced logo size */
        }
        .footer {
        font-size: 10px; /* Reduced font size */
        margin-top: 10px;
        font-weight: bold;
        color: #1e398d;
        }
        a {
        color: #1e398d;
        text-decoration: none;
        font-weight: bold;
        }
        a:hover {
        text-decoration: underline;
        }
        .bold {
        font-weight: bold;
        color: #1e398d;
        }
        table {
        width: 100%;
        border-collapse: collapse;
        table-layout: auto;
        word-wrap: break-word;
        }
        th, td {
        border: 1px solid black;
        padding: 4px;
        text-align: center;
        font-size: 9px; /* Further reduced font size */
        font-weight: bold; /* Added bold font weight */
        }
        .table-responsive {
        overflow-x: auto;
        }
        img {
        display: block;
        }
        .gallery {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        max-width: 800px;
        margin: auto;
        }
        .image-card {
        text-align: center;
        width: 200px;
        }
        .image-card img {
        width: 200px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        }
        .title {
        margin-top: 10px;
        font-size: 16px;
        font-weight: bold;
        }
        </style>
        
    </head>
    <body>
        <div class="container">
           <div class="watermark"></div>
            <div class="header" style="font-weight:900; font-size:30px ;">FREETOWN CITY COUNCIL</div>
            <div>
                <img src="{{storage_path('app/public/image4.png')}}" class="logo">
                
            </div>
            <span><span class="bold" style="text-align:center;font-size: 12px;">New City Council Complex
                17 Wallace-Johnson Street
                Freetown
            Sierra Leone</span>
            <br>
            Tel:<span class="bold" style="text-align:center;font-size: 12px;"> +232 76 345 504</span><br>
        </div>
        <hr>
        <div class="header">Demand Note</div>
        <div class="table-responsive">
            <h4  style="text-align:left; font-size: 12px; margin-bottom: 5px;">Customer Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User Name</th>
                        <th>Customer Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Address</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>FCC/BK/{{ $booking->id }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->customer_name }}</td>
                        <td>{{ $booking->customer_email }}</td>
                        <td>{{ $booking->mobile }}</td>
                        <td style="word-wrap: break-word; max-width: 150px;">{{ $booking->address }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Booking Details</h4>
            <table style="font-size: 20px;">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Period</th>
                        <th>Space</th>
                        <th>Space Category</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>FCC/BK/{{ $booking->id }}</td>
                        <td>{{ $booking->period }}</td>
                        <td>FCC/SPC/{{ $booking->space->id ?? 'N/A' }}</td>
                        <td>{{ $booking->space->category->name ?? 'N/A' }}</td>
                        <td>{{ $booking->start_date }}</td>
                        <td>{{ $booking->end_date }}</td>
                        <td>{{ $booking->status }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Space Basic Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>Data Collection Date</th>
                        <th>Name of Person Collecting Data</th>
                        <th>Advertisement Agent/Company</th>
                        <th>Contact Person</th>
                        <th>Telephone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->space->data_collection_date }}</td>
                        <td>{{ $booking->space->name_of_person_collection_data }}</td>
                        <td>{{ $booking->space->name_of_advertise_agent_company_or_person }}</td>
                        <td>{{ $booking->space->name_of_contact_person }}</td>
                        <td>{{ $booking->space->telephone }}</td>
                        <td>{{ $booking->space->email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Advertisement Identification</h4>
            <table>
                <thead>
                    <tr>
                        <th>Space Category rate</th>
                        <th>Location</th>
                        <th>Street/Road Number</th>
                        <th>Section of Road</th>
                        <th>Landmark</th>
                        <th>GPS Coordinates</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->space->rate }}</td>
                        <td>{{ $booking->space->location }}</td>
                        <td>{{ $booking->space->stree_rd_no }}</td>
                        <td>{{ $booking->space->section_of_rd }}</td>
                        <td>{{ $booking->space->landmark }}</td>
                        <td>{{ $booking->space->gps_cordinate }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Advertisement Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Type</th>
                        <th>Billboard Position</th>
                        <th>Length</th>
                        <th>Width</th>
                        <th>Area</th>
                        <th>No. of Sides</th>
                        <th>Clearance Height</th>
                        <th>Illumination</th>
                        {{-- <th>Certified Georgia Licensed</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->space->description_property_advertisement }}</td>
                        <td>{{ $booking->space->advertisement_cat_desc }}</td>
                        <td>{{ $booking->space->type_of_advertisement }}</td>
                        <td>{{ $booking->space->position_of_billboard }}</td>
                        <td>{{ $booking->space->lenght_advertise }}</td>
                        <td>{{ $booking->space->width_advertise }}</td>
                        <td>{{ $booking->space->area_advertise }}</td>
                        <td>{{ $booking->space->no_advertisement_sides }}</td>
                        {{-- <td>{{ $booking->space->other_advertisement_sides }}</td> --}}
                        <td>{{ $booking->space->clearance_height_advertise }}</td>
                        <td>{{ $booking->space->illuminate_nonilluminate }}</td>
                        {{-- <td>{{ optional($booking->space)->certified_georgia_licensed == 1 ? 'Yes' : 'No' }}</td> --}}
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Amount Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>Booking Status</th>
                        <th>Amount</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ isset($booking->payment) && $booking->payment != null ?
                            ( (int) $booking->payments_sum_payment_amount_1 === (int) $booking->payment->amount ? 'full' : $booking->payment->payment_type ) : 'not paid' }}
                        </td>
                        <td>
                            NLe
                            {{ isset($booking->payment) && $booking->payment != null
                            ? number_format($booking->payment->amount, 2, '.', ',')
                            : number_format(
                            $booking->space->rate * $booking->space->area_advertise * (intval($booking->space->other_advertisement_sides_no ?? 1)) ?? 0,
                            2,
                            '.',
                            ','
                            )
                            }}
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Landlord Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>Company/Corporate</th>
                        <th>Landowner Name</th>
                        <th>Street Address</th>
                        <th>Telephone</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $booking->space->landowner_company_corporate }}</td>
                        <td>{{ $booking->space->landowner_name }}</td>
                        <td>{{ $booking->space->landlord_street_address }}</td>
                        <td>{{ $booking->space->landlord_telephone }}</td>
                        <td>{{ $booking->space->landlord_email }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Bank Details</h4>
            <table>
                <thead>
                    <tr>
                        <th>LOCATION</th>
                        <th>BANK</th>
                        <th>ACCOUNT NAME</th>
                        <th>ACCOUNT NUMBER</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ANY BANK BRANCH OFFICE</td>
                        <td style="text-transform: uppercase;">ROKEL COMMERCIAL BANK</td>
                        <td>FREETOWN CITY COUNCIL GENERAL FUND ACCOUNT</td>
                        <td>0201118450001</td>
                    </tr>
                    <tr>
                        <td>ANY BANK BRANCH OFFICE</td>
                        <td style="text-transform: uppercase;">SIERRA LEONE COMMERCIAL BANK</td>
                        <td>FREETOWN CITY COUNCIL REVENUE ACCOUNT </td>
                        <td>116776</td>
                    </tr>
                    {{-- <tr>
                        <td>ANY BANK BRANCH OFFICE</td>
                        <td style="text-transform: uppercase;">Ecobank (SL) Ltd</td>
                        <td>FREE TOWN CITY COUNCIL ACCOUNT</td>
                        <td>6340029635 </td>
                    </tr> --}}
                    
                </tbody>
            </table>
        </div>
        <br>
        {{-- <br> --}}
        <div style="display: flex; flex-direction: column; align-items: flex-end; text-align: right;">
            <img src="{{ storage_path('app/public/Stamp.png') }}" class="logo" style="width: 70px; display: block;">
            <img src="{{ storage_path('app/public/Sign.png') }}" class="logo" style="width: 50px; margin-top: -20px;">
            <p style="text-align:right; font-size:12px">Authorize Signature</p>
        </div>
        <br>
        <br>
        <br>
        <div class="table-responsive">
    <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">Amount Details</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Payment ID</th>
                <th>Booking ID</th>
                <th>Amount paid</th>
                <th>Payment Type</th>
                <th>Payee Name</th>
                <th>Payment Date</th>
                <th>Payment Mode</th>
                <th>Transaction ID</th>
                {{-- <th>Payment Slip</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach($booking->payments as $payment)
                <tr>
                    <td>FCC/PMT/{{ $payment->id }}</td>
                    <td>FCC/BK/{{ $payment->booking_id }}</td>
                    <td>
                        NLe {{ number_format($payment->payment_amount_1, 2, '.', ',') }}
                    </td>
                    <td>{{ $payment->payment_type }}</td>
                    <td>{{ $payment->payee_name }}</td>
                    <td>{{ $payment->payment_date }}</td>
                    <td>{{ $payment->payment_mode }}</td>
                    <td>{{ $payment->transaction_id }}</td>
                   {{--  <td>
                        @if(file_exists(public_path($booking->payment->payment_slip)))
                            <a href="{{ asset($booking->payment->payment_slip) }}" target="_blank">View Slip</a>
                        @else
                            No Slip Available
                        @endif
                    </td> --}}
                </tr>
            @endforeach

            <!-- If no payments exist, show a default row -->
            @if($booking->payments->isEmpty())
                <tr>
                    <td colspan="9">No payments recorded.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

        <div class="table-responsive">
            <h4 style="text-align:left; font-size: 12px; margin-bottom: 5px;">FCC by Law Provision for Advertisement on the Demand Notice  </h4>
            <p style="text-align: justify; line-height: 1.8; margin-bottom: 15px;font-size: 11px;">
                Defaulters are kindly required to note the following provisions in the Freetown City Council
                Statutory Nuisance Byelaws, 2010 which state the following:
            </p>
            
            <p style="text-align: justify; line-height: 1.8; margin-bottom: 15px;font-size: 11px;">
                <strong>Section 3 (j)</strong> of the Municipality byelaws states that “any display of banners or erection of
                billboards or other advertising hoardings without first obtaining appropriate permits from Council”
                is an offence.
            </p>
            
            <p style="text-align: justify; line-height: 1.8; margin-bottom: 15px;font-size: 11px;">
                <strong>Section 4 (2)</strong> states that “any banner, billboard or advertising hoarding erected in
                contravention of paragraph (j) of sub clause (3), shall be pulled down or confiscated by Council
                and cost for such action and a fine of Le100,000 per billboard and Le25,000 per banner levied on
                the defaulter”.
            </p>
            
            <p style="text-align: justify; line-height: 1.8;font-size:11px">
                <strong>Section 4 (1)</strong> any person who contravenes any of the provisions of subclause (2), commits an
                offence and shall be liable, on summary conviction, to a fine not exceeding five hundred thousand
                leones or to imprisonment for a term not exceeding six months or to both such fines and
                imprisonment.
            </p>
        </div>
        

        <div class="gallery" style="display: flex; gap: 30px; justify-content: center; align-items: flex-start;">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div class="title" style="margin-bottom: 5px;">Front View</div>
                @if($booking->space->image_1)
                    <img src="{{ storage_path('app/public/' . $booking->space->image_1) }}" alt="Front view" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div style="width: 100px; height: 100px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">No image</div>
                @endif
            </div>
        
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div class="title" style="margin-bottom: 5px;">Back View</div>
                @if($booking->space->image_2)
                    <img src="{{ storage_path('app/public/' . $booking->space->image_2) }}" alt="Back view" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div style="width: 100px; height: 100px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">No image</div>
                @endif
            </div>
        
            <div style="display: flex; flex-direction: column; align-items: center;">
                <div class="title" style="margin-bottom: 5px;">Whole View</div>
                @if($booking->space->image_3)
                    <img src="{{ storage_path('app/public/' . $booking->space->image_3) }}" alt="Whole view" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <div style="width: 100px; height: 100px; background-color: #f0f0f0; display: flex; align-items: center; justify-content: center;">No image</div>
                @endif
            </div>
        </div>
        
        
    </body>
</html>