<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TrAdd extends Command
{
    protected $signature = 'tr:add {key} {data} {--file=common}';
    protected $description = 'Helper to add lang words ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $key = $this->argument('key');
            $data = explode(',', $this->argument('data'));
            $success = false;
            foreach ($data as $item) {
                [$lang, $translation] = explode(':', $item);
                $translations = $this->getLangFileData($lang, $this->option('file'));
                if (!isset($translations[$key])) {
                    if (str_contains($translation, '_')) {
                        $translation = str_replace('_', ' ', $translation);
                    }
                    $translations[$key] = $translation;
                    $success = true;
                } else {
                    dd("$key already exist in " . "$lang/" . $this->option('file') . ".php");
                }
                $this->setLangFileData($translations, $lang, $this->option('file'));
            }
            if ($success) {
                $this->info("$key added successfully to " . $this->option('file') . ".php");
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
        }
    }

    private function getLangFileData($lang, $name)
    {
        $path = resource_path("lang/$lang/$name.php");
        if (file_exists($path)) {
            return require_once $path;
        } else {
            return [];
        }
    }
    private function setLangFileData($translations, $lang, $name)
    {
        $path = resource_path("lang/$lang/$name.php");
        if (file_exists($path)) {
            $data = (str_replace('array (', "<?php \n\nreturn [", var_export($translations, true)));
            $data = (str_replace(')', "];", $data));
            file_put_contents($path, $data);
        }
    }
}
