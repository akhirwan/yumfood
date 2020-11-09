<?php

namespace App\Http\Controllers;

use App\Order;
use App\Dish;
use App\Http\Resources\OrderResource;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return OrderResource::collection(Order::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'user_id' => 'required|numeric',
            'dish_id' => 'required|numeric',
            'amount' => 'nullable',
            'note' => 'nullable'
        ]);

        // check if user is exists
        
        try {
            $User = User::find($request->user_id);
            if(! $User) {
                return response()->json(array(
                    "message" => "User tidak ditemukan"
                ));
            }

            $Dish = Dish::find($request->dish_id);
            if(! $Dish) {
                return response()->json(array(
                    "message" => "Dish tidak ditemukan"
                ));
            }

            $Order = new Order();
            $Order->fill($request->all());
            $Order->save();

            return response()->json(array(
                "message" => 'Berhasil membuat order',
                // 'data' => new OrderResource($Order),
            ));
        }catch(NotFoundHttpException $ex) {
            return response()->json(array(
                'message' => 'Gagal membuat order',
                'error' => $ex
            ));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {
            $Order = Order::find($id);
            if(! $Order) {
                return response()->json(array(
                    'message' => 'Data tidak ditemukan'
                ));
            }

            return new OrderResource($Order);

        } catch(QueryException $ex) {
            return response()->json(array(
                'message' => 'Terdapat error',
                'error' => $ex
            ));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'user_id' => 'numeric',
            'dish_id' => 'numeric',
            'amount' => 'nullable',
            'note' => 'nullable'
        ]);

        $Order = Order::find($id);
        if(! $Order) {
            return response()->json(array(
                'message' => 'Data tidak ada'
            ));
        }

        try {
            $Order->fill($request->all());
            $Order->save();

            $Message = array(
                'message' => "Berhasil mengubah",
                'data' => new OrderResource($Order)
            );
    
            return response()->json($Message);

        }catch(QueryException $ex) {
            $Message = array(
                'message' => "Update failed",
                'error' => $ex
            );
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $Order = Order::find($id);

        if($Order) {
            if($Order->delete()) {

                // delete all tags related to this vendor
                // $Order->tags()->detach();

                $message = array(
                    "message" => "Berhasil menghapus",
                    "data" => $Order
                );

            } else {
                $message = array(
                    "message" => "Gagal menghapus"
                );

            }

        } else {
            $message = array(
                "message" => "Data tidak itemukan"
            );

        }

        return response()->json($message);
    }
}
