<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class HomeSlideController extends Controller
{
    public function homeSlide()
    {
        $homeSlide = HomeSlide::find(1);
        return view('admin.home_slide.home_slide_all', compact('homeSlide'));
    }

    public function updateSlide(Request $request)
    {
        $id = $request->id;
        if ($request->file('home_slide')) {
            $image = $request->file('home_slide');
            $name_generate = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(636, 852)->save('upload/home_slide/'.$name_generate);
            $save_url = 'upload/home_slide/'.$name_generate;

            HomeSlide::findOrFail($id)->update([
               'title' => $request->title,
               'short_title' => $request->short_title,
               'video_url' => $request->video_url,
               'home_slide' => $save_url,
            ]);

            $notification = array(
                'message' => 'Updated Home Slide with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        } else {
            HomeSlide::findOrFail($id)->update([
                'title' => $request->title,
                'short_title' => $request->short_title,
                'video_url' => $request->video_url,
            ]);

            $notification = array(
                'message' => 'Updated Home Slide without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->back()->with($notification);
        }
    }
}
