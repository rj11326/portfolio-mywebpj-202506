<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    // 一覧
    public function index(Request $request)
    {
        $showTrashed = $request->input('show_trashed') === '1';

        if ($showTrashed) {
            $companies = Company::onlyTrashed()->orderByDesc('deleted_at')->paginate(20);
        } else {
            $companies = Company::orderByDesc('created_at')->paginate(20);
        }

        return view('admin.companies.index', compact('companies', 'showTrashed'));
    }

    // 論理削除
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', '企業を削除しました');
    }

    // 論理削除の復元
    public function restore($id)
    {
        $company = Company::onlyTrashed()->findOrFail($id);
        $company->restore();
        return redirect()->route('admin.companies.index', ['show_trashed' => 1])->with('success', '企業を復元しました');
    }
}