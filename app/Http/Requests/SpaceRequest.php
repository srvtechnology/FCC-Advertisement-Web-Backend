<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpaceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'space_cat_id' => 'required|exists:space_categories,id',
            'data_collection_date' => 'required|date',
            'name_of_person_collection_data' => 'nullable|string|max:255',
            'name_of_advertise_agent_company_or_person' => 'nullable|string|max:255',
            'name_of_contact_person' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:255',
            'location' => 'nullable|string|max:255',
            'stree_rd_no' => 'nullable|string|max:50',
            'section_of_rd' => 'nullable|string|max:50',
            'landmark' => 'nullable|string|max:255',
            'gps_cordinate' => 'nullable|string|max:100',
            'description_property_advertisement' => 'nullable|string',
            'advertisement_cat_desc' => 'nullable|string|max:255',
            'type_of_advertisement' => 'nullable|string|max:255',
            'position_of_billboard' => 'nullable|string|max:255',
            'lenght_advertise' => 'nullable|numeric',
            'width_advertise' => 'nullable|numeric',
            'area_advertise' => 'nullable|numeric',
            'no_advertisement_sides' => 'nullable|string',
            'other_advertisement_sides' => 'nullable|string',
            'clearance_height_advertise' => 'nullable|numeric',
            'other_advertisement_sides_no'=> 'nullable',
            'illuminate_nonilluminate' => 'nullable|string|max:255',
            'certified_georgia_licensed' => 'nullable|string|max:255',
            'landowner_company_corporate' => 'nullable|string|max:255',
            'landowner_name' => 'nullable|string|max:255',
            'landlord_street_address' => 'nullable|string|max:255',
            'landlord_telephone' => 'nullable|string|max:15',
            'landlord_email' => 'nullable|email|max:255',

            'business_address'=> 'nullable',
            'business_contact'=> 'nullable',
            'business_name'=> 'nullable',
            'agent_rate_name'=> 'required',
        ];
    }
}
