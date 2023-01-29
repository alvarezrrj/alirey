<?php

namespace App\Http\Controllers;

use App\Models\Config;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigController extends Controller
{
    public function index(User $user)
    {
        if(! Gate::allows('admin-only')) {
            return redirect('/dashboard');
        }

        $config = Config::where('user_id', $user->id);
        return View('config.index', [
            'config' => $config,
        ]);
    }
}
