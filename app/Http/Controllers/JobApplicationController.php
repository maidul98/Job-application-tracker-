<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\JobApplication;
use JWTAuth;
use Illuminate\Support\Facades\Auth;
class JobApplicationController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'position' => 'required',
            'company' => 'required',
            'location' => 'required',
        ]);

        return JobApplication::create(['user_id'=> Auth::user()->id, 'position' =>$request->position, 'company' =>$request->company, 'location'=>$request->location, 'description'=>$request->description, 'icon_id'=>1]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $jobApp = JobApplication::findOrFail($request->id);
        $jobApp->position = $request->position;
        $jobApp->company = $request->company;
        $jobApp->location = $request->location;
        $jobApp->description = $request->description;
        
        $jobApp->save();
        
        return ['success'=>True];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        JobApplication::findOrFail($request->id)->delete();
        
        return ['success'=>True];
    }

    /**
     * Show all job apps
     */

     public function showApps(){
         return JobApplication::find(Auth::user()->id)->paginate(10);
     }
}
