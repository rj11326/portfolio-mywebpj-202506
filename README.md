# プロジェクト概要

個人ポートフォリオ用のサンプルです。
Laravel + PostgreSQL + Tailwind CSS + Alpine.js によるシンプルなWeb求人サイトです。  
企業/管理者向けの多機能な管理画面を備え、ユーザーの求人検索～応募～管理をトータルで実現。

---

## 技術スタック

- バックエンド: PHP (Laravel 12)
- フロントエンド: Blade、Tailwind CSS、Alpine.js
- データベース: PostgreSQL
- テスト: PHPUnit, Laravel Dusk
- その他: Composer, npm, Webpack, Git

---

## 主な機能

- 求人検索・一覧表示
- 求人への応募（履歴書・職務経歴書アップロード対応）
- 応募履歴の管理・確認
- マイページ編集（プロフィール・職歴・学歴・資格）
- メッセージ機能(ユーザー・企業間)
- 企業による求人管理・応募者管理
- 管理者による全体管理（求人・企業・カテゴリ・タグ等）
- 認証機能（求職者・企業・管理者で分離）

---

## デモ環境

本アプリケーションは [Fly.io](https://fly.io/) 上で動作しています。

- アクセスURL: [https://jobsite-withered-dust-5557.fly.dev](https://jobsite-withered-dust-5557.fly.dev)
- 無料プランなので初回読み込みに時間が掛かります。

---

## 使い方（デモ操作方法）

### ユーザー（求職者）として利用する場合

1. トップページや求人一覧ページから求人情報を閲覧出来ます。
2. 求人詳細ページの「応募する」ボタンから、応募フォームにアクセス出来ます。
3. 必要事項（志望動機、メッセージ、職務経歴書ファイルなど）を入力し、送信すると応募が完了します。
4. 「マイページ」から応募履歴やプロフィール情報を確認・編集出来ます。

### 企業アカウントの場合

1. ログイン画面: [https://jobsite-withered-dust-5557.fly.dev/company/login](https://jobsite-withered-dust-5557.fly.dev/company/login)からログイン出来ます。(ダミーデータでのログイン情報は下記参照)
2. 管理画面から求人情報の登録や応募者管理、会社情報の編集、会社画像の編集が可能です。


### 管理者アカウントの場合

1. ログイン画面: [https://jobsite-withered-dust-5557.fly.dev/admin/login](https://jobsite-withered-dust-5557.fly.dev/admin/login)からログイン出来ます。
2. 管理画面から企業情報の管理、求人管理、カテゴリ管理、タグ管理が可能です。

---

## デモ環境のアカウント情報(サンプル)

### ユーザーアカウント
- メールアドレス: test1@example.com
- パスワード: password

### 企業アカウント
- 企業1
    - メールアドレス: sample1@example.com
    - パスワード: password
- 企業2
    - メールアドレス: sample2@example.com
    - パスワード: password
- 企業3
    - メールアドレス: sample3@example.com
    - パスワード: password


### 管理者アカウント
- メールアドレス: admin@example.com
- パスワード: password

---

## 注意事項

- テスト用のダミーデータが含まれている場合があります。
- デモ環境のデータは定期的にリセットされる場合があります。
- 個人情報や重要なデータは入力しないようにしてください。

---
