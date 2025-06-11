<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = auth()->user()->licenses()->latest()->paginate(10);
        return view('licenses.index', compact('licenses'));
    }
    public function create()
    {
        return view('licenses.create');
    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'acquired_date' => 'required|date',
        ]);

        $data = $validator->validate();
        $data['user_id'] = Auth::id();
        $data['name'] = $request->input('name', null);
        $data['acquired_date'] = $request->input('acquired_date', null);

        License::create($data);

        return redirect()->route('mypage')->with('success', '資格を追加しました。');
    }
    public function edit(int $id)
    {
        $license = auth()->user()->licenses()->findOrFail($id);
        return view('licenses.edit', compact('license'));
    }
    public function update(Request $request, License $license)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'acquired_date' => 'required|date',
        ]);
        $data = $validator->validate();

        $license->update($data);

        return redirect()->route('mypage')->with('success', '資格を更新しました。');
    }
    public function destroy($id)
    {
        $license = auth()->user()->licenses()->findOrFail($id);
        $license->delete();

        return redirect()->route('mypage')->with('success', '資格を削除しました。');
    }
}
