<?php

namespace App\Http\Controllers;

use App\Http\Resources\VendorDishesTagsResource;
use App\Http\Resources\VendorResource;
use App\Tag;
use App\Vendor;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        if(isset($_GET['tags']) && !empty($_GET['tags'])) {
            return $this->searchVendorDishesByTags($_GET['tags']);
        }
        return VendorResource::collection(Vendor::paginate());
    }

    private function searchVendorDishesByTags($tags) {
        // dd($tags);
        $arr = [];
        // $tag = Tag::where('name', $tags);
        // dd($tag->vendors);
        // dd($tag->pivot->created_at);
        // dd(Tag::all()->pivot);
        foreach($tags as $tag) {
            // $Tag = new Tag();
            $Tag = Tag::where('name', $tag)->first();
            // $arr[] = $Tag->dishes;
            $arr["data"] = VendorDishesTagsResource::collection($Tag->dishes);
        }
        return $arr;
        // echo "called";  
        // return response()->json($tag->vendors);
        // return response()->json(Vendor::find(10));
        // return VendorDishesTagsResource::collection($tag->vendors);
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
            'name' => 'max:128|required'
        ]);

        try {
            $Vendor = new Vendor();
            $Vendor->fill($request->all());
    
            $Vendor->save();

        }catch(QueryException $ex) {
            $message = array(
                "message" => "Gagal menambahkan vendor",
                "error" => $ex
            );
            
            return response()->json($message);
        }
            // save tag if exists
        if($request->has('tag')) {
            $tags = [];
            foreach($request->tag as $tag) {
                $Tag = Tag::where('name', $tag)->first();
                $tags[] = $Tag->id;
            }
            // save the tag object to vendor
            $Vendor->tags()->attach($tags);
        }

        $message = array(
            'message' => "Berhasil menambahkan",
            'data' => new VendorResource($Vendor)
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
        $Vendor = Vendor::find($id);
        if(!$Vendor) {
            $message = array(
                'message' => 'Data tidak ada'
            );

            return response()->json($message);
        }
        return new VendorResource($Vendor);
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
            'name' => 'max:128|required'
        ]);

        $Vendor = Vendor::find($id);
        if(! $Vendor) {
            return response()->json(array(
                'message' => 'Data tidak ada'
            ));
        }

        try {
            $Vendor->fill($request->all());
            $Vendor->save();

        }catch(QueryException $ex) {
            $Message = array(
                'message' => "Update failed",
                'error' => $ex
            );
        }

        $Message = array(
            'message' => "Berhasil mengubah",
            'data' => new VendorResource($Vendor)
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
        $Vendor = Vendor::find($id);

        if($Vendor) {
            if($Vendor->delete()) {

                // delete all tags related to this vendor
                $Vendor->tags()->detach();

                $message = array(
                    "message" => "Berhasil menghapus",
                    "data" => $Vendor
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
