use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserSearchController;

Route::middleware('auth')->group(function () {
    Route::get('/users/search', [UserSearchController::class, 'search']);
}); 