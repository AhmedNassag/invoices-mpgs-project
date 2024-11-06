<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuickUserRequest;
use App\Models\Product;
use App\Models\TaxRate;
use App\Models\User;
use App\Services\Modules\UserService;
use App\Services\Notifications\UserNotificationService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UtilityController extends BackendController
{
    private UserService $userService;
    private UserNotificationService $userNotificationService;

    public function __construct(UserService $userService, UserNotificationService $userNotificationService)
    {
        parent::__construct();

        $this->userService = $userService;
        $this->userNotificationService = $userNotificationService;
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function getProductList(Request $request)
    {
        $products = Product::query()
            ->where('name', 'like', '%' . $request->get('search') . '%')
            ->latest()
            ->take(20)
            ->get();

        if (blank($products)) {
            return json_encode([]);
        }

        $productList = [];
        foreach ($products as $product) {
            $productList[] = ["id" => $product->id, "text" => $product->name, "price" => $product->price];
        }

        return json_encode($productList);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function getTaxList(Request $request)
    {
        $taxes = TaxRate::query()
            ->where('name', 'like', '%' . $request->get('search') . '%')
            ->latest()
            ->take(20)
            ->get();

        if (blank($taxes)) {
            return json_encode([]);
        }

        $taxList = [];
        foreach ($taxes as $tax) {
            $taxList[] = ["id" => $tax->id, "text" => tax_name_generate($tax), "percent" => $tax->percent];
        }
        return json_encode($taxList);
    }

    /**
     * @param QuickUserRequest $request
     * @return JsonResponse
     */
    public function addQuickUser(QuickUserRequest $request)
    {
        try {
            $username = $this->userService->generateUniqueUsername($request->get('name'));

            $request->mergeIfMissing([
                'role' => 3,
                'status' => 1,
                'username' => $username,
                'password' => '123456@#'
            ]);

            if (blank($request->get('email'))) {
                $request->merge(['email' => $username . '@gmail.com']);
            }

            $createdUser = User::query()->where('email', $request->get('email'))->first();
            if (blank($createdUser)) {
                // Save User Information
                $createdUser = $this->userService->saveUser(new User(), $request);

                $this->userService->saveUserRole($createdUser, $request->get('role', 3));

                $this->userNotificationService->userAddedToPermissionUser($createdUser, auth()->user());
            }

            return response()->json([
                'value' => $createdUser?->id,
                'label' => $createdUser?->name
            ]);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json();
        }
    }
}
