<?php

namespace App\Filters;

use App\Statuses\ComplaintStatus;
use App\Models\Complaint;
use Illuminate\Support\Facades\DB;

class Filters
{
    public static function search_by_title($search)
    {
        return ['title', 'LIKE', '%' . $search . '%'];
    }

    public static function search_by_name_email_last_name_or_dni($search)
    {
        return [DB::raw("name LIKE '%$search%' or email LIKE '%$search%' or last_name LIKE '%$search%' or dni_number LIKE '%$search%' ")];
    }

    public static function only_dont_taken()
    {
        return ['status', '=', ComplaintStatus::STARTED];
    }
}
