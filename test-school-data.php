<?php

use App\Models\User;
use App\Models\Account;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Ukrainian names data
$firstNamesMale = [
    'Олександр', 'Петро', 'Іван', 'Максим', 'Андрій', 'Богдан', 'Василь', 'Віталій', 
    'Владислав', 'Денис', 'Дмитро', 'Ігор', 'Михайло', 'Назар', 'Олег', 'Павло', 
    'Роман', 'Сергій', 'Степан', 'Тарас', 'Юрій'
];

$firstNamesFemale = [
    'Анастасія', 'Вікторія', 'Галина', 'Дарина', 'Ірина', 'Катерина', 'Марія', 
    'Наталія', 'Оксана', 'Олена', 'Софія', 'Тетяна', 'Христина', 'Юлія', 'Ярина',
    'Людмила', 'Світлана', 'Лариса', 'Надія', 'Валентина'
];

$middleNamesMale = [
    'Олександрович', 'Петрович', 'Іванович', 'Максимович', 'Андрійович', 'Богданович', 
    'Васильович', 'Віталійович', 'Владиславович', 'Денисович', 'Дмитрович', 'Ігорович', 
    'Михайлович', 'Назарович', 'Олегович', 'Павлович', 'Романович', 'Сергійович', 
    'Степанович', 'Тарасович', 'Юрійович'
];

$middleNamesFemale = [
    'Олександрівна', 'Петрівна', 'Іванівна', 'Максимівна', 'Андріївна', 'Богданівна', 
    'Василівна', 'Віталіївна', 'Владиславівна', 'Денисівна', 'Дмитрівна', 'Ігорівна', 
    'Михайлівна', 'Назарівна', 'Олегівна', 'Павлівна', 'Романівна', 'Сергіївна', 
    'Степанівна', 'Тарасівна', 'Юріївна'
];

$lastNames = [
    'Шевченко', 'Бондаренко', 'Коваленко', 'Бойко', 'Ткаченко', 'Кравченко', 'Ковальчук', 
    'Мельник', 'Шевчук', 'Поліщук', 'Бондар', 'Марченко', 'Ткачук', 'Петренко', 'Савченко',
    'Кузьменко', 'Іваненко', 'Левченко', 'Мороз', 'Федоренко', 'Руденко', 'Приходько',
    'Гончаренко', 'Литвиненко', 'Василенко', 'Хоменко', 'Мельниченко', 'Павленко',
    'Кравчук', 'Коваль'
];

$subjects = [
    'Українська мова та література', 'Математика', 'Фізика', 'Хімія', 
    'Біологія', 'Історія України', 'Географія', 'Англійська мова', 
    'Інформатика', 'Фізична культура', 'Мистецтво', 'Основи здоров\'я',
    'Німецька мова', 'Зарубіжна література', 'Правознавство', 'Астрономія',
    'Економіка', 'Захист України'
];

$qualifications = [
    'Спеціаліст вищої категорії', 'Спеціаліст першої категорії', 
    'Спеціаліст другої категорії', 'Спеціаліст', 'Вчитель-методист',
    'Старший вчитель'
];

$cities = [
    'Київ', 'Львів', 'Харків', 'Одеса', 'Дніпро', 'Запоріжжя', 'Вінниця', 
    'Полтава', 'Чернівці', 'Житомир', 'Суми', 'Хмельницький', 'Черкаси', 
    'Івано-Франківськ', 'Тернопіль', 'Рівне', 'Луцьк', 'Ужгород', 'Миколаїв'
];

$regions = [
    'Київська область', 'Львівська область', 'Харківська область', 'Одеська область', 
    'Дніпропетровська область', 'Запорізька область', 'Вінницька область', 
    'Полтавська область', 'Чернівецька область', 'Житомирська область', 
    'Сумська область', 'Хмельницька область', 'Черкаська область', 
    'Івано-Франківська область', 'Тернопільська область', 'Рівненська область', 
    'Волинська область', 'Закарпатська область', 'Миколаївська область',
    'м. Київ'
];

$streets = [
    'Шевченка', 'Франка', 'Лесі Українки', 'Богдана Хмельницького', 'Грушевського', 
    'Сковороди', 'Коцюбинського', 'Вишнева', 'Соборна', 'Садова', 'Миру', 'Центральна',
    'Незалежності', 'Героїв України', 'Шкільна', 'Молодіжна', 'Ярослава Мудрого',
    'Вокзальна', 'Польова', 'Лісова'
];

$classes = [
    '1-А', '1-Б', '2-А', '2-Б', '3-А', '3-Б', '4-А', '4-Б', '5-А', '5-Б',
    '6-А', '6-Б', '7-А', '7-Б', '8-А', '8-Б', '9-А', '9-Б', '10-А', '10-Б',
    '11-А', '11-Б'
];

// Event and task data
$eventTypes = [
    'exam',
    'test',
    'school_event',
    'parent_meeting',
    'personal'
];

$taskTypes = [
    'Домашнє завдання', 'Контрольна робота', 'Самостійна робота', 'Проєкт', 
    'Реферат', 'Тест', 'Диктант', 'Твір', 'Лабораторна робота', 
    'Презентація', 'Групова робота'
];

$taskStatuses = [
    'Нове', 'В процесі', 'Виконано', 'На перевірці', 'Перевірено', 'Потребує доопрацювання'
];

function getRandomPhone() {
    return '+380' . rand(100000000, 999999999);
}

function getRandomEmail($firstName, $lastName) {
    $domains = ['gmail.com', 'ukr.net', 'meta.ua', 'outlook.com', 'i.ua'];
    $translitFirstName = transliterate($firstName);
    $translitLastName = transliterate($lastName);
    return strtolower($translitFirstName . '.' . $translitLastName . rand(1, 99) . '@' . $domains[array_rand($domains)]);
}

function transliterate($string) {
    $transliteration = [
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'H', 'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Є' => 'Ye', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'Y', 'І' => 'I', 'Ї' => 'Yi', 'Й' => 'Y',
        'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts', 'Ч' => 'Ch',
        'Ш' => 'Sh', 'Щ' => 'Shch', 'Ь' => '', 'Ю' => 'Yu', 'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g', 'д' => 'd', 'е' => 'e',
        'є' => 'ye', 'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i', 'ї' => 'yi', 'й' => 'y',
        'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch',
        'ш' => 'sh', 'щ' => 'shch', 'ь' => '', 'ю' => 'yu', 'я' => 'ya', "'" => '', '`' => '',
        'ʼ' => '', 'ʹ' => '', '_' => '_', ' ' => '_'
    ];
    return str_replace(array_keys($transliteration), array_values($transliteration), $string);
}

// Find or create account
$account = Account::first();
if (!$account) {
    $account = new Account();
    $account->name = 'Тестова школа №61';
    $account->save();
    echo "Created new account with ID: {$account->id}\n";
} else {
    echo "Using existing account with ID: {$account->id}\n";
}

// 1. Create test teachers
$teacherCount = 10;
$teachers = [];
$teacherUsers = [];

echo "\nCreating $teacherCount teachers...\n";

for ($i = 0; $i < $teacherCount; $i++) {
    $isFemale = rand(0, 1) === 1;
    
    if ($isFemale) {
        $firstName = $firstNamesFemale[array_rand($firstNamesFemale)];
        $middleName = $middleNamesFemale[array_rand($middleNamesFemale)];
    } else {
        $firstName = $firstNamesMale[array_rand($firstNamesMale)];
        $middleName = $middleNamesMale[array_rand($middleNamesMale)];
    }
    
    $lastName = $lastNames[array_rand($lastNames)];
    $email = getRandomEmail($firstName, $lastName);
    $qualification = $qualifications[array_rand($qualifications)];
    $subject = $subjects[array_rand($subjects)];

    $city = $cities[array_rand($cities)];
    $region = $regions[array_rand($regions)];
    if ($city === 'Київ') {
        $region = 'м. Київ';
    }
    
    $street = $streets[array_rand($streets)];
    $houseNumber = rand(1, 150);
    $phone = getRandomPhone();
    
    // Create teacher record
    $teacher = Teacher::create([
        'account_id' => $account->id,
        'first_name' => $firstName,
        'middle_name' => $middleName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'qualification' => $qualification,
        'address' => "вул. $street, $houseNumber",
        'city' => $city,
        'region' => $region,
        'country' => 'UA',
        'postal_code' => (string)rand(10000, 99999),
    ]);
    
    $teachers[] = $teacher;
    
    // Create user for this teacher
    $user = User::create([
        'account_id' => $account->id,
        'first_name' => $firstName,
        'middle_name' => $middleName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => Hash::make('password'),
        'role' => User::ROLE_TEACHER,
    ]);
    
    $teacherUsers[] = $user;
    
    echo "Created teacher: {$firstName} {$middleName} {$lastName} ({$subject})\n";
}

// 2. Create test parents
$parentCount = 20;
$parents = [];
$parentUsers = [];

echo "\nCreating $parentCount parents...\n";

for ($i = 0; $i < $parentCount; $i++) {
    // Alternate between mothers and fathers
    $parentType = ($i % 2 === 0) ? ParentModel::TYPE_MOTHER : ParentModel::TYPE_FATHER;
    $isFemale = $parentType === ParentModel::TYPE_MOTHER;
    
    if ($isFemale) {
        $firstName = $firstNamesFemale[array_rand($firstNamesFemale)];
        $middleName = $middleNamesFemale[array_rand($middleNamesFemale)];
    } else {
        $firstName = $firstNamesMale[array_rand($firstNamesMale)];
        $middleName = $middleNamesMale[array_rand($middleNamesMale)];
    }
    
    $lastName = $lastNames[array_rand($lastNames)];
    $email = getRandomEmail($firstName, $lastName);
    
    $city = $cities[array_rand($cities)];
    $region = $regions[array_rand($regions)];
    if ($city === 'Київ') {
        $region = 'м. Київ';
    }
    
    $street = $streets[array_rand($streets)];
    $houseNumber = rand(1, 150);
    $phone = getRandomPhone();
    
    // Create parent record
    $parent = ParentModel::create([
        'account_id' => $account->id,
        'first_name' => $firstName,
        'middle_name' => $middleName,
        'last_name' => $lastName,
        'email' => $email,
        'parent_type' => $parentType,
        'phone' => $phone,
        'address' => "вул. $street, $houseNumber",
        'city' => $city,
        'region' => $region,
        'country' => 'UA',
        'postal_code' => (string)rand(10000, 99999),
        'street' => $street,
        'house_number' => (string)$houseNumber,
    ]);
    
    $parents[] = $parent;
    
    // Create user for this parent
    $user = User::create([
        'account_id' => $account->id,
        'first_name' => $firstName,
        'middle_name' => $middleName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => Hash::make('password'),
        'role' => User::ROLE_PARENT,
    ]);
    
    $parentUsers[] = $user;
    
    echo "Created parent: {$firstName} {$middleName} {$lastName} ({$parentType})\n";
}

// 3. Create test students and connect them to parents
$studentCount = 30;
$students = [];
$studentUsers = [];

echo "\nCreating $studentCount students and connecting them to parents...\n";

for ($i = 0; $i < $studentCount; $i++) {
    $isFemale = rand(0, 1) === 1;
    
    if ($isFemale) {
        $firstName = $firstNamesFemale[array_rand($firstNamesFemale)];
        $middleName = $middleNamesFemale[array_rand($middleNamesFemale)];
    } else {
        $firstName = $firstNamesMale[array_rand($firstNamesMale)];
        $middleName = $middleNamesMale[array_rand($middleNamesMale)];
    }
    
    // Select a parent to create a family connection
    $parentIndex = rand(0, count($parents) - 1);
    $parent = $parents[$parentIndex];
    
    // 50% chance to use the same last name as the parent
    if (rand(0, 1) === 1) {
        $lastName = $parent->last_name;
    } else {
        $lastName = $lastNames[array_rand($lastNames)];
    }
    
    $email = getRandomEmail($firstName, $lastName);
    
    // Use the same address as the parent
    $city = $parent->city;
    $region = $parent->region;
    $street = $parent->street;
    $houseNumber = $parent->house_number;
    $phone = getRandomPhone();
    $class = $classes[array_rand($classes)];
    
    // Create student record
    $student = Student::create([
        'account_id' => $account->id,
        'first_name' => $firstName,
        'middle_name' => $middleName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'address' => "вул. $street, $houseNumber",
        'city' => $city,
        'region' => $region,
        'country' => 'UA',
        'postal_code' => (string)rand(10000, 99999),
        'class' => $class,
        'organization_id' => $class,
    ]);
    
    $students[] = $student;
    
    // Create user for this student
    $user = User::create([
        'account_id' => $account->id,
        'first_name' => $firstName,
        'middle_name' => $middleName,
        'last_name' => $lastName,
        'email' => $email,
        'password' => Hash::make('password'),
        'role' => User::ROLE_STUDENT,
    ]);
    
    $studentUsers[] = $user;
    
    // Connect student to parent using the parent_student table directly
    // instead of using the relationship method which may not work correctly
    DB::table('parent_student')->insert([
        'parent_model_id' => $parent->id,
        'student_id' => $student->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    // 40% chance to have a second parent (to create full families)
    if (rand(1, 100) <= 40) {
        // Find a parent of the opposite gender
        $oppositeType = $parent->parent_type === ParentModel::TYPE_MOTHER ? 
                        ParentModel::TYPE_FATHER : ParentModel::TYPE_MOTHER;
        
        $possibleParents = array_filter($parents, function($p) use ($oppositeType, $parent, $lastName) {
            return $p->parent_type === $oppositeType && $p->id !== $parent->id && $p->last_name === $lastName;
        });
        
        // If no matching parent found with same last name, find any opposite gender
        if (empty($possibleParents)) {
            $possibleParents = array_filter($parents, function($p) use ($oppositeType, $parent) {
                return $p->parent_type === $oppositeType && $p->id !== $parent->id;
            });
        }
        
        if (!empty($possibleParents)) {
            $secondParent = $possibleParents[array_rand($possibleParents)];
            
            // Connect to second parent using the parent_student table directly
            DB::table('parent_student')->insert([
                'parent_model_id' => $secondParent->id,
                'student_id' => $student->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            echo "Connected student to second parent: {$secondParent->first_name} {$secondParent->last_name}\n";
        }
    }
    
    echo "Created student: {$firstName} {$middleName} {$lastName} (Class: {$class}), connected to parent: {$parent->first_name} {$parent->last_name}\n";
}

echo "\nScript completed successfully!\n";
echo "Created:\n";
echo "- $teacherCount teachers\n";
echo "- $parentCount parents\n";
echo "- $studentCount students\n";

// 4. Create test events
$eventCount = 30;
$events = [];

echo "\nCreating $eventCount events...\n";

// Event title templates based on type
$eventTitles = [
    'exam' => ['Екзамен з', 'Фінальний екзамен з', 'Підсумковий екзамен з', 'Тестування з'],
    'test' => ['Контрольна робота з', 'Тест з', 'Оцінювання з', 'Перевірка знань з'],
    'school_event' => ['Шкільний концерт', 'День відкритих дверей', 'Випускний вечір', 'Свято', 'Спортивні змагання з', 'Олімпіада з'],
    'parent_meeting' => ['Батьківські збори', 'Збори батьків', 'Зустріч з батьками', 'Консультація для батьків'],
    'personal' => ['Особиста зустріч', 'Консультація', 'Додаткове заняття з', 'Індивідуальне заняття з']
];

for ($i = 0; $i < $eventCount; $i++) {
    $eventType = $eventTypes[array_rand($eventTypes)];
    $titleTemplates = $eventTitles[$eventType];
    $titleBase = $titleTemplates[array_rand($titleTemplates)];
    
    // If title ends with 'з', add a subject
    if (substr($titleBase, -1) === 'з') {
        $subject = $subjects[array_rand($subjects)];
        $eventTitle = $titleBase . ' ' . $subject;
    } else {
        $eventTitle = $titleBase . ' ' . ($i + 1);
    }
    
    // Create random date within next 30 days
    $startDate = date('Y-m-d H:i:s', strtotime('+' . rand(1, 30) . ' days'));
    $duration = rand(1, 4) * 60; // Duration in minutes (1-4 hours)
    
    // 50% chance to assign to a specific class, otherwise school-wide
    $targetClass = null;
    
    // Create event
    $event = DB::table('events')->insert([
        'account_id' => $account->id,
        'title' => $eventTitle,
        'type' => $eventType,
        'content' => 'Опис події: ' . $eventTitle,
        'start_date' => $startDate,
        'duration' => $duration,
        'location' => 'Кабінет ' . rand(100, 305),
        'is_content_hidden' => false,
        'created_by' => $teacherUsers[array_rand($teacherUsers)]->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $eventId = DB::getPdo()->lastInsertId();
    $events[] = $eventId;
    
    // 50% chance to add class participants
    if (rand(0, 1) === 1) {
        $targetClass = $classes[array_rand($classes)];
        
        // Find students in this class
        $classStudents = array_filter($students, function($student) use ($targetClass) {
            return $student->class === $targetClass;
        });
        
        if (!empty($classStudents)) {
            foreach ($classStudents as $student) {
                DB::table('event_participants')->insert([
                    'event_id' => $eventId,
                    'participant_type' => 'App\\Models\\Student',
                    'participant_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    echo "Created event: {$eventTitle} starting {$startDate}" . ($targetClass ? " for class {$targetClass}" : " for entire school") . "\n";
}

// 5. Create test tasks
$taskCount = 50;
$tasks = [];

echo "\nCreating $taskCount tasks...\n";

for ($i = 0; $i < $taskCount; $i++) {
    $taskType = $taskTypes[array_rand($taskTypes)];
    $subject = $subjects[array_rand($subjects)];
    $targetClass = $classes[array_rand($classes)];
    $status = $taskStatuses[array_rand($taskStatuses)];
    
    $taskTitle = $taskType . ' з предмету "' . $subject . '"';
    
    // Create random date within -15 to +15 days range
    $dueDate = date('Y-m-d H:i:s', strtotime(rand(-15, 15) . ' days'));
    
    // Select a random teacher
    $teacher = $teachers[array_rand($teachers)];
    
    // Create task
    $task = DB::table('tasks')->insert([
        'account_id' => $account->id,
        'title' => $taskTitle,
        'content' => 'Завдання для класу ' . $targetClass . ': ' . $taskTitle,
        'due_date' => $dueDate,
        'completed' => false,
        'created_by' => $teacher->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    $taskId = DB::getPdo()->lastInsertId();
    $tasks[] = $taskId;
    
    // If task is assigned to individual students rather than class
    if (rand(0, 1) === 1) {
        // Find students in the target class
        $classStudents = array_filter($students, function($student) use ($targetClass) {
            return $student->class === $targetClass;
        });
        
        if (!empty($classStudents)) {
            // Assign to 1-5 random students from the class
            $assigneeCount = min(rand(1, 5), count($classStudents));
            $selectedStudentKeys = array_rand($classStudents, $assigneeCount);
            
            if (!is_array($selectedStudentKeys)) {
                $selectedStudentKeys = [$selectedStudentKeys];
            }
            
            foreach ($selectedStudentKeys as $studentKey) {
                $student = $classStudents[$studentKey];
                
                DB::table('task_participants')->insert([
                    'task_id' => $taskId,
                    'participant_type' => 'App\\Models\\Student',
                    'participant_id' => $student->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    echo "Created task: {$taskTitle} for class {$targetClass}, due {$dueDate}\n";
}

echo "\nScript completed successfully!\n";
echo "Created:\n";
echo "- $teacherCount teachers\n";
echo "- $parentCount parents\n";
echo "- $studentCount students\n";
echo "- $eventCount events\n";
echo "- $taskCount tasks\n";
echo "\nAll users have the password: 'password'\n"; 