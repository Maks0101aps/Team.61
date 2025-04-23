<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $account = Account::first() ?? Account::create(['name' => 'School Account']);
        $user = User::first() ?? User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password')
        ]);
        
        $eventsData = [
            [
                'title' => 'Батьківські збори 7-А класу',
                'start_date' => now()->addDays(7)->setHour(17)->setMinute(0),
                'duration' => 90, // 1.5 hours in minutes
                'content' => 'Обговорення успішності учнів та планування шкільних заходів на наступний семестр.',
                'location' => 'Кабінет 203',
                'type' => Event::TYPE_PARENT_MEETING,
                'is_content_hidden' => false,
            ],
            [
                'title' => 'Контрольна робота з математики',
                'start_date' => now()->addDays(14)->setHour(10)->setMinute(0),
                'duration' => 45, // 45 minutes
                'content' => 'Контрольна робота з алгебри для учнів 8-9 класів.',
                'location' => 'Кабінет 105',
                'type' => Event::TYPE_TEST,
                'is_content_hidden' => false,
            ],
            [
                'title' => 'День відкритих дверей',
                'start_date' => now()->addDays(21)->setHour(9)->setMinute(0),
                'duration' => 360, // 6 hours in minutes
                'content' => 'Запрошуємо батьків та майбутніх учнів познайомитися з нашою школою.',
                'location' => 'Школа 61',
                'type' => Event::TYPE_SCHOOL_EVENT,
                'is_content_hidden' => false,
            ],
            [
                'title' => 'Екзамен з історії',
                'start_date' => now()->addMonths(1)->setHour(9)->setMinute(0),
                'duration' => 120, // 2 hours in minutes
                'content' => 'Підсумковий екзамен з історії України для 11-х класів.',
                'location' => 'Актова зала',
                'type' => Event::TYPE_EXAM,
                'is_content_hidden' => false,
            ],
            [
                'title' => 'Концерт до Дня вчителя',
                'start_date' => now()->addMonth()->setHour(13)->setMinute(0),
                'duration' => 120, // 2 hours in minutes
                'content' => 'Святковий концерт, підготовлений учнями школи до Дня вчителя.',
                'location' => 'Актова зала',
                'type' => Event::TYPE_SCHOOL_EVENT,
                'is_content_hidden' => false,
            ],
        ];
        
        foreach ($eventsData as $data) {
            $event = Event::create([
                'account_id' => $account->id,
                'title' => $data['title'],
                'type' => $data['type'],
                'start_date' => $data['start_date'],
                'duration' => $data['duration'],
                'content' => $data['content'],
                'location' => $data['location'],
                'is_content_hidden' => $data['is_content_hidden'],
                'created_by' => $user->id,
            ]);
            
            echo "Created event: {$event->title}\n";
        }
    }
} 