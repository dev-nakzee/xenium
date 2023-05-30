<?php

namespace App\Http\Controllers\Pos\CRM;

use App\Http\Controllers\Controller;
use App\Models\CRM\Customers;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Customers::where('isDealer','0')->get();
        return view('pos.crm.customers', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data['full_name']=$request->input('fullname');
        $data['mobile']=$request->input('mobile');
        $data['email']=$request->input('email');
        $data['address']=$request->input('address');
        $data['pincode']=$request->input('pincode');
        $data['city']=$request->input('city');
        $data['location']=$request->input('location');
        $data['source']=$request->input('source');
        $data['isDealer']=$request->input('isDealer');
        // $data['email_verified']=$request->input('email_verified');
        // $data['mobile_verified']=$request->input('mobile_verified');
        // $data['lastlogin']=Carbon::now()->toDateTimeString();
        // $data['lastlogin_ip']=$request->getClientIp();
        //dd($request->file('photo'));
        if($request->file('photo'))
        {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
        
            $imageName = 'customer'.time().'.'.$request->photo->extension();  
            if($request->photo->move(public_path('customerphotos'), $imageName))
            $data['photo']=$imageName;
        }
        if($request->input('password')&&$request->input('password_confirmation')&&$request->input('email'))
        {
            $data['password']=$request->input('password');
        }
        //dd($request->input());
        if(Customers::insert($data))
        return redirect(route('pos.crm.customers'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
        if ($request->ajax()) {

            $data = Customers::where('isDealer',0)->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('personal_info', function($row){
                    return $row->full_name;
                })
                ->addColumn('contact_info', function($row){
                    return $row->mobile.'<br>'.$row->email.'</b>';
                })
                ->addColumn('address', function($row){
                    return $row->address;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<button onclick="showData('.$row->id.')" data-toggle="modal" data-target="#addEditModel" class="edit btn btn-success btn-sm"><i class="fa-light fa-edit"></i></button> <button onclick="delData('.$row->id.')" class="delete btn btn-danger btn-sm"><i class="fa-light fa-trash"></i></button>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->escapeColumns([])
                ->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        //
        $id = $request->all();
        $data = Customers::where('id', $id)->get();    

        return response()->json($data[0]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customers $customers)
    {
        //

        $data['full_name']=$request->input('fullname');
        $data['mobile']=$request->input('mobile');
        $data['email']=$request->input('email');
        $data['address']=$request->input('address');
        $data['pincode']=$request->input('pincode');
        $data['city']=$request->input('city');
        $data['location']=$request->input('location');
        $data['source']=$request->input('source');
        $data['isDealer']=$request->input('isDealer');
        // $data['lastlogin']=Carbon::now()->toDateTimeString();
        // $data['lastlogin_ip']=$request->getClientIp();
        // $data['email_verified']=$request->input('email_verified');
        // $data['mobile_verified']=$request->input('mobile_verified');


        if($request->file('photo'))
        {
            $request->validate([
                'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            ]);
            $imageName = 'emp'.time().'.'.$request->photo->extension();  
            if($request->photo->move(public_path('customerphotos'), $imageName))
            $data['photo']=$imageName;
        }

        if(Customers::where('id', $request->input('id'))->update($data))
            return redirect(route('pos.crm.customers'));

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $data = Customers::where('id', $request->id)->delete();
        return response()->json($data);
    }
}
