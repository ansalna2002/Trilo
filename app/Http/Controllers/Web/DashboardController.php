<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AddAvatar;
use App\Models\BannerImage;
use App\Models\Plan;
use App\Models\Security;
use App\Models\SecurityPrompt;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class DashboardController extends Controller
{
  
    public function languages_store(Request $request)
    {
    $validator = Validator::make($request->all(), [
        'banner_name' => 'required|string|max:255',
        'banner_img'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    $imagePath = null;
    if ($request->hasFile('banner_img')) {
        $file = $request->file('banner_img');
        if (!$file->isValid()) {
            return redirect()->back()->with('errormessage', 'File upload failed.');
        }
        $fileName        = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('assets/images/banners');
        $file->move($destinationPath, $fileName);
        $imagePath       = 'assets/images/banners/' . $fileName;
    }

    $banner               = new Language();
    $banner->name  = $request->input('banner_name');
    $banner->image = $imagePath;
    $banner->save();
    return redirect()->back()->with('successmessage', 'language added successfully.');
}
    public function banner_delete($id)
    {
        $banner = Language::find($id);
        if ($banner) {

            if (file_exists(public_path('images/banner/' . $banner->banner_image))) {
                unlink(public_path('images/banner/' . $banner->banner_image));
            }
            $banner->delete();
            return redirect()->back()->with('successmessage', 'language deleted successfully.');
        }
        return redirect()->back()->with('error', 'language not found.');
    }
    public function notification_post(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'notification_title' => 'required|string|max:255',
            'description'        => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            $notification              = new Notification();
            $notification->title       = $request->input('notification_title');
            $notification->description = $request->input('description');
            $notification->save();
    
            return redirect()->route('add_notification')->with('successmessage', 'Notification added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the notification: ' . $e->getMessage())->withInput();
        }
    }
    public function notification_delete($id)
    {
        $notification = Notification::findOrFail($id);
        if($notification) 
        {
        $notification->delete();
        return redirect()->route('add_notification')->with('successmessage', 'Notification deleted successfully.');
        }
        return redirect()->back()->with('errormessage','Error not found');
   
    }

    public function plan_update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coins'           => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
       
        try {
            $coins         = new Plan();
            $coins->coins  = $request->input('coins');
            $coins->amount = $request->input('amount');
            $coins->save();
    
            return redirect()->back()->with('successmessage', 'Notification added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while adding the notification: ' . $e->getMessage())->withInput();
        }
    }
    public function talktime_delete($id)
    {
        $plan = Plan::find($id); 
                if (!$plan) {
            return redirect()->back()->with('error', 'Plan does not exist.');
        }
                $plan->delete();
        return redirect()->back()->with('success', 'Plan deleted successfully!');
    }
    
    public function prompt_delete($id)
    {
        $plan = SecurityPrompt::find($id); 
                if (!$plan) {
            return redirect()->back()->with('error', 'Security prompt does not exist.');
        }
                $plan->delete();
        return redirect()->back()->with('success', 'Security prompt deleted successfully!');
    }
    
    public function update_talktime(Request $request, $id)
{
    $request->validate([
        'plan'           => 'required|string|max:255',
        'type'           => 'required|in:message,voice_call,video_call',
        'talk_time'      => 'required|string|max:255',
        'amount'         => 'required|numeric',
        'available_days' => 'required|integer',
    ]);
    $talktime = Plan::findOrFail($id);
    $talktime->update([
        'plan'           => $request->plan,
        'type'           => $request->type,
        'talk_time'      => $request->talk_time,
        'amount'         => $request->amount,
        'available_days' => $request->available_days,
    ]);
    return redirect()->route('talktime_management')->with('success', 'Talktime Plan updated successfully!');
}
public function add_security(Request $request)
{
    $validator = Validator::make($request->all(), [
        'voice' => 'required|string|max:255',
    ]);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    try {
        $voice                 = new SecurityPrompt();
        $voice->prompt_content = $request->input('voice');
        $voice->save();
        return redirect()->route('security')->with('successmessage', 'Notification added successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred while adding the notification: ' . $e->getMessage())->withInput();
    }
}
public function update_prompt(Request $request, $id)
{
    $request->validate([
        'voice' => 'required|string|max:255', 
    ]);
    try {
        $talktime = SecurityPrompt::findOrFail($id);
        if (!$talktime) {
            return redirect()->back()->with('error', 'Security prompt does not exist.');
        }
        $talktime->prompt_content = $request->input('voice');
        $talktime->save();
        return redirect()->route('security')->with('successmessage', 'Plan updated successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

public function add_avatar(Request $request)
{
$validator = Validator::make($request->all(), [
   
    'banner_img'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
]);
if ($validator->fails()) {
    return redirect()->back()->withErrors($validator)->withInput();
}

$imagePath = null;
if ($request->hasFile('banner_img')) {
    $file = $request->file('banner_img');
    if (!$file->isValid()) {
        return redirect()->back()->with('errormessage', 'File upload failed.');
    }
    $fileName        = time() . '_' . $file->getClientOriginalName();
    $destinationPath = public_path('assets/images/banners');
    $file->move($destinationPath, $fileName);
    $imagePath       = 'assets/images/banners/' . $fileName;
}

$banner               = new AddAvatar();
$banner->image = $imagePath;
$banner->save();
return redirect()->back()->with('successmessage', 'language added successfully.');
}


public function banner_post(Request $request)
{
    
    $validator = Validator::make($request->all(), [
        'banner_name' => 'required|string|max:255',
        'banner_img'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }
    
    $imagePath = null;
    if ($request->hasFile('banner_img')) {
        $file = $request->file('banner_img');
        if (!$file->isValid()) {
            return redirect()->back()->with('errormessage', 'File upload failed.');
        }
        $fileName        = time() . '_' . $file->getClientOriginalName();
        $destinationPath = public_path('assets/images/banners');
        $file->move($destinationPath, $fileName);
        $imagePath       = 'assets/images/banners/' . $fileName;
    }

    $banner               = new BannerImage();
    $banner->banner_name  = $request->input('banner_name');
    $banner->banner_image = $imagePath;
    $banner->save();
    return redirect()->back()->with('successmessage', 'Banner added successfully.');
}


}
