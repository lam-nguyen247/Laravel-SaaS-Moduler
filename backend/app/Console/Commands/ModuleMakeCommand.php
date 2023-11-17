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
        $type = strtolower($this->argument('type'));
        $name = $this->argument('name');
        $folder =  ucfirst($type) . 's';

        // Xác định đường dẫn của module
        $modulePath = app_path('Modules/' . $moduleName);

        // Tạo thư mục Controllers nếu chưa tồn tại
        if (!is_dir($modulePath . '/' . $folder)) {
            mkdir($modulePath . '/' . $folder, 0755, true);
        }

        $this->call("make:$type", [
            'name' => "App\\Modules\\$moduleName\\$folder\\$name",
        ]);
    }
}
