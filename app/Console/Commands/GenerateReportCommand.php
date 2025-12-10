<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Project;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;

class GenerateReportCommand extends Command
{
    /**
     * Назва команди для виклику в терміналі (signature).
     */
    protected $signature = 'app:generate-report';

    /**
     * Опис команди.
     */
    protected $description = 'Generate a statistical report on project tasks';

    /**
     * Логіка виконання команди.
     */
    public function handle()
    {
        $this->info('Starting report generation...');

        //  Збираємо статистику
        $projects = Project::with('tasks')->get();
        $reportData = [];

        foreach ($projects as $project) {
            $stats = [
                'project_id' => $project->id,
                'project_name' => $project->name,
                'total_tasks' => $project->tasks->count(),
                'todo' => $project->tasks->where('status', 'todo')->count(),
                'in_progress' => $project->tasks->where('status', 'in_progress')->count(),
                'done' => $project->tasks->where('status', 'done')->count(),
            ];
            $reportData[] = $stats;
        }

        $jsonPayload = json_encode($reportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Зберігаємо в базу даних (таблиця reports)
        // Переконайтеся, що в моделі Report дозволено масове заповнення (protected $guarded = [];)
        Report::create([
            'period_start' => now()->subDay(), // Наприклад, за останню добу
            'period_end' => now(),
            'payload' => $reportData,
        ]);

        // Зберігаємо у файл
        $fileName = 'reports/report_' . date('Y-m-d_H-i-s') . '.json';
        Storage::put($fileName, $jsonPayload);

        $this->info("Report generated successfully!");
        $this->info("Saved to DB and file: storage/app/{$fileName}");
    }
}
