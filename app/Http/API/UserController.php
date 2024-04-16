<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function index($id){
    // Ambil data pengguna dari JSON Placeholder
        $userResponse = Http::get('https://jsonplaceholder.typicode.com/users/' . $id);
        $user = $userResponse->json();

        // Ambil data terkait pengguna (todos, posts, albums, photos, comments)
        $todosResponse = Http::get('https://jsonplaceholder.typicode.com/todos', ['userId' => $id]);
        $postsResponse = Http::get('https://jsonplaceholder.typicode.com/posts', ['userId' => $id]);
        $albumsResponse = Http::get('https://jsonplaceholder.typicode.com/albums', ['userId' => $id]);
        $photosResponse = Http::get('https://jsonplaceholder.typicode.com/photos', ['albumId' => $albumsResponse->json()]);
        $commentsResponse = Http::get('https://jsonplaceholder.typicode.com/comments', ['postId' => $postsResponse->json()]);

        // Gabungkan data dalam array
        $data = [
            'user' => $user,
            'todos' => $todosResponse->json(),
            'posts' => $postsResponse->json(),
            'albums' => $albumsResponse->json(),
            'photos' => $photosResponse->json(),
            'comments' => $commentsResponse->json(),
        ];

        // Kembalikan data sebagai respons JSON
        return response()->json($data);
    }
}
