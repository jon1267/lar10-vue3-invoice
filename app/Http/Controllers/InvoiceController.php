<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Counter;

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

    public function createInvoice()
    {
        $counter = Counter::where('key', 'invoice')->first();

        $invoice = Invoice::orderBy('id', 'DESC')->first();
        if ($invoice) {
            $invoiceId  = $invoice->id + 1;
            $counterNew = $counter->value + $invoiceId;
        } else {
            $counterNew = $counter->value;
        }

        $formData = [
            'number' => $counter->prefix.$counterNew,
            'customer_id' => null,
            'customer' => null,
            'date' => date('Y-m-d'),
            'due_date' => null,
            'reference' => null,
            'discount' => 0,
            'terms_and_conditions' => 'Default terms and conditions',
            'items' => [
                [
                    'product_id' => null,
                    'product' => null,
                    'unit_price' => 0,
                    'quantity' => 1
                ]
            ]
        ];

        return response()->json($formData, 200);
    }
}
