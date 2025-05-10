<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $query = Event::query();//query builder for the event model
        $relations = ['user', 'attendees', 'attendees.user'];

        foreach ($relations as $relation) {
            $query->when(//when first parameter is true do the callback function
                $this->shouldIncludeRelation($relation), 
                fn($q) => $q->with($relation));
        }

        //eventResource to return data as json format
        return EventResource::collection(
            $query->latest()->paginate()
        );


    }

    //function to check the included relation from query
    protected function shouldIncludeRelation(string $relation): bool {

        $include = request()->query('include');//retrive the value of include from query

        if(!$include) {
            return false;
        }

        //explode method transform a string into array
        //array_map run trim function over each element of the generated array by expode
        $relations = array_map('trim', explode(',', $include));
        return in_array($relation, $relations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event = Event::create([
            ...$request->validate([//spread operator to copy all elements of the array to the create array
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time'
            ]),
            'user_id' => 1
        ]);

        return new EventResource($event);
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        //UserResource and AttendeeResource r used to display user and attendees when returning an event
        $event->load('user', 'attendees');
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {   
        $event->update(
            $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'sometimes|date',
                'end_time' => 'sometimes|date|after:start_time'
        ]));

        return new EventResource($event);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return response()->json([
            'message' => 'event deleted successfully'
        ]);
    }
}
