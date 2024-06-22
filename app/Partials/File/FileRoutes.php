<?php


namespace App\Partials\File;

use Illuminate\Support\Facades\Route;

class FileRoutes extends Route
{
    public static function filesResource(string $name, string $controller)
    {
        self::get("$name-file/{fileable}", [$controller, "listFilection"])->name("$name.file.index");
        self::get("$name-file/{fileable}/{file}", [$controller, "downloadFileAction"])->name("$name.file.download");
        self::post("$name-file/{fileable}", [$controller, "createFilection"])->name("$name.file.create");
        self::post("$name-file/{fileable}/{file}", [$controller, "updateFilection"])->name("$name.file.update");
        self::delete("$name-file/{fileable}/{file}", [$controller, "deleteFilection"])->name("$name.file.delete");
    }
}