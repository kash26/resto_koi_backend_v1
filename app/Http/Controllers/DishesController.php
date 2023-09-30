<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Http\Requests\StoreDishRequest;
use App\Http\Requests\UpdateDishRequest;
use App\Http\Resources\DishesResource;

class DishesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DishesResource::collection(Dish::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDishRequest $request)
    {
        $request->validated($request->all());

        if ($request->hasFile('photo')) {
            $dish = Dish::create([
                'category_id' => $request->category_id,
                'origin_id' => $request->origin_id,
                'name' => $request->name,
                'price' => $request->price,
                'preparation_time' => $request->preparation_time,
                'description' => $request->description,
            ]);

            $uploadFolder = 'photos';
            $image = $request->file('photo');
            $dish->photo = $image->store($uploadFolder, 'public');
        }

        $dish->save();

        return new DishesResource($dish);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dish $dish)
    {
        return new DishesResource($dish);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDishRequest $request, Dish $dish)
    {
        // $dish->update($request->all());

        // return new DishesResource($dish);

        $request->validated($request->all());

        $dish = Dish::findOrFail($dish);

        $dish->category_id = $request->category_id;
        $dish->origin_id = $request->origin_id;
        $dish->name = $request->name;
        $dish->price = $request->price;
        $dish->preparation_time = $request->preparation_time;
        $dish->description = $request->description;

        if ($request->hasFile('photo')) {
            Storage::delete($dish->photo);
            $uploadFolder = 'photos';
            $image = $request->file('photo');
            $dish->photo = $image->store($uploadFolder, 'public');
        }

        $dish->save();

        return new DishesResource($dish);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        return $dish->delete();
    }
}
