<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Counter;
use App\Models\Invoice;
use App\Models\InvoiceItem;

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

    public function saveInvoice(Request $request)
    {
        $invoiceItems = json_decode($request->input('invoice_item'));

        $invoiceData['sub_total'] = $request->input('subtotal');
        $invoiceData['total'] = $request->input('total');
        $invoiceData['customer_id'] = $request->input('customer_id');
        $invoiceData['number'] = $request->input('number');
        $invoiceData['date'] = $request->input('date');
        $invoiceData['due_date'] = $request->input('due_date');
        $invoiceData['discount'] = $request->input('discount');
        $invoiceData['reference'] = $request->input('reference');
        $invoiceData['terms_and_conditions'] = $request->input('terms_and_conditions');

        $invoice = Invoice::create($invoiceData);

        foreach ($invoiceItems as $item) {
            $itemData['invoice_id'] = $invoice->id;
            $itemData['product_id'] = $item->id;
            $itemData['quantity'] = $item->quantity;
            $itemData['unit_price'] = $item->unit_price;

            InvoiceItem::create($itemData);
        }

        return response()->json(['message' => 'success'], 200);
    }

    public function showInvoice($id)
    {
        $invoice = Invoice::with('customer', 'invoice_items.product')->where('id', $id)->first();

        return response()->json(['invoice' => $invoice],200);
    }

    public function editInvoice($id)
    {
        $invoice = Invoice::with('customer', 'invoice_items.product')->where('id', $id)->first();

        return response()->json(['invoice' => $invoice],200);
    }

    public function deleteInvoiceItem($id)
    {
        $invoiceItem = InvoiceItem::findOrFail($id);

        $invoiceItem->delete();

        return response()->noContent();
    }
}
