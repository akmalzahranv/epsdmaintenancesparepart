<?php

namespace App\Http\Controllers;

use App\Models\ProductWip;
use App\Models\Product;
use App\Models\StockOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function products(Request $req){
        $sort = $req->sort;
        $search = $req->q;
        $cat = $req->category;
        $line = $req->line;
        $machine = $req->machine;
        $dateSort = $req->date_sort;
    
        $categories = DB::table('categories')->get();
        $lines = DB::table('line')->get();
        $machines = DB::table('machine')->get();
    
        $products = DB::table('products')                    
                    ->leftJoin("shelf", "products.shelf_id", "=", "shelf.shelf_id")
                    ->leftJoin("categories", "products.category_id", "=", "categories.category_id")
                    ->leftJoin("line", "products.line_id", "=", "line.line_id")
                    ->leftJoin("machine", "products.machine_id", "=", "machine.machine_id")
                    ->select("products.*", "shelf.*", "categories.*", "line.*", "machine.*");                    
        
        if(!empty($cat)){
            $products = $products->where("categories.category_id", $cat);
        }
    
        if(!empty($line)){
            $products = $products->where("line.line_id", $line);
        }
    
        if(!empty($machine)){
            $products = $products->where("machine.machine_id", $machine);
        }
        
        if(!empty($search)){
            $products = $products->where(function($query) use ($search) {
                $query->where("products.product_name", "LIKE", "%".$search."%")
                      ->orWhere("products.product_code", "LIKE", "%".$search."%");
            });
        }
               
        if($dateSort == 'asc'){
            $products = $products->orderBy("products.request_date", "asc");
        } elseif($dateSort == 'desc'){
            $products = $products->orderBy("products.request_date", "desc");
        } elseif(empty($sort)){
            $products = $products->orderBy("products.product_id", "desc");
        } elseif($sort == "desc"){
            $products = $products->orderBy("products.product_name", "desc");
        } else {
            $products = $products->orderBy("products.product_name", "asc");
        }
    
        $products = $products->paginate(50);
    
        foreach($products as $p){
            $totalStockIn   = DB::table('stock')->where([["product_id", $p->product_id], ["type", 1]])->sum("product_amount");
            $totalStockOut  = DB::table('stock')->where([["product_id", $p->product_id], ["type", 0]])->sum("product_amount");
            $availableStock = $totalStockIn-$totalStockOut;
            $p->product_amount = $availableStock;
        }
    
        return View::make("products")->with(compact("products", "categories", "lines", "machines", "dateSort"));
    }

    public function products_wip(Request $req){
        $sort   = $req->sort;
        $search = $req->q;
        $cat    = $req->category;
        $dateSort = $req->date_sort;

        $products = DB::table('products_wip')
                    ->leftJoin("shelf", "products_wip.shelf_id", "=", "shelf.shelf_id")
                    ->leftJoin("categories", "products_wip.category_id", "=", "categories.category_id")
                    ->leftJoin("line", "products_wip.line_id", "=", "line.line_id")
                    ->leftJoin("machine", "products_wip.machine_id", "=", "machine.machine_id")
                    ->select("products_wip.*", "shelf.*", "categories.*", "line.*", "machine.*");
        
        if(!empty($search)){
            $products = $products->orWhere([["products.product_name", "LIKE", "%".$search."%"]])
                        ->orWhere([["products.product_code", "LIKE", "%".$search."%"]]);
        }
        
        if($dateSort == 'asc'){
            $products = $products->orderBy("products_wip.request_date", "asc");
        } elseif($dateSort == 'desc'){
            $products = $products->orderBy("products_wip.request_date", "desc");
        } elseif(empty($sort)){
            $products = $products->orderBy("products_wip.product_wip_id", "desc");
        } elseif($sort == "desc"){
            $products = $products->orderBy("products_wip.product_name", "desc");
        } else {
            $products = $products->orderBy("products_wip.product_name", "asc");
        }
        
        if(empty($sort)){
            $products = $products->orderBy("products_wip.product_wip_id", "desc")->paginate(50);
        } else if($sort == "desc"){
            $products = $products->orderBy("products_wip.product_code", "desc")->paginate(50);
        } else {
            $products = $products->orderBy("products_wip.product_code", "asc")->paginate(50);
        }                

        return View::make("products_wip")->with(compact("products", "dateSort"));
    }

    public function product_check(Request $req){

        // $product = DB::table('products')->where([["product_code", $req->pcode]])->select("product_id", "product_code","product_name")->first();
        $product = DB::table('products')     
                    ->where([["product_code", $req->pcode]])   
                    ->select("product_id", "product_code","product_name")
                    ->leftJoin("shelf", "products.shelf_id", "=", "shelf.shelf_id")
                    ->leftJoin("categories", "products.category_id", "=", "categories.category_id")
                    ->leftJoin("line", "products.line_id", "=", "line.line_id")
                    ->leftJoin("machine", "products.machine_id", "=", "machine.machine_id")
                    ->select("products.*", "shelf.*", "categories.*", "line.*", "machine.*")
                    ->first();                    
        
        $result = ["status" => 0, "data" => null];

        if(!empty($product)){
            $result = ["status" => 1, "data" => $product];
        }
        
        return response()->json($result);
    }

    public function product_save(Request $req){
        if ($req->line == 6){
            $req->validate([                        
                "product_name"    => 'required',
                "problem_details"    => 'required',
                "specification"    =>  'required',            
                "quantity"    => 'required|numeric',
                "shelf"    => 'required',
                "category"    => 'required',
                "line"    => 'required',                
                "request_date"    => 'required',
                "requester"    => 'required',                    
            ],
            [            
                'product_name.required'     => 'Product Name belum diisi!',
                'problem_details.required'     => 'Problem Details belum diisi!',
                'specification.required'     => 'Product Specification belum diisi!',
                'quantity.required'     => 'Product Quantity belum diisi!',
                'shelf.required'     => 'Product Shelf belum diisi!',
                'category.required'     => 'Product Category belum diisi!',
                'line.required'     => 'Product Line belum diisi!',                
                'request_date.required'     => 'Product Request Date belum diisi!',
                'requester.required'     => 'Product Requester belum diisi!',
            ]);
        } else {
            $req->validate([                        
                "product_name"    => 'required',
                "problem_details"    => 'required',
                "specification"    =>  'required',            
                "quantity"    => 'required|numeric',
                "shelf"    => 'required',
                "category"    => 'required',
                "line"    => 'required',
                "machine"    => 'required',
                "request_date"    => 'required',
                "requester"    => 'required',                    
            ],
            [            
                'product_name.required'     => 'Product Name belum diisi!',
                'problem_details.required'     => 'Problem Details belum diisi!',
                'specification.required'     => 'Product Specification belum diisi!',
                'quantity.required'     => 'Product Quantity belum diisi!',
                'shelf.required'     => 'Product Shelf belum diisi!',
                'category.required'     => 'Product Category belum diisi!',
                'line.required'     => 'Product Line belum diisi!',
                'machine.required'     => 'Product Machine belum diisi!',
                'request_date.required'     => 'Product Request Date belum diisi!',
                'requester.required'     => 'Product Requester belum diisi!',
            ]);
        }

        $productName = $req->product_name;
        $prefix = substr($productName, 0, 2);
    
        $productName = $req->product_name;
        $words = explode(' ', $productName);

        if (count($words) == 1) {
            $prefix = substr($productName, 0, 2);
        } else {
            $prefix = substr($words[0], 0, 1) . substr($words[1], 0, 1);
        }

        do {
            $randomNumber = mt_rand(10000000, 99999999);
            $productCode = strtoupper($prefix) . $randomNumber;
            
            $existingProduct = DB::table('products_wip')
                                ->where('product_code', $productCode)
                                ->first();
        } while ($existingProduct);
    
        $data = [
            "user_id"           => Auth::user()->id,
            "product_code"      => $productCode,
            "product_name"    => $req->product_name,
            "problem_details"    => $req->problem_details,
            "specification"    => $req->specification,            
            "maker"    => $req->maker,
            "item_no"    => $req->item_no,
            "quantity"    => $req->quantity,
            "shelf_id"    => $req->shelf,
            "category_id"    => $req->category,
            "line_id"    => $req->line,
            "machine_id"    => $req->machine,
            "request_date"    => $req->request_date,
            "requester"    => $req->requester,
            "order_date"    => $req->order_date,
            "supplier"    => $req->supplier,
            "estimate_time"    => $req->estimate_time,
            "arrival_time"    => $req->arrival_time,
            "installation_planning_schedule"    => $req->installation_planning_schedule,
            "installation_date"    => $req->installation_date,            
        ];

        if(empty($req->id)){
            $add = DB::table('products')->insertGetId($data);

            if($add){
                $req->session()->flash('success', "Product berhasil ditambahkan.");
            } else {
                $req->session()->flash('error', "Product gagal ditambahkan!");
            }
        } else {
            $update = DB::table('products')->where("product_id", $req->id)->update($data);

            if($update){
                $req->session()->flash('success', "Product berhasil diubah.");
            } else {
                $req->session()->flash('error', "Product gagal diubah!");
            }
        }
        
        return redirect()->back();
    }

    public function product_wip_save(Request $req){
        if ($req->line == 6){
            $req->validate([                        
                "product_name"    => 'required',
                "problem_details"    => 'required',
                "specification"    =>  'required',            
                "quantity"    => 'required|numeric',
                "shelf"    => 'required',
                "category"    => 'required',
                "line"    => 'required',                
                "request_date"    => 'required',
                "requester"    => 'required',                    
            ],
            [            
                'product_name.required'     => 'Product Name belum diisi!',
                'problem_details.required'     => 'Problem Details belum diisi!',
                'specification.required'     => 'Product Specification belum diisi!',
                'quantity.required'     => 'Product Quantity belum diisi!',
                'shelf.required'     => 'Product Shelf belum diisi!',
                'category.required'     => 'Product Category belum diisi!',
                'line.required'     => 'Product Line belum diisi!',                
                'request_date.required'     => 'Product Request Date belum diisi!',
                'requester.required'     => 'Product Requester belum diisi!',
            ]);
        } else {
            $req->validate([                        
                "product_name"    => 'required',
                "problem_details"    => 'required',
                "specification"    =>  'required',            
                "quantity"    => 'required|numeric',
                "shelf"    => 'required',
                "category"    => 'required',
                "line"    => 'required',
                "machine"    => 'required',
                "request_date"    => 'required',
                "requester"    => 'required',                    
            ],
            [            
                'product_name.required'     => 'Product Name belum diisi!',
                'problem_details.required'     => 'Problem Details belum diisi!',
                'specification.required'     => 'Product Specification belum diisi!',
                'quantity.required'     => 'Product Quantity belum diisi!',
                'shelf.required'     => 'Product Shelf belum diisi!',
                'category.required'     => 'Product Category belum diisi!',
                'line.required'     => 'Product Line belum diisi!',
                'machine.required'     => 'Product Machine belum diisi!',
                'request_date.required'     => 'Product Request Date belum diisi!',
                'requester.required'     => 'Product Requester belum diisi!',
            ]);
        }
    
        $productName = $req->product_name;
        $prefix = substr($productName, 0, 2);
    
        $productName = $req->product_name;
        $words = explode(' ', $productName);

        if (count($words) == 1) {
            $prefix = substr($productName, 0, 2);
        } else {
            $prefix = substr($words[0], 0, 1) . substr($words[1], 0, 1);
        }

        do {
            $randomNumber = mt_rand(10000000, 99999999);
            $productCode = strtoupper($prefix) . $randomNumber;
            
            $existingProduct = DB::table('products_wip')
                                ->where('product_code', $productCode)
                                ->first();
        } while ($existingProduct);
    
        $data = [
            "user_id"           => Auth::user()->id,
            "product_code"      => $productCode,
            "product_name"    => $req->product_name,
            "problem_details"    => $req->problem_details,
            "specification"    => $req->specification,            
            "maker"    => $req->maker,
            "item_no"    => $req->item_no,
            "quantity"    => $req->quantity,
            "shelf_id"    => $req->shelf,
            "category_id"    => $req->category,
            "line_id"    => $req->line,
            "machine_id"    => $req->machine,
            "request_date"    => $req->request_date,
            "requester"    => $req->requester,
            "order_date"    => $req->order_date,
            "supplier"    => $req->supplier,
            "estimate_time"    => $req->estimate_time,
            "arrival_time"    => $req->arrival_time,
            "installation_planning_schedule"    => $req->installation_planning_schedule,
            "installation_date"    => $req->installation_date,
            "status"    => "Requested",
        ];
    
        if(empty($req->id)){
            $add = DB::table('products_wip')->insertGetId($data);
    
            if($add){
                $req->session()->flash('success', "Product berhasil ditambahkan.");
            } else {
                $req->session()->flash('error', "Product gagal ditambahkan!");
            }
        } else {
            $update = DB::table('products_wip')->where("product_wip_id", $req->id)->update($data);
    
            if($update){
                $req->session()->flash('success', "Product berhasil diubah.");
            } else {
                $req->session()->flash('error', "Product gagal diubah!");
            }
        }
        
        return redirect()->back();
    }

    public function product_wip_status(Request $request) {                       
        $data = $request->input('datas');

        $productWipId = $data['pid'];
        $newStatus = $data['status'];                  

        if  ($data['line_id'] == null){
            $data['line_id'] = 6;
        }

        if ($newStatus == "Receive"){
            $datas = [
                "user_id"           => Auth::user()->id,
                "product_code"      => $data['pcode'],
                "product_name"    => $data['pname'],
                "problem_details"    => $data['pdetails'],
                "specification"    => $data['pspecification'],
                "maker"    => $data['pmaker'],
                "item_no"    => $data['pitem_no'],
                "quantity"    => $data['pquantity'],
                "shelf_id"    => $data['shelf_id'],
                "category_id"    => $data['category_id'],
                "line_id"    => $data['line_id'],
                "machine_id"    => $data['machine_id'],
                "request_date"    => $data['prequest_date'],
                "requester"    => $data['prequester'],
                "order_date"    => $data['porder_date'],
                "supplier"    => $data['psupplier'],
                "estimate_time"    => $data['pestimate_time'],
                "arrival_time"    => $data['parrival_time'],
                "installation_planning_schedule"    => $data['pinstallation_planning_schedule'],
                "installation_date"    => $data['pinstallation_date'],
            ];                                    
            $products = DB::table('products')->insertGetId($datas); 
            $productId = $products;       
            $amount     = $data['pquantity'];    
            
            $data_stock = [
                "user_id" => Auth::user()->id,
                "username" => $data['prequester'],
                "shelf_id" => $data['shelf_id'],
                "product_id" => $productId,
                "product_amount" => $data['pquantity'],
                "type" => 1,
                "created_at" => now(),                
            ];             

            $endingTotalStockIn   = DB::table('stock')->where([["product_id", $productId], ["type", 1]])->sum("product_amount");
            $endingTotalStockOut  = DB::table('stock')->where([["product_id", $productId], ["type", 0]])->sum("product_amount");
            $endingAmount = $endingTotalStockIn-$endingTotalStockOut;
            $data_stock["ending_amount"] = $endingAmount+$amount;
            DB::table('stock')->insertGetId($data_stock);        
            
            $product = ProductWip::findOrFail($productWipId);
            $product->delete();
            
            return response()->json(['success' => true]);
        }        
          
        $product = ProductWip::findOrFail($productWipId);
        $product->status = $newStatus;
        $product->save();
    
        return response()->json(['success' => true]);
    }

    public function product_status_ordered(Request $request) {
        $request->validate([                                    
            "ordered_order_date"    => 'required',
            "ordered_supplier"    =>  'required',            
            "ordered_estimate_time"    => 'required',                    
        ],
        [            
            'ordered_order_date.required'     => 'Order Date belum diisi!',
            'ordered_supplier.required'     => 'Supplier belum diisi!',
            'ordered_estimate_time.required'     => 'Estimate Time belum diisi!',            
        ]);
                      

        $dataProduct = DB::table('products_wip')
                ->where("product_wip_id", $request->ordered_save_id)
                ->update([
                    'order_date' => $request->ordered_order_date,
                    'supplier' => $request->ordered_supplier,
                    'estimate_time' => $request->ordered_estimate_time,
                    'status' => "Ordered",
                ]);
        
        if ($dataProduct) {
            $request->session()->flash('success', "Status berhasil diubah.");
        } else {
            $request->session()->flash('error', "Status gagal diubah.");
        }            
        return redirect()->back();        
    }

    public function product_status_received(Request $request){
        $request->validate([                                    
            "received_arrival_time"    => 'required',                 
        ],
        [            
            'received_arrival_time.required'     => 'Arrival Time belum diisi!',                      
        ]);
                      

        $dataProductWip = DB::table('products_wip')
                ->where("product_wip_id", $request->received_save_id)
                ->first();
        
        if ($dataProductWip) {
            $datas = [
                "user_id" => Auth::user()->id,
                "product_code" => $dataProductWip->product_code,
                "product_name" => $dataProductWip->product_name,
                "problem_details" => $dataProductWip->problem_details,
                "specification" => $dataProductWip->specification,
                "maker" => $dataProductWip->maker,
                "item_no" => $dataProductWip->item_no,
                "quantity" => $dataProductWip->quantity,
                "shelf_id" => $dataProductWip->shelf_id,
                "category_id" => $dataProductWip->category_id,
                "line_id" => $dataProductWip->line_id,
                "machine_id" => $dataProductWip->machine_id,
                "request_date" => $dataProductWip->request_date,
                "requester" => $dataProductWip->requester,
                "order_date" => $dataProductWip->order_date,
                "supplier" => $dataProductWip->supplier,
                "estimate_time" => $dataProductWip->estimate_time,
                "arrival_time" => $request->received_arrival_time,
                "installation_planning_schedule" => $dataProductWip->installation_planning_schedule,
                "installation_date" => $dataProductWip->installation_date,
            ];                                    
            $products = DB::table('products')->insertGetId($datas); 
            $productId = $products;       
            $amount     = $dataProductWip->quantity;    
            
            $data_stock = [
                "user_id" => Auth::user()->id,
                "username" => $dataProductWip->requester,
                "shelf_id" => $dataProductWip->shelf_id,
                "product_id" => $productId,
                "product_amount" => $amount,
                "type" => 1,                
                "created_at" => now(),                
            ];             

            $endingTotalStockIn   = DB::table('stock')->where([["product_id", $productId], ["type", 1]])->sum("product_amount");
            $endingTotalStockOut  = DB::table('stock')->where([["product_id", $productId], ["type", 0]])->sum("product_amount");
            $endingAmount = $endingTotalStockIn-$endingTotalStockOut;
            $data_stock["ending_amount"] = $endingAmount+$amount;
            DB::table('stock')->insertGetId($data_stock);        
            
            $product = ProductWip::findOrFail($request->received_save_id);
            $product->delete();
                        
            $request->session()->flash('success', "Status berhasil diubah.");            
        } else {
            $request->session()->flash('error', "Status gagal diubah.");
        }            
        return redirect()->back();    
    }

    public function product_stock_order_status(Request $request) {        
        $data = $request->input('datas');
    
        $stock_order_Id = $data['soid'];
        $product_id = $data['pid'];        
        $newStatus = $data['status'];
        $amount = $data['pamount'];
        $shelf_id = $data['shelf_id'];        
        $username = $data['pusername'];        
        
        if ($newStatus == "Receive"){
            $product = Product::findOrFail($product_id);

            if ($amount < 0) {
                return response()->json(["status" => 0, "message" => "Jumlah penambahan stok tidak boleh negatif!"]);
            }
                         
            $data = [
                "user_id" => Auth::user()->id,
                "username" => $username,
                "product_id" => $product_id,
                "product_amount" => $amount,
                "shelf_id" => $shelf_id,    
                "type" => 1,
                "ending_amount" => $product->quantity + $amount,
                "created_at" => now(),                        
            ];

            $dataProduct = DB::table('stock')->insertGetId($data);

            $product->quantity = $product->quantity + $amount;
            $product->shelf_id =  $shelf_id;
            $product->save();
            
            DB::table('stock_order')->where("stock_order_id", $stock_order_Id)->delete();

            if ($dataProduct) {
                return response()->json(["status" => 1, "message" => "Stok berhasil diupdate."]);
            }            
        }

        $stoc_order = StockOrder::findOrFail($stock_order_Id);
        $stoc_order->status = $newStatus;
        $stoc_order->save();

        if ($stoc_order){
            return response()->json(['success' => true]);
        }
                   
    }

    public function product_delete(Request $req){
        $del = DB::table('products')->where("product_id", $req->id)->delete();

        if($del){
            $stock_id = DB::table('stock')->where("product_id", $req->id)->first();
            if(!empty($stock_id)){
                $stock_id = $stock_id->stock_id;
                DB::table('stock')->where("product_id", $req->id)->delete();
                DB::table('history')->where("stock_id", $stock_id)->delete();
            }
            $req->session()->flash('success', "Product berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Product gagal dihapus!");
        }

        return redirect()->back();
    }  

    public function product_wip_delete(Request $req){
        $del = DB::table('products_wip')->where([["product_wip_id", $req->id]])->delete();

        if($del){
            $req->session()->flash('success', "Product berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Product gagal dihapus!");
        }

        return redirect()->back();
    }

    public function product_stock_order_delete(Request $req){
        $del = DB::table('stock_order')->where([["stock_order_id", $req->id]])->delete();

        if($del){
            $req->session()->flash('success', "Stock order berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Stock order gagal dihapus!");
        }

        return redirect()->back();
    }

    public function product_wip_complete(Request $req){
        $wip_id     = $req->wip_id;

        $wip        = DB::table('products_wip')->select("*")->where("product_wip_id", $wip_id)->first();
        $shelf      = DB::table('shelf')->select("shelf_id")->first()->shelf_id;
        $wipComplete = null;

        if(count(array($wip)) > 0){
            $data = new Request([
                "product_id"    => $wip->product_id,
                "amount"        => $wip->product_amount,
                "shelf"         => $shelf,
                "type"          => 1,
            ]);

            $wipComplete = $this->product_stock($data);
        }

        if($wipComplete){
            DB::table('products_wip')->where("product_wip_id", $wip_id)->delete();
            $req->session()->flash('success', "Product telah dipindahkan ke Products List.");
        } else {
            $req->session()->flash('error', "Terjadi kesalahan! Mohon coba kembali!");
        }

        return redirect()->back();
    }

    public function product_stock_order(Request $req){
        $search = $req->search;
        $order = DB::table('stock_order')
                    ->leftJoin("products", "stock_order.product_id", "=", "products.product_id")
                    ->leftJoin("shelf", "stock_order.shelf_id", "=", "shelf.shelf_id")
                    ->leftJoin("users", "stock_order.user_id", "=", "users.id")
                    ->select("stock_order.*", "products.product_code", "products.product_name", "products.requester", "shelf.shelf_name", "users.name")
                    ->orderBy("stock_order.stock_order_id", "desc");
                    
    
        if(!empty($search)){
            $order = $order->where("products.product_code", "LIKE", "%".$search."%")
                        ->orWhere("products.product_name", "LIKE", "%".$search."%")
                        ->orWhere("shelf.shelf_name", "LIKE", "%".$search."%");
        }
    
        $order = $order->paginate(50);
        return View::make("stock_order")->with(compact("order"));
    }
    

    public function product_stock(Request $req)
    {
        $product_id = $req->product_id;
        $amount = $req->amount;
        $type = $req->type;
        $shelf_id = $req->shelf;
        $username = $req->username;
        $installation_date = $req->installation_date;

        if (empty($amount)) {
            return response()->json(["status" => 0, "message" => "Amount belum diisi!"]);
        }

        if (empty($username)) {
            return response()->json(["status" => 0, "message" => "User Name belum diisi!"]);
        }

        $shelfData = DB::table('products')
            ->where('product_id', $product_id)
            ->value('shelf_id');

        $quantityData = DB::table('products')
            ->where('product_id', $product_id)
            ->value('quantity');

        $totalStockIn = DB::table('stock')->where([
            ["product_id", $product_id],
            ["shelf_id", $shelfData],
            ["type", 1]
        ])->sum("product_amount");

        $totalStockOut = DB::table('stock')->where([
            ["product_id", $product_id],
            ["shelf_id", $shelfData],
            ["type", 0]
        ])->sum("product_amount");

        $availableStock = $totalStockIn - $totalStockOut;

        if ($type == 0) {
            
            if (empty($installation_date)) {
                return response()->json(["status" => 0, "message" => "Installation Date belum diisi!"]);
            }

            if ($amount > $quantityData) {
                return response()->json(["status" => 0, "message" => "Jumlah stock out melebihi jumlah stock yang tersedia!"]);
            }

            if ($quantityData - $amount == 0){
                $dataProduct = DB::table('products')
                ->where('product_id', $product_id)
                ->update([
                    'shelf_id' => null,
                    'quantity' => $quantityData - $amount,
                    'installation_date' => $installation_date
                ]);
            } else {
                $dataProduct = DB::table('products')
                ->where('product_id', $product_id)
                ->update([
                    'quantity' => $quantityData - $amount,
                    'installation_date' => $installation_date
                ]);
            }

            if ($dataProduct) {
                $data = [
                    "user_id" => Auth::user()->id,
                    "username" => "$username",
                    "product_id" => $product_id,
                    "product_amount" => $amount,
                    "shelf_id" => $shelfData,
                    "type" => $type,
                    "installation_date" => $installation_date,
                    "created_at" => now(),
                    "ending_amount" => $quantityData - $amount
                ];

                $updateStock = DB::table('stock')->insertGetId($data);

                if ($updateStock) {
                    return response()->json(["status" => 1, "message" => "Stok berhasil diupdate."]);
                }
            }
        } elseif ($type == 1) {
            if ($amount < 0) {
                return response()->json(["status" => 0, "message" => "Jumlah penambahan stok tidak boleh negatif!"]);
            }

            if ($shelf_id != 0) {               
                $data = [
                    "user_id" => Auth::user()->id,
                    "username" => "$username",
                    "product_id" => $product_id,
                    "product_amount" => $amount,
                    "shelf_id" => $shelf_id,                    
                    "created_at" => now(),        
                    "status" => "Requested",
                ];

                $dataProduct = DB::table('stock_order')->insertGetId($data);
                if ($dataProduct) {
                    return response()->json(["status" => 1, "message" => "Stok berhasil diupdate."]);
                }
            } else {
                $stock_order_check = DB::table('stock_order')->where("product_id", $product_id)->first(); 
                $productData = DB::table('products')->where("product_id", $product_id)->first(); 
                if ($stock_order_check) {
                    return response()->json(["status" => 0, "message" => "Order stock produk dengan ID ". $productData->product_code ." sudah terdaftar!"]);
                }                            
                $data = [
                    "user_id" => Auth::user()->id,
                    "username" => "$username",
                    "product_id" => $product_id,
                    "product_amount" => $amount,
                    "shelf_id" => $shelfData,      
                    "created_at" => now(),        
                    "status" => "Requested",
                ];

                $dataProduct = DB::table('stock_order')->insertGetId($data);
                if ($dataProduct) {
                    return response()->json(["status" => 1, "message" => "Stok berhasil diupdate."]);
                }
            }
        }

        return response()->json(["status" => 0, "message" => "Stok gagal diupdate! Mohon coba kembali!"]);
    }

    public function product_stock_history(Request $req){
        $search = $req->search;
        $history = DB::table('stock')
                    ->leftJoin("products", "stock.product_id", "=", "products.product_id")
                    ->leftJoin("shelf", "stock.shelf_id", "=", "shelf.shelf_id")
                    ->leftJoin("users", "stock.user_id", "=", "users.id")
                    ->select("stock.*", "products.product_code", "products.product_name", "shelf.shelf_name", "users.name")
                    ->orderBy("stock.stock_id", "desc");

        if(!empty($search)){
            $history = $history->where("products.product_code", "LIKE", "%".$search."%")
                        ->orWhere("products.product_name", "LIKE", "%".$search."%")
                        ->orWhere("shelf.shelf_name", "LIKE", "%".$search."%");
        }

        $history = $history->paginate(50);
        return View::make("stock_history")->with(compact("history"));
    }

    public function categories(Request $req){
        $search = $req->q;

        $categories = DB::table('categories')->select("*");

        if(!empty($search)){
            $categories = $categories->where("category_name", "LIKE", "%".$search."%");
        }

        if($req->format == "json"){
            $categories = $categories->get();

            return response()->json($categories);
        } else {
            $categories = $categories->paginate(50);

            return View::make("categories")->with(compact("categories"));
        }
    }

    public function categories_save(Request $req){
        $category_id = $req->category_id;

        $req->validate([
            'category_name'      => ['required']
            
        ],
        [
            'category_name.required'     => 'Nama Kategori belum diisi!',
        ]);

        $data = [
            "category_name"      => $req->category_name
        ];

        if(empty($category_id)){
            $add = DB::table('categories')->insertGetId($data);

            if($add){
                $req->session()->flash('success', "Kategori baru berhasil ditambahkan.");
            } else {
                $req->session()->flash('error', "Kategori baru gagal ditambahkan!");
            }
        } else {
            $edit = DB::table('categories')->where("category_id", $category_id)->update($data);

            if($edit){
                $req->session()->flash('success', "Kategori berhasil diubah.");
            } else {
                $req->session()->flash('error', "Kategori gagal diubah!");
            }
        }
        
        return redirect()->back();
    }

    public function categories_delete(Request $req){
        $del = DB::table('categories')->where("category_id", $req->delete_id)->delete();

        if($del){
            DB::table('products')->where("category_id", $req->delete_id)->update(["category_id" => null]);
            $req->session()->flash('success', "Kategori berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Kategori gagal dihapus!");
        }

        return redirect()->back();
    }

    public function line(Request $req){
        $search = $req->q;

        $line = DB::table('line')->select("*");

        if(!empty($search)){
            $line = $line->where("line_name", "LIKE", "%".$search."%");
        }

        if($req->format == "json"){
            $line = $line->get();

            return response()->json($line);
        } else {
            $line = $line->paginate(50);

            return View::make("line")->with(compact("line"));
        }
    }

    public function line_save(Request $req){
        $line_id = $req->line_id;

        $req->validate([
            'line_name'      => ['required']
            
        ],
        [
            'line_name.required'     => 'Nama Kategori belum diisi!',
        ]);

        $data = [
            "line_name"      => $req->line_name
        ];

        if(empty($line_id)){
            $add = DB::table('line')->insertGetId($data);

            if($add){
                $req->session()->flash('success', "Line baru berhasil ditambahkan.");
            } else {
                $req->session()->flash('error', "Line baru gagal ditambahkan!");
            }
        } else {
            $edit = DB::table('line')->where("line_id", $line_id)->update($data);

            if($edit){
                $req->session()->flash('success', "Line berhasil diubah.");
            } else {
                $req->session()->flash('error', "Line gagal diubah!");
            }
        }
        
        return redirect()->back();
    }

    public function line_delete(Request $req){
        $del = DB::table('line')->where("line_id", $req->delete_id)->delete();

        if($del){            
            $req->session()->flash('success', "Line berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Line gagal dihapus!");
        }

        return redirect()->back();
    }

    public function machine(Request $req){
        $search = $req->q;
        $lines = $req->line;

        $machine = DB::table('machine')->select("*");        

        $line = DB::table('line')->get();

        $machine = DB::table('machine')
                    ->leftJoin("line", "machine.line_id", "=", "line.line_id")
                    ->select("machine.*", "line.*");

        if(!empty($search)){
            $machine = $machine->where("machine_name", "LIKE", "%".$search."%");
        }

        if(!empty($lines)){
            $products = $machine->orWhere([["line.line_id", $lines]]);
        }

        if($req->format == "json"){
            $machine = $machine->get();

            return response()->json($machine);
        } else {
            $machine = $machine->paginate(50);

            return View::make("machine")->with(compact("machine","line"));
        }
    }

    public function machine_save(Request $req){
        $machine_id = $req->machine_id;

        $req->validate([
            'line'           => ['required'],
            'machine_name'      => ['required']
            
        ],
        [
            'line.required'          => 'Line belum diisi!',
            'machine_name.required'     => 'Nama Kategori belum diisi!',
        ]);

        $data = [
            "line_id"           => $req->line,
            "machine_name"      => $req->machine_name
        ];

        if(empty($machine_id)){
            $add = DB::table('machine')->insertGetId($data);

            if($add){
                $req->session()->flash('success', "Machine baru berhasil ditambahkan.");
            } else {
                $req->session()->flash('error', "Machine baru gagal ditambahkan!");
            }
        } else {
            $edit = DB::table('machine')->where("machine_id", $machine_id)->update($data);

            if($edit){
                $req->session()->flash('success', "Machine berhasil diubah.");
            } else {
                $req->session()->flash('error', "Machine gagal diubah!");
            }
        }
        
        return redirect()->back();
    }

    public function machine_delete(Request $req){
        $del = DB::table('machine')->where("machine_id", $req->delete_id)->delete();

        if($del){            
            $req->session()->flash('success', "Machine berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Machine gagal dihapus!");
        }

        return redirect()->back();
    }

    public function shelf(Request $req) {
        $product_id = $req->product_id;
        $exceptProductId = $req->except_product_id;
        $shelf = DB::table('shelf');
        if ($req->format == "json") {
            if (!empty($product_id)) {
                $shelf = $shelf->join("stock", "shelf.shelf_id", "stock.shelf_id")
                    ->where("stock.product_id", $product_id)->groupBy("shelf_id");
                $result = [];
                $shelf = $shelf->select("shelf.*", "stock.product_amount")->get();
                foreach ($shelf as $s) {
                    $totalStockIn = DB::table('stock')->where([["product_id", $product_id], ["shelf_id", $s->shelf_id], ["type", 1]])->sum("product_amount");
                    $totalStockOut = DB::table('stock')->where([["product_id", $product_id], ["shelf_id", $s->shelf_id], ["type", 0]])->sum("product_amount");
                    $availableStock = $totalStockIn - $totalStockOut;
                    if ($availableStock > 0) {
                        $s->product_amount = $availableStock;
                        $s->used_in_products = DB::table('products')->where('shelf_id', $s->shelf_id)->exists();
                        $s->used_in_products_wip = DB::table('products_wip')->where('shelf_id', $s->shelf_id)->exists();
                        $result[] = $s;
                    }
                }
            } else {
                $result = $shelf->select("shelf.*")->get()->map(function ($shelf) use ($exceptProductId) {
                    $shelf->used_in_products = DB::table('products')
                        ->where('shelf_id', $shelf->shelf_id)
                        ->when($exceptProductId, function ($query) use ($exceptProductId) {
                            return $query->where('product_id', '!=', $exceptProductId);
                        })
                        ->exists();
    
                    $shelf->used_in_products_wip = DB::table('products_wip')
                        ->where('shelf_id', $shelf->shelf_id)
                        ->when($exceptProductId, function ($query) use ($exceptProductId) {
                            return $query->where('product_wip_id', '!=', $exceptProductId);
                        })
                        ->exists();
    
                    return $shelf;
                });
            }
            return response()->json($result); 
        } else {
            $shelf = $shelf->paginate(50);
            if(Auth::user()->role == 0){
                return View::make("shelf")->with(compact("shelf"));
            } else {
                abort(403);
            }
        }
    }
    

    public function shelf_save(Request $req){
        $shelf_id = $req->shelf_id;

        $req->validate([
            'shelf_name'      => ['required']
            
        ],
        [
            'shelf_name.required'     => 'Shelf Name belum diisi!',
        ]);

        $data = [
            "shelf_name"      => $req->shelf_name
        ];

        if(empty($shelf_id)){
            $add = DB::table('shelf')->insertGetId($data);

            if($add){
                $req->session()->flash('success', "Shelf baru berhasil ditambahkan.");
            } else {
                $req->session()->flash('error', "Shelf baru gagal ditambahkan!");
            }
        } else {
            $edit = DB::table('shelf')->where("shelf_id", $shelf_id)->update($data);

            if($edit){
                $req->session()->flash('success', "Shelf berhasil diubah.");
            } else {
                $req->session()->flash('error', "Shelf gagal diubah!");
            }
        }
        
        return redirect()->back();
    }

    public function shelf_delete(Request $req){
        $del = DB::table('shelf')->where("shelf_id", $req->delete_id)->delete();

        if($del){
            DB::table('stock')->where("shelf_id", $req->delete_id)->delete();
            $req->session()->flash('success', "Shelf berhasil dihapus.");
        } else {
            $req->session()->flash('error', "Shelf gagal dihapus!");
        }

        return redirect()->back();
    }

    public function generateBarcode(Request $req){
        $code       = $req->code;
        $print      = $req->print;
            
        $barcodeGenerator = new \Milon\Barcode\DNS1D();
            
        $barcodeB64 = $barcodeGenerator->getBarcodePNG("".$code."", 'C128', 2, 81, array(0,0,0), true);
        
        if (!empty($print) && $print == true) {
            return View::make("barcode_print")->with("barcode", $barcodeB64);
        } else {            
            $barcode = base64_decode($barcodeB64);
            
            if ($barcode === false) {
                return response('Failed to decode barcode', 500);
            }
                
            $image = imagecreatefromstring($barcode);
            if ($image === false) {
                return response('Failed to create image from string', 500);
            }
                
            ob_start();
            imagepng($image);
            $barcodePng = ob_get_contents();
            ob_end_clean();
                
            imagedestroy($image);
                
            if ($barcodePng === false) {
                return response('Failed to capture image output', 500);
            }
    
            return response($barcodePng)->header('Content-Type', 'image/png');
        }
    }
    
    
    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        $products = DB::table('products')
            ->where('product_code', 'LIKE', "%$search%")
            ->orWhere('product_name', 'LIKE', "%$search%")
            ->select('product_id', 'product_code', 'product_name')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}