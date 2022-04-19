<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeCacheRepositoryCommand extends Command
{
    const REPOSITORIES_INTERFACE_PATH = __DIR__ . '/../../Repositories';
    const REPOSITORIES_ELOQUENT_PATH = __DIR__ . '/../../Repositories/EloquentRepositories';
    const REPOSITORIES_CACHE_PATH = __DIR__ . '/../../Cache';
    const MODEL_PATH = __DIR__ . '/Repository/Models';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Repository';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('model');

        //Create Interface Repository
        $this->createFile($name, self::MODEL_PATH . '/ModelRepositoryInterface.stub', self::REPOSITORIES_INTERFACE_PATH . "/$name" . "RepositoryInterface.php", 'Cache Repository');
        //Create Eloquent Repository
        $this->createFile($name, self::MODEL_PATH . '/EloquentModelRepository.stub', self::REPOSITORIES_ELOQUENT_PATH . "/$name" . "Repository.php", 'Eloquent Repository');
        //Create Cache Repository
        $this->createFile($name, self::MODEL_PATH . '/ModelCache.stub', self::REPOSITORIES_CACHE_PATH . "/$name" . "Cache.php", 'Repository Interface');
        //Comment code to Controller
        $this->commentCodeForController($name, self::MODEL_PATH . '/ControllerModelRepository.stub');
    }

    public function createFile($name, $model_path, $objetive_path, $file_type)
    {
        //Open model File
        $gestorModel = fopen($model_path, 'r');
        if ($gestorModel) {
            if (!file_exists($objetive_path)) {
                //Open Objetive Repository
                $gestorNewRepository = fopen($objetive_path, 'w');

                $this->replaceNameModelForFile($gestorModel, $name, function ($line_replaced) use ($gestorNewRepository) {
                    fwrite($gestorNewRepository, $line_replaced);
                });

                fclose($gestorNewRepository);

                $this->info("$file_type created succesfully");
            } else {
                $this->comment("$file_type Already Exists");
            }
            fclose($gestorModel);
        } else {
            $this->error('Error opening Model Repository');
        }
    }

    public function commentCodeForController($name, $model_path)
    {
        $gestorModel = fopen($model_path, 'r');
        if ($gestorModel) {
            $code = "";
            $this->replaceNameModelForFile($gestorModel, $name, function ($line_replaced) use (&$code) {
                $code .= $line_replaced;
            });
            fclose($gestorModel);
            $this->comment('Copy this code in your controller');
            $this->comment($code);
        } else {
            $this->error('Error opening Model Repository');
        }
    }

    public function replaceNameModelForFile($gestorModel, $name, $closure)
    {
        //Write Objetive Repository with Model replacing NameModel
        while ($line = fgets($gestorModel)) {
            $line_replaced = str_replace("NameModel", ucfirst($name), $line);
            $line_replaced = str_replace("name_model", lcfirst($name), $line_replaced);
            $closure($line_replaced);
        }
    }
}
