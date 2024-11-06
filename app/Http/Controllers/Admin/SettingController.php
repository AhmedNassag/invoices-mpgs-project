<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BackendController;
use App\Http\Requests\SettingRequest;
use App\Libraries\GreenSupport;
use App\Services\Modules\SettingService;
use App\Services\Notifications\SettingNotificationService;
use Illuminate\Http\Request;

class SettingController extends BackendController
{

    public function __construct()
    {
        parent::__construct();

        $this->middleware(['permission:setting'])->only('index', 'store');
    }

    public function index()
    {
        $this->data['timezones'] = GreenSupport::timezones();

        return view('backend.setting.index', $this->data);
    }

    public function store(SettingRequest $request)
    {
        app(SettingService::class)->storeSettingInformation($request);

        return redirect(route('admin.setting.index'))->withSuccess('The setting updated successfully.');
    }

    public function setInvoice(Request $request)
    {
        $this->validate($request, [
            'theme' => 'required|string|max:100',
        ]);

        $themeArray['settingtype'] = 'invoicesetting';
        $themeArray['invoicetheme'] = $request->get('theme');

        setting($themeArray);

        app(SettingNotificationService::class)->invoiceSettingUpdatedToPermissionUser(auth()->user());

        $request->session()->flash('success', 'The invoice theme updated successfully.');
    }
}
