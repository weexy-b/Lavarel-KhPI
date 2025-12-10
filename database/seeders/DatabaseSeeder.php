<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Створюємо одного конкретного користувача для тестування
        $mainUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Створюємо ще 10 випадкових користувачів
        $users = User::factory(10)->create();

        // Об'єднуємо всіх користувачів в одну колекцію для зручності
        $allUsers = $users->push($mainUser);

        // Створюємо 5 проєктів
        // Для кожного проєкту випадковим чином обираємо власника (owner_id) зі списку юзерів
        $projects = Project::factory(5)->recycle($allUsers)->create([
            'owner_id' => fn() => $allUsers->random()->id
        ]);

        //  Проходимося по кожному проєкту, щоб наповнити його даними
        foreach ($projects as $project) {

            // Додаємо учасників до проєкту (Many-to-Many через таблицю project_user)
            // Беремо 3 випадкових ID користувачів
            $randomMembers = $allUsers->random(3)->pluck('id');
            // Метод attach() записує дані у проміжну таблицю
            $project->members()->attach($randomMembers, ['role' => 'member']);

            // Створюємо задачі для цього проєкту
            $tasks = Task::factory(8)->create([
                'project_id' => $project->id,
                'author_id' => $allUsers->random()->id,   // Випадковий автор
                'assignee_id' => $allUsers->random()->id, // Випадковий виконавець
            ]);

            //  Для кожної задачі створюємо коментарі
            foreach ($tasks as $task) {
                Comment::factory(rand(1, 4))->create([
                    'task_id' => $task->id,
                    'author_id' => $allUsers->random()->id, // Випадковий коментатор
                ]);
            }
        }
    }
}
