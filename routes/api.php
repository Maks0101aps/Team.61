use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserSearchController;
use App\Http\Controllers\Api\ParentTypeController;

Route::middleware('auth')->group(function () {
    Route::get('/users/search', [UserSearchController::class, 'search']);
});

// Retrieve parent type by user ID
Route::get('/parent-type/{user}', [ParentTypeController::class, 'getParentType']); 