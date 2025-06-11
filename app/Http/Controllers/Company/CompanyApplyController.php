<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\CompanyApplication;
use Illuminate\Http\Request;

class CompanyApplyController extends Controller
{
    // フォーム表示
    public function create()
    {
        return view('company.applies.create');
    }

    // フォーム送信処理
    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name'      => 'required|string|max:255',
            'company_email'     => 'required|email|max:255',
            'company_description' => 'nullable|string|max:2000',
            'contact_name'      => 'required|string|max:255',
            'contact_email'     => 'required|email|max:255',
            'contact_phone'     => 'nullable|string|max:50',
        ]);

        CompanyApplication::create($validated);

        //TODO: メール通知などの処理を追加

        return redirect()->route('company.apply.thanks')->with('status', '申請を受け付けました。運営の審査をお待ちください。');
    }

    // サンクスページ表示
    public function thanks()
    {
        return view('company.applies.thanks');
    }
}
