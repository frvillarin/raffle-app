<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Tightenco\Ziggy\CommandRouteGenerator;
use Tightenco\Ziggy\Ziggy;

class CustomZiggyGenerate extends CommandRouteGenerator
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ziggy:generate-custom {path=./resources/js/ziggy.js} {--url=} {--group=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom Ziggy Generate';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = $this->argument('path');
        $generatedRoutes = $this->generate($this->option('group'));

        $this->makeDirectory($path);
        $this->files->put(base_path($path), $generatedRoutes);

        $this->info('File generated!');
    }

    private function generate($group = false)
    {
        $payload = (new Ziggy($group, $this->option('url') ? url($this->option('url')) : null))->toJson();
        $base64_payload = base64_encode($payload);

        return <<<JAVASCRIPT
let Ziggy = '{$base64_payload}';
let decodedZiggy = null;

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    decodedZiggy = JSON.parse(atob(Ziggy));
    for (let name in window.Ziggy.routes) {
        decodedZiggy.routes[name] = window.Ziggy.routes[name];
    }
    Ziggy = btoa(JSON.stringify(decodedZiggy));
}

export { Ziggy };

JAVASCRIPT;
    }
}
