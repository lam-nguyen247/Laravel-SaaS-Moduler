<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make {moduleName} {type} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a controller or model for a module';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $moduleName = $this->argument('moduleName');
        $type = $this->argument('type');
        $name = $this->argument('name');

        // Xác định đường dẫn của module
        $modulePath = app_path('Modules/' . $moduleName);

        // Tạo thư mục Controllers nếu chưa tồn tại
        if (!is_dir($modulePath . '/Controllers')) {
            mkdir($modulePath . '/Controllers', 0755, true);
        }

        // Tạo thư mục Models nếu chưa tồn tại
        if (!is_dir($modulePath . '/Models')) {
            mkdir($modulePath . '/Models', 0755, true);
        }

        // Tạo thư mục Routes nếu chưa tồn tại
        if (!is_dir($modulePath . '/Routes')) {
            mkdir($modulePath . '/Routes', 0755, true);
        }

         // Tạo thư mục Services nếu chưa tồn tại
         if (!is_dir($modulePath . '/Services')) {
            mkdir($modulePath . '/Services', 0755, true);
        }

         // Tạo thư mục Repositories nếu chưa tồn tại
         if (!is_dir($modulePath . '/Repositories')) {
            mkdir($modulePath . '/Repositories', 0755, true);
        }

        // Tùy thuộc vào type, tạo controller hoặc model
        if ($type === 'controller') {
            $this->call('make:controller', [
                'name' => "App\\Modules\\$moduleName\\Controllers\\$name",
            ]);
        } elseif ($type === 'model') {
            $this->call('make:model', [
                'name' => "Modules\\$moduleName\\Models\\$name",
            ]);
        }
    }
}
