<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function allInvoices()
    {
        $invoices = Invoice::with('customer')->orderBy('id', 'DESC')->get(); //$invoices = Invoice::all();

        return response()->json(['invoices' => $invoices],200);
    }

    public function searchInvoice(Request $request)
    {
        $search = $request->get('s');
        if (!is_null($search)) {
            $invoices = Invoice::with('customer')
                ->where('id','LIKE',"%$search%")
                //->orWhere('number','LIKE',"%$search%")
                ->get();
            return response()->json(['invoices' => $invoices],200);
        }
        return $this->allInvoices();
    }
}
