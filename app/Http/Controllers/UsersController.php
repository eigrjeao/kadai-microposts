<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    // 中略

    /**
     * ユーザーのフォロー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
    // 全ユーザーを取得（ページネーションつき）
    $users = User::paginate(10);

    // users.indexビューに$usersを渡す
    return view('users.index', ['users' => $users]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->loadRelationshipCounts();

        $microposts = $user->microposts()->orderBy('created_at', 'desc')->paginate(10);

        return view('users.show', [
            'user' => $user,
            'microposts' => $microposts,
        ]);
    }

    public function followings($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロー一覧を取得
        $followings = $user->followings()->paginate(10);

        // フォロー一覧ビューでそれらを表示
        return view('users.followings', [
            'user' => $user,
            'users' => $followings,
        ]);
    }

    /**
     * ユーザーのフォロワー一覧ページを表示するアクション。
     *
     * @param  $id  ユーザーのid
     * @return \Illuminate\Http\Response
     */
    public function followers($id)
    {
        // idの値でユーザーを検索して取得
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのフォロワー一覧を取得
        $followers = $user->followers()->paginate(10);

        // フォロワー一覧ビューでそれらを表示
        return view('users.followers', [
            'user' => $user,
            'users' => $followers,
        ]);
    }

    /**
     * ユーザーのお気に入り投稿一覧ページを表示するアクション。
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function favorites($id)
    {
        $user = User::findOrFail($id);

        // 関係するモデルの件数をロード
        $user->loadRelationshipCounts();

        // ユーザーのお気に入り投稿一覧を取得
        $favorites = $user->favorites()->orderBy('created_at', 'desc')->paginate(10);

        // お気に入り一覧ビューで表示
        return view('users.favorites', [
        'user' => $user,
        'microposts' => $favorites,
        ]);
    }
}