<?php

use App\Http\Controllers\TodosController;
use App\Http\Controllers\UserController;
use App\Models\User;
use GuzzleHttp\Psr7\Response as Psr7Response;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});






// // /users
Route::get('/users', function (Request $request) {
    // Ambil data dari JSONPlaceholder
    $users = Http::get('https://jsonplaceholder.typicode.com/posts')->json();
    
 
    $userId = $request->query('userId');
    if ($userId) {
        $users = array_filter($users, function($user) use ($userId) {
            return $user['id'] == $userId;
        });
    }

    return response()->json($users);
});


// /todos
Route::get('/todos', function (Request $request) {
    $todos = Http::get('https://jsonplaceholder.typicode.com/todos')->json();
    
 
    $userId = $request->query('userId');
    if ($userId) {
        $todos = array_filter($todos, function($todo) use ($userId) {
            return $todo['userId'] == $userId;
        });
    }

    $completed = $request->query('completed');
    if ($completed !== null) {
        $todos = array_filter($todos, function($todo) use ($completed) {
            return $todo['completed'] == ($completed === 'true');
        });
    }

    return response()->json($todos);
});

// /photos
Route::get('/photos', function (Request $request) {
    $photos = Http::get('https://jsonplaceholder.typicode.com/photos')->json();

    $albumId = $request->query('albumId');
    if ($albumId) {
        $photos = array_filter($photos, function($photo) use ($albumId) {
            return $photo['albumId'] == $albumId;
        });
    }

    return response()->json($photos);
});

// /albums
Route::get('/albums', function (Request $request) {
    $albums = Http::get('https://jsonplaceholder.typicode.com/albums')->json();
    
    $userId = $request->query('userId');
    if ($userId) {
        $albums = array_filter($albums, function($album) use ($userId) {
            return $album['userId'] == $userId;
        });
    }

    return response()->json($albums);
});

// /comments
Route::get('/comments', function (Request $request) {
    $comments = Http::get('https://jsonplaceholder.typicode.com/comments')->json();
    
    $postId = $request->query('postId');
    if ($postId) {
        $comments = array_filter($comments, function($comment) use ($postId) {
            return $comment['postId'] == $postId;
        });
    }

    return response()->json($comments);
});

// /posts
Route::get('/posts', function (Request $request) {
    $posts = Http::get('https://jsonplaceholder.typicode.com/posts')->json();
    
    $userId = $request->query('userId');
    if ($userId) {
        $posts = array_filter($posts, function($post) use ($userId) {
            return $post['userId'] == $userId;
        });
    }

    return response()->json($posts);
});


Route::get('/users/{id}', function(Request $request, $id){
    $response = Http::get('https://jsonplaceholder.typicode.com/users');
    $users = $response->json();
    
    
        $users_filter = array_filter($users, function($user) use ($id) {
            return $user['id'] == $id;
        });
    
    
    return response()->json($users_filter);
});

Route::get('/todos/{id}', function ($id) {
    $todos = Http::get('https://jsonplaceholder.typicode.com/todos')->json();

    $todos_filter = array_filter($todos, function ($todo) use ($id) {

        return $todo["id"] == $id;
    });

    return response()->json($todos_filter);
});


Route::get('/albums/{id}', function ($id) {
    $albums  = Http::get('https://jsonplaceholder.typicode.com/albums')->json();

    $albums_filter = array_filter($albums, function($album) use ($id){
        return $album['id'] == $id;
    });

    return response()->json($albums_filter);
});


Route::get('/posts/{id}', function ($id) { 
    $posts = Http::get('https://jsonplaceholder.typicode.com/posts')->json();

    $posts_filter = array_filter($posts, function ($post) use ($id) {
        return  $post['id'] == $id;
    });

    return response()->json($posts_filter);

});


Route::get('/photos/{id}', function ($id) { 
    $photos = Http::get('https://jsonplaceholder.typicode.com/photos')->json();

    $photos_filter = array_filter($photos, function ($photo) use ($id) {
       return   $photo['id'] == $id;
    });

    return response()->json($photos_filter);

});


Route::get('/comments/{id}', function ($id) { 
    $comments = Http::get('https://jsonplaceholder.typicode.com/comments')->json();

    $comments_filter = array_filter($comments, function ($comment) use ($id) {
       return  $comment['id'] == $id;
    });

    return response()->json($comments_filter);

});








//get data dengan id dan parameter

Route::get('/users/{id}/{param}', function (Request $request, $id, $param) {
    $posts = Http::get('https://jsonplaceholder.typicode.com/posts')->json();
    $comments = Http::get('https://jsonplaceholder.typicode.com/comments')->json();
    $albums = Http::get('https://jsonplaceholder.typicode.com/albums')->json();
    $photos = Http::get('https://jsonplaceholder.typicode.com/photos')->json();
    $todos = Http::get('https://jsonplaceholder.typicode.com/todos')->json();

    $result = [];
    switch ($param) {
        case 'posts':

            $result = array_filter($posts, function ($post) use ($id) {
                return $post['userId'] == $id;
            });
            break;
        
        case 'comments':

            
            $result = array_filter($comments, function ($comment) use ($id) {
                return $comment['postId'] == $id; 
            });
            break;

        case 'albums':
            $result = array_filter($albums, function ($album) use ($id) {
                return $album['userId'] == $id;
            });
            break;
            
        case 'photos':
            $userAlbums = array_filter($albums, function ($album) use ($id) {
                return $album['userId'] == $id;
            });
            $userAlbumIds = array_map(function ($album) {
                return $album['id'];
            }, $userAlbums);
            
            $result = array_filter($photos, function ($photo) use ($userAlbumIds) {
                return in_array($photo['albumId'], $userAlbumIds);
            });
            break;
        
        case 'todos':
            $result = array_filter($todos, function ($todo) use ($id) {
                return $todo['userId'] == $id;
            });

             $completed = $request->query('completed');
                if ($completed !== null) {
                     $todos = array_filter($todos, function($todo) use ($completed) {
                 return $todo['completed'] == ($completed === 'true');
                 });
             }
                return response()->json($todos);
            
            break;

        default:
            return response()->json(['error' => 'Invalid parameter'], 404);          
    }
    return response()->json($result);
});



// Route::get('/users/{userId}/comments', function ($userId) {
//     // Ambil data dari JSONPlaceholder API
//     // Ambil semua postingan pengguna berdasarkan user_id
//     $postsResponse = Http::get('https://jsonplaceholder.typicode.com/posts', [
//         'userId' => $userId
//     ]);
    
//     // Periksa apakah permintaan berhasil
//     if ($postsResponse->successful()) {
//         $posts = $postsResponse->json();

//         // Ambil semua komentar yang terkait dengan postId yang didapat
//         $postIds = collect($posts)->pluck('id');

//         // Lakukan permintaan ke JSONPlaceholder API untuk mengambil data komentar
//         $commentsResponse = Http::get('https://jsonplaceholder.typicode.com/comments', [
//             'postId' => $postIds->toArray()
//         ]);

//         // Periksa apakah permintaan berhasil
//         if ($commentsResponse->successful()) {
//             $comments = $commentsResponse->json();

//             // Kembalikan data komentar dalam format JSON
//             return Response()->json($comments);
//         } else {
//             return Response()->json(['error' => 'Failed to fetch comments'], $commentsResponse->status());
//         }
//     } else {
//         return Response()->json(['error' => 'Failed to fetch posts'], $postsResponse->status());
//     }
// });


// Route::get('/users/{userId}/photos', function ($userId) {
//     // Ambil semua postingan pengguna berdasarkan user_id
//     $albumsResponse = Http::get('https://jsonplaceholder.typicode.com/albums', [
//         'userId' => $userId
//     ]);
    
//     // Periksa apakah permintaan berhasil
//     if ($albumsResponse->successful()) {
//         $albums = $albumsResponse->json();

//         // Ambil semua komentar yang terkait dengan postId yang didapat
//         $postIds = collect($albums)->pluck('id');

//         // Lakukan permintaan ke JSONPlaceholder API untuk mengambil data komentar
//         $photosResponse = Http::get('https://jsonplaceholder.typicode.com/photos', [
//             'postId' => $postIds->toArray()
//         ]);

//         // Periksa apakah permintaan berhasil
//         if ($photosResponse->successful()) {
//             $photos = $photosResponse->json();

//             // Kembalikan data komentar dalam format JSON
//             return Response()->json($photos);
//         } else {
//             return Response()->json(['error' => 'Failed to fetch comments'], $photosResponse->status());
//         }
//     } else {
//         return Response()->json(['error' => 'Failed to fetch posts'], $albumsResponse->status());
//     }
// });





//Post api ke data enpoint 


Route::post("/posttt", function (Request $request) {
    $userId = $request->input('userId');
    $title = $request->input('title');
    $body = $request->input('body');

    $reqsponse = http::post('https://jsonplaceholder.typicode.com/posts', [
        'userId' => $userId,
        'title' => $title,
        'body' => $body,
    ]);

    if ($reqsponse->successful()) {
       return response()->json([
        'message' => "berhasil post data"
    ]); 
    }
    
});


//atau bisa juga anda menggunakan request->all



// Route::post('/posttt', function (Request $request) {
//     $post = $request->all();
//     $response = Http::post('https://jsonplaceholder.typicode.com/posts', $post);


//         return response()->json([
//             'message' => 'Berhasil post data',

//         ]);
// });






//Put Api ke endpoint

Route::put('/posttt/{id}', function (Request $request, $id) {
    $update = $request->all();

     $url = 'https://jsonplaceholder.typicode.com/posts/' . $id;


    $response = Http::put($url, $update);

    return response()->json([
        "message" => "berhasil update"
    ]);


});