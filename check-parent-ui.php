<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Функция для получения данных детей для родителя
function getChildrenData($parentId) {
    // Используем тот же запрос, что и в UsersController
    $children = DB::table('parent_student')
        ->join('contacts', 'parent_student.student_id', '=', 'contacts.id')
        ->where('parent_student.parent_model_id', $parentId)
        ->select('contacts.*')
        ->get();

    return $children->map(function($child) {
        return [
            'id' => $child->id,
            'name' => $child->first_name . ' ' . $child->last_name,
            'first_name' => $child->first_name,
            'last_name' => $child->last_name,
            'middle_name' => $child->middle_name,
            'class' => $child->class,
            'organization_id' => $child->organization_id,
            'email' => $child->email,
        ];
    })->toArray();
}

// Получим всех родителей
$parents = ParentModel::take(5)->get();

// Для каждого родителя проверим его детей по обоим методам
foreach ($parents as $parent) {
    echo "Родитель: {$parent->first_name} {$parent->last_name} (ID: {$parent->id})\n";
    
    // Получаем детей через связь
    $childrenViaRelation = $parent->children()->get();
    echo "  Через связь children(): " . $childrenViaRelation->count() . " детей\n";
    
    // Получаем детей через запрос как в контроллере
    $childrenViaQuery = getChildrenData($parent->id);
    echo "  Через прямой запрос: " . count($childrenViaQuery) . " детей\n";
    
    // Сравниваем результаты
    if ($childrenViaRelation->count() !== count($childrenViaQuery)) {
        echo "  ВНИМАНИЕ: Разное количество детей между методами!\n";
    }
    
    // Выводим данные о детях из SQL-запроса
    if (count($childrenViaQuery) > 0) {
        echo "  Данные о детях (из запроса):\n";
        foreach ($childrenViaQuery as $child) {
            echo "    - ID: {$child['id']}, Имя: {$child['name']}, Класс: {$child['class']}, org_id: {$child['organization_id']}\n";
        }
    }
    
    echo "\n";
}

// Проверка пользователей-родителей
$parentUsers = User::where('role', User::ROLE_PARENT)->take(5)->get();

echo "\nПроверка пользователей с ролью 'parent':\n";
foreach ($parentUsers as $user) {
    echo "Пользователь: {$user->first_name} {$user->last_name} (ID: {$user->id}, Email: {$user->email})\n";
    
    // Ищем родителя по email
    $parent = ParentModel::where('email', $user->email)->first();
    
    if ($parent) {
        echo "  Найден родитель: ID: {$parent->id}, Email: {$parent->email}\n";
        
        // Получаем детей для этого родителя
        $children = getChildrenData($parent->id);
        echo "  Количество детей: " . count($children) . "\n";
        
        if (count($children) > 0) {
            echo "  Данные о детях:\n";
            foreach ($children as $child) {
                echo "    - ID: {$child['id']}, Имя: {$child['name']}, Класс: {$child['class']}, org_id: {$child['organization_id']}\n";
            }
        }
    } else {
        echo "  ОШИБКА: Не найден соответствующий родитель для этого пользователя\n";
    }
    
    echo "\n";
} 