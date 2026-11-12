<?php

namespace App\Http\Controllers;

use App\Models\Documentary;
use App\Models\Education;
use App\Models\GalleryItem;
use App\Models\Profile;
use App\Models\Slide;
use App\Models\SocialLink;

class PublicController extends Controller
{
    public function home()
    {
        return view('home', [
            'profile'       => Profile::first(),
            'slides'        => Slide::orderBy('sort_order')->get(),
            'socials'       => SocialLink::orderBy('sort_order')->get(),
            'education'     => Education::orderBy('sort_order')->get(),
            'documentaries' => Documentary::orderBy('sort_order')->get(),
            'gallery'       => GalleryItem::orderBy('sort_order')->get(),
        ]);
    }
}
