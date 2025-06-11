<?php

namespace App\Http\Controllers;

use App\Models\WorkHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class WorkHistoryController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $workHistories = Auth::user()->workHistories;
        return view('workhistories.index', compact('workHistories'));
    }

    public function create()
    {
        return view('workhistories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_current' => 'nullable|boolean',
            'start_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // is_current が false のときだけ end_date を検証
        $validator->sometimes('end_date', 'required|date|after_or_equal:start_date', function ($input) {
            return !$input->is_current;
        });

        $data = $validator->validate();

        $data['user_id'] = Auth::id();
        $data['end_date'] = $request->input('end_date', null);
        $data['is_current'] = $request->boolean('is_current');

        WorkHistory::create($data);

        return redirect()->route('mypage')->with('success', '職歴を追加しました。');
    }

    public function edit(int $id)
    {
        $workHistory = WorkHistory::findOrFail($id);
        return view('workhistories.edit', compact('workHistory'));
    }

    public function update(Request $request, WorkHistory $workhistory)
    {
        $validator = Validator::make($request->all(), [
            'job_title' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'is_current' => 'nullable|boolean',
            'start_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // is_current が false のときだけ end_date を検証
        $validator->sometimes('end_date', 'required|date|after_or_equal:start_date', function ($input) {
            return !$input->is_current;
        });

        $data = $validator->validate();

        $data['end_date'] = $request->input('end_date', null);
        $data['is_current'] = $request->boolean('is_current');
        $data['tags'] = json_encode($request->input('tags', []));

        $data['tags'] = json_encode($data['tags'] ?? []);

        // 認証されたユーザーが職歴を更新できるか確認
        $this->authorize('update', $workhistory);
        // 職歴を更新
        $workhistory->update($data);

        return redirect()->route('mypage')->with('success', '職歴を更新しました。');
    }

    public function destroy(int $id)
    {
        $workHistory = WorkHistory::findOrFail($id);

        // 職歴の詳細を確認
        $this->authorize('delete', $workHistory);
        
        // 職歴を削除
        $workHistory->delete();

        return redirect()->route('mypage')->with('success', '職歴を削除しました。');
    }
}