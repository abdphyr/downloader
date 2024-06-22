<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class TrRm extends Command
{
    protected $signature = 'tr:rm {key} {--file=common}';
    protected $description = 'Remove key from translation';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        try {
            $key = $this->argument('key');
            $langs = Language::all();
            $success = false;
            foreach ($langs as $item) {
                $translations = $this->getLangFileData($item->code, $this->option('file'));
                if (isset($translations[$key])) {
                    $success = true;
                    unset($translations[$key]);
                }
                $this->setLangFileData($translations, $item->code, $this->option('file'));
            }
            if ($success) {
                $this->info("$key removed successfully to " . $this->option('file') . ".php");
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
