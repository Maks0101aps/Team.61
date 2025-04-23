<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

// Получим все записи из таблицы parent_student
$parentStudentRecords = DB::table('parent_student')->get();
echo "Всего записей в таблице parent_student: " . $parentStudentRecords->count() . "\n\n";

if ($parentStudentRecords->count() > 0) {
    echo "Пример записей:\n";
    foreach ($parentStudentRecords->take(5) as $record) {
        echo "ID: {$record->id}, Parent ID: {$record->parent_model_id}, Student ID: {$record->student_id}\n";
    }
    echo "\n";
}

// Проверим работу связи с родительской стороны
$parents = ParentModel::take(5)->get();
echo "Проверяем родителей и их детей:\n";
foreach ($parents as $parent) {
    $children = $parent->children()->get();
    echo "Родитель: {$parent->first_name} {$parent->last_name} (ID: {$parent->id}) имеет {$children->count()} детей.\n";
    
    if ($children->count() > 0) {
        foreach ($children as $child) {
            echo "  - Ребенок: {$child->first_name} {$child->last_name} (ID: {$child->id})\n";
        }
    }
}
echo "\n";

// Проверим работу связи со стороны студентов
$students = Student::take(5)->get();
echo "Проверяем студентов и их родителей:\n";
foreach ($students as $student) {
    $studentParents = $student->parents()->get();
    echo "Студент: {$student->first_name} {$student->last_name} (ID: {$student->id}) имеет {$studentParents->count()} родителей.\n";
    
    if ($studentParents->count() > 0) {
        foreach ($studentParents as $studentParent) {
            echo "  - Родитель: {$studentParent->first_name} {$studentParent->last_name} (ID: {$studentParent->id})\n";
        }
    }
}

// Проверим через SQL-запрос
echo "\nПрямая проверка через SQL:\n";
$sqlCheck = DB::select("
    SELECT 
        p.id as parent_id, 
        p.first_name as parent_first_name,
        p.last_name as parent_last_name,
        s.id as student_id,
        s.first_name as student_first_name,
        s.last_name as student_last_name
    FROM parent_models p
    JOIN parent_student ps ON p.id = ps.parent_model_id
    JOIN contacts s ON s.id = ps.student_id
    LIMIT 5
");

if (count($sqlCheck) > 0) {
    foreach ($sqlCheck as $record) {
        echo "Родитель: {$record->parent_first_name} {$record->parent_last_name} (ID: {$record->parent_id}) - ";
        echo "Студент: {$record->student_first_name} {$record->student_last_name} (ID: {$record->student_id})\n";
    }
} else {
    echo "SQL-запрос не вернул результатов\n";
} 