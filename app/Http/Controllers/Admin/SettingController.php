<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSettings;
use App\Models\LogoSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Throwable;

class SettingController extends Controller
{

    public function general()
    {
        try {
            $data['title'] = 'General Settings';
            $data['admin_user'] = Auth::user();

            $settings = generalSettings();

            return view('admin.settings.general-settings', $data, $settings);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }

    public function storeGeneral(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required',
            'og_meta_title' => 'nullable',
            'og_meta_description' => 'nullable',
            'og_meta_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'send_sms' => 'nullable',
        ], [
            'site_name' => 'Site name is required.',
        ]);

        // for validation check image
        $acceptable = ['jpeg', 'png', 'jpg', 'gif'];

        try {
            if ($request->hasFile('og_meta_image')) {
                $image = $request->file('og_meta_image');

                if (!in_array($image->getClientOriginalExtension(), $acceptable)) {
                    return $this->backWithSuccess('Only jpeg, png, jpg and gif file is supported.');
                }

                $filename = time() . $image->getClientOriginalName();
                $image->move(public_path('/upload/generalSettings/'), $filename);
                $path = "/upload/generalSettings/" . $filename;
                $data['og_meta_image'] = $path;
            }

            foreach ($data as $key => $value) {
                GeneralSettings::updateOrCreate(['key' => $key], ['value' => $value]);
            }

            if (generalSettings('og_meta_image')) {
                $path = generalSettings('og_meta_image');
                if (file_exists(public_path($path))) {
                    unlink(public_path($path));
                }
            }

            Cache::forget(CACHE_GENERAL_SETTINGS);

            return back()->with('success', 'General settings are updated successfully');
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }

    public function logoFavicon()
    {
        try {
            $data['title'] = 'Logo and Favicon';
            $data['admin_user'] = Auth::user();
            $data['logoSettings'] = LogoSettings::first();

            return view('admin.settings.logo-favicon', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }

    public function storeLogoFavicon(Request $request)
    {
        try {
            $logoFaviconSettingStore = LogoSettings::first() ? LogoSettings::first() : new LogoSettings();
            //            If has already any logo delete it first then store new logo
            if ($request->hasFile('logo')) {
                if (LogoSettings::first()) {
                    if ($logoFaviconSettingStore->logo) {
                        $path = $logoFaviconSettingStore->logo;
                        if (file_exists(public_path($path))) {
                            unlink(public_path($path));
                        }
                    }
                }
                $images = $request->logo;
                foreach ($images as $img) {
                    $image = $img;
                    $filename = time() . $image->getClientOriginalName();
                }
                $img->move(public_path('/upload/logoFavicon/'), $filename);
                $path = "/upload/logoFavicon/" . $filename;
                $logoFaviconSettingStore->logo = $path;
            }

            //            If has already any favicon delete it first then store new logo
            if ($request->hasFile('favicon')) {
                if (LogoSettings::first()) {
                    if ($logoFaviconSettingStore->favicon) {
                        $path = $logoFaviconSettingStore->favicon;
                        if (file_exists(public_path($path))) {
                            unlink(public_path($path));
                        }
                    }
                }
                $images = $request->favicon;
                foreach ($images as $img) {
                    $image = $img;
                    $filename = time() . $image->getClientOriginalName();
                }
                $img->move(public_path('/upload/logoFavicon/'), $filename);
                $path = '/upload/logoFavicon/' . $filename;
                $logoFaviconSettingStore->favicon = $path;
            }
            $logoFaviconSettingStore->save();

            return back()->with('success', 'Logo Favicon are updated successfully');
        } catch (Throwable $th) {
            return $this->backWithError($th->getMessage());
        }
    }
}
