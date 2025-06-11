<?php

namespace App\Http\Controllers;

use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $educations = auth()->user()->educations()->latest()->paginate(10);
        return view('educations.index', compact('educations'));
    }

    public function create()
    {
        return view('educations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'major' => 'nullable|string|max:255',
        ]);

        $data = $validator->validate();
        $data['user_id'] = Auth::id();
        $data['end_date'] = $request->input('end_date', null);
        $data['degree'] = $request->input('degree', null);
        $data['major'] = $request->input('major', null);

        Education::create($data);

        return redirect()->route('mypage')->with('success', '学歴を追加しました。');
    }

    public function edit(int $id)
    {
        $education = auth()->user()->educations()->findOrFail($id);
        return view('educations.edit', compact('education'));
    }

    public function update(Request $request, Education $education)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'degree' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'major' => 'nullable|string|max:255',
        ]);
        $data = $validator->validate();
        $data['end_date'] = $request->input('end_date', null);
        $data['degree'] = $request->input('degree', null);
        $data['major'] = $request->input('major', null);

        $this->authorize('update', $education);

        $education->update($data);

        return redirect()->route('mypage')->with('success', '学歴を更新しました。');
    }

    public function destroy($id)
    {
        $education = auth()->user()->educations()->findOrFail($id);
        $education->delete();

        return redirect()->route('mypage')->with('success', '学歴を削除しました。');
    }
}
