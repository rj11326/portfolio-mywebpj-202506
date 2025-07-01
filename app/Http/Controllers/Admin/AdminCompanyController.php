<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class AdminCompanyController extends Controller
{
    /**
     * 企業一覧を表示
     *
     * @since 1.0.0
     *
     * @param \Illuminate\Http\Request $request リクエストインスタンス
     * @return \Illuminate\View\View 企業一覧ページのビュー
     */
    public function index(Request $request)
    {
        // リクエストからshow_trashedパラメータを取得
        // 1なら論理削除された企業も表示する
        // 0または未指定なら通常の企業のみ表示する
        $showTrashed = $request->input('show_trashed') === '1';

        if ($showTrashed) {
            $companies = Company::onlyTrashed()->orderByDesc('deleted_at')->paginate(20);
        } else {
            $companies = Company::orderByDesc('created_at')->paginate(20);
        }

        return view('admin.companies.index', compact('companies', 'showTrashed'));
    }

    /**
     * 企業の論理削除
     * 
     * @since 1.0.0
     * 
     * @param \App\Models\Company $company 企業モデルインスタンス
     * @return \Illuminate\Http\RedirectResponse 論理削除後のリダイレクトレスポンス
     */
    public function destroy(Company $company)
    {
        // 企業が論理削除されている場合は404エラー
        if ($company->trashed()) {
            return redirect()->route('admin.companies.index')->with('error', '既に削除された企業です');
        }
        // 企業を論理削除
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', '企業を削除しました');
    }

    /**
     * 企業の論理削除を復元
     *
     * @since 1.0.0
     *
     * @param int $id 企業ID
     * @return \Illuminate\Http\RedirectResponse 復元後のリダイレクトレスポンス
     */
    public function restore($id)
    {
        // 企業が論理削除されていない場合は404エラー
        if (!Company::onlyTrashed()->where('id', $id)->exists()) {
            return redirect()->route('admin.companies.index', ['show_trashed' => 1])->with('error', '既に復元された企業です');
        }
        // 企業を論理削除から復元
        // onlyTrashed()で論理削除された企業のみを対象にする
        $company = Company::onlyTrashed()->findOrFail($id);
        $company->restore();
        return redirect()->route('admin.companies.index', ['show_trashed' => 1])->with('success', '企業を復元しました');
    }
}