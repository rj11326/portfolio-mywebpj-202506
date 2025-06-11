<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyProfileController extends Controller
{
    // 会社情報の表示
    public function show()
    {
        $company = Auth::guard('company')->user()->company;
        return view('company.profiles.show', compact('company'));
    }

    // 会社情報編集フォーム
    public function edit()
    {
        $company = Auth::guard('company')->user()->company;
        return view('company.profiles.edit', compact('company'));
    }

    // 更新処理
    public function update(Request $request)
    {
        $company = Auth::guard('company')->user()->company;

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|max:255',
            'description'     => 'nullable|string|max:2000',
            'founded_at'      => 'nullable|date',
            'capital'         => 'nullable|integer',
            'employee_count'  => 'nullable|integer',
        ]);

        $company->update($validated);

        return redirect()->route('company.profiles.show')->with('status', '会社情報を更新しました');
    }
}
