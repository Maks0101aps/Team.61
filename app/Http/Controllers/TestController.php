<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function testPasswordChange()
    {
        // Найдем всех студентов
        $students = User::where('role', User::ROLE_STUDENT)->get();
        
        // Вернем информацию о студентах и их флагах password_change_required
        $data = [];
        foreach ($students as $student) {
            $data[] = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'password_change_required' => $student->password_change_required,
            ];
        }
        
        // Проверим также наличие студентов, которым нужно сменить пароль
        $needChange = User::where('role', User::ROLE_STUDENT)
            ->where('password_change_required', true)
            ->count();
            
        // Проверим структуру таблицы users
        $columns = DB::getSchemaBuilder()->getColumnListing('users');
        
        return response()->json([
            'students' => $data,
            'total_students' => count($students),
            'students_need_change' => $needChange,
            'has_password_change_field' => in_array('password_change_required', $columns),
            'columns' => $columns
        ]);
    }
    
    public function setPasswordChangeFlag($studentId)
    {
        // Найдем студента
        $student = User::where('role', User::ROLE_STUDENT)->find($studentId);
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Студент не найден'
            ]);
        }
        
        // Установим флаг password_change_required
        $student->password_change_required = true;
        $student->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Флаг password_change_required установлен для студента ' . $student->name,
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'password_change_required' => $student->password_change_required
            ]
        ]);
    }
}
