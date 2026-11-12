<?php

namespace App\Http\Controllers;

use App\Models\Documentary;
use App\Models\Education;
use App\Models\GalleryItem;
use App\Models\Profile;
use App\Models\Slide;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    /** type => [model class, validation rules] */
    private function types(): array
    {
        return [
            'social' => [SocialLink::class, [
                'platform' => ['required', 'string', 'max:50'],
                'url'      => ['required', 'url', 'max:255'],
            ]],
            'education' => [Education::class, [
                'institution' => ['required', 'string', 'max:255'],
                'degree'      => ['nullable', 'string', 'max:255'],
                'field'       => ['nullable', 'string', 'max:255'],
                'start_year'  => ['nullable', 'string', 'max:10'],
                'end_year'    => ['nullable', 'string', 'max:10'],
                'description' => ['nullable', 'string', 'max:2000'],
            ]],
            'documentary' => [Documentary::class, [
                'title'       => ['required', 'string', 'max:255'],
                'youtube_url' => ['required', 'url', 'max:255'],
                'description' => ['nullable', 'string', 'max:2000'],
            ]],
            'gallery' => [GalleryItem::class, [
                'title' => ['nullable', 'string', 'max:255'],
                'image' => ['required', 'image', 'max:4096'],
            ]],
            'slide' => [Slide::class, [
                'title'   => ['nullable', 'string', 'max:255'],
                'caption' => ['nullable', 'string', 'max:255'],
                'image'   => ['required', 'image', 'max:4096'],
            ]],
        ];
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'profile'       => Profile::first() ?? new Profile(),
            'slides'        => Slide::orderBy('sort_order')->get(),
            'socials'       => SocialLink::orderBy('sort_order')->get(),
            'education'     => Education::orderBy('sort_order')->get(),
            'documentaries' => Documentary::orderBy('sort_order')->get(),
            'gallery'       => GalleryItem::orderBy('sort_order')->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name'                => ['required', 'string', 'max:255'],
            'headline'            => ['nullable', 'string', 'max:255'],
            'bio'                 => ['nullable', 'string', 'max:5000'],
            'email'               => ['nullable', 'email', 'max:255'],
            'phone'               => ['nullable', 'string', 'max:50'],
            'location'            => ['nullable', 'string', 'max:255'],
            'youtube_channel_url' => ['nullable', 'url', 'max:255'],
            'photo'               => ['nullable', 'image', 'max:4096'],
        ]);

        $profile = Profile::first() ?? new Profile();

        if ($request->hasFile('photo')) {
            if ($profile->photo) {
                Storage::disk('public')->delete($profile->photo);
            }
            $data['photo'] = $request->file('photo')->store('uploads', 'public');
        } else {
            unset($data['photo']);
        }

        $profile->fill($data)->save();

        return back()->with('status', 'Profile saved.');
    }

    public function store(Request $request, string $type)
    {
        [$model, $rules] = $this->types()[$type];
        $data = $request->validate($rules);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads', 'public');
        }

        $data['sort_order'] = ((int) $model::max('sort_order')) + 1;
        $model::create($data);

        return back()->with('status', ucfirst($type) . ' added.');
    }

    public function destroy(string $type, int $id)
    {
        [$model] = $this->types()[$type];
        $item = $model::findOrFail($id);

        if (!empty($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return back()->with('status', ucfirst($type) . ' deleted.');
    }
}
