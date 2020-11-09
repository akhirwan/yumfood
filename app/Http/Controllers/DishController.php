<?php

namespace App\Http\Controllers;

use App\Dish;
use App\Vendor;
use App\Tag;
use App\Http\Resources\DishResource;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DishController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return DishResource::collection(Dish::paginate());
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
            'title' => 'required|max:100',
            'vendor_id' => 'required|numeric',
            'price' => 'required|numeric'
        ]);
        
        
        // check if vendor is exists
        $Vendor = Vendor::find($request->vendor_id);
        if(! $Vendor) {
            return response()->json(array(
                'message' => 'Vendor tidak ditemukan'
            ));
        }

        try {
            $Dish = new Dish();
            $Dish->fill($request->all());
            $Dish->save();
            // $Dish = Dish::create($request->all());
        } catch(QueryException $ex) {
            $message = array(
                "message" => "Gagal menambahkan menu",
                "error" => $ex
            );
            return response()->json($message);
        }


        // add tags to dishes if tags exists in request
        if($request->has('tags')) {
            $tags = [];
            foreach($request->tags as $tag) {
                $tags[] = Tag::where('name', $tag)->first()->id;
            }
            $Dish->tags()->attach($tags);
        }

        $message = array(
            "message" => "Berhasil menambahkan menu",
            "data" => new DishResource($Dish)
        );

        return response()->json($message);
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
        $Dish = Dish::find($id);
        if(!$Dish) {
            $message = array(
                'message' => 'Data tidak ada'
            );

            return response()->json($message);
        }
        return new DishResource($Dish);
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
            'title' => 'max:100',
            'vendor_id' => 'numeric',
            'price' => 'numeric'
        ]);

        $Dish = Dish::find($id);
        if(! $Dish) {
            return response()->json(array(
                'message' => 'Data tidak ada'
            ));
        }

        try {
            $Dish->fill($request->all());
            $Dish->save();

        }catch(QueryException $ex) {
            $Message = array(
                'message' => "Update failed",
                'error' => $ex
            );
        }
        $Message = array(
            'message' => "Berhasil mengubah",
            'data' => new DishResource($Dish)
        );


        return response()->json($Message);
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
        $Dish = Dish::find($id);

        if($Dish) {
            if($Dish->delete()) {

                // delete all tags related to this vendor
                $Dish->tags()->detach();

                $message = array(
                    "message" => "Berhasil menghapus",
                    "data" => $Dish
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
