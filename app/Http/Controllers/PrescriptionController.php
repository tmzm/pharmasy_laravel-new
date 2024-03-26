<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePrescriptionRequest;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
        /**
     * Display a listing of the resource.
     * @param Request $request
     */
    public function index(Request $request)
    {
        self::ok(Prescription::latest()->get());
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     */
    public function create(CreatePrescriptionRequest $request)
    {
        $data = $request->validated();

        $image = self::save_image_to_public_directory($request);

        if($image !== false)
            $data['image'] = $image;

        self::ok(
            Prescription::create([
                'user_id' => $request->user()->id,
                'description' =>  $data['description'],
                'image' => $data['image']
            ])
        );
    }

    // /**
    //  * Display the specified resource.
    //  * @param Request $request
    //  * @param $order_id
    //  */
    // public function show(Request $request,$order_id): void
    // {
    //     self::get_user_order_by_id($order_id,$request->user()->id);
    // }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $order_id
     */
    public function update($prescription_id,$order_id): void
    {
        Prescription::find($prescription_id)->update(['order_id' => $order_id]);
    }

    // /**
    //  * Remove the specified resource from storage.
    //  * @param Request $request
    //  * @param $order_id
    //  */
    // public function destroy(Request $request,$order_id): void
    // {
    //      self::delete_order($request,$order_id);
    // }
}
