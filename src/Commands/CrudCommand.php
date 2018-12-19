<?php

namespace Slugger\Crud\Commands;

use File;
use Illuminate\Console\Command;

class CrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate
                            {name : The name of the Crud.}
                            {--fields= : Fields name for the form & model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--pk=id : The name of the primary key.}
                            {--view-path= : The name of the view path.}
                            {--namespace= : Namespace of the controller.}
                            {--route-group= : Prefix of the route group.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud including controller, model ,resource,test & migrations.';

    /** @var string  */
    protected $routeName = '';

    /** @var string  */
    protected $controller = '';

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
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $modelName = str_singular($name);
        $migrationName = str_plural(strtolower($name));
        $tableName = $migrationName;
        $viewName = strtolower($name);


        $routeGroup = $this->option('route-group');
        $this->routeName = ($routeGroup) ? $routeGroup . '/' . strtolower($name) : strtolower($name);

        $controllerNamespace = ($this->option('namespace')) ? $this->option('namespace') . '\\' : '';

        $fields = $this->option('fields');
        $primaryKey = $this->option('pk');
        $viewPath = $this->option('view-path');

        $fieldsArray = explode(',', $fields);
        $requiredFields = '';
        $requiredFieldsStr = '';

        $tab =[];
        $tabResource = [];


        foreach ($fieldsArray as $item) {
            $fillableArray[] = preg_replace("/(.*?):(.*)/", "$1", trim($item));

            $itemArray = explode(':', $item);
            $currentField = trim($itemArray[0]);
            $requiredFieldsStr .= (isset($itemArray[2])
                && (trim($itemArray[2]) == 'req'
                    || trim($itemArray[2]) == 'required'))
            ? "'$currentField' => 'required', " : '';


            array_push($tabResource,"'".$currentField."'"."=>$"."this->".$currentField);


        }

        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";
        $requiredFields = ($requiredFieldsStr != '') ? "[" . $requiredFieldsStr . "]" : '';


        foreach ($tabResource as $key) {
          $key = (string)$key;
          array_push($tab,(string)$key);
        }


        $this->call('crud:controller', ['name' => $controllerNamespace . $name . 'Controller', '--crud-name' => $name, '--model-name' => $modelName, '--view-path' => $viewPath, '--required-fields' => $requiredFields, '--route-group' => $routeGroup]);
        $this->call('crud:model', ['name' => $modelName, '--fillable' => $fillable, '--table' => $tableName]);
        $this->call('crud:resource', ['name' => $modelName, '--fillable' => $tab, '--table' => $tableName]);
        $this->call('crud:migration', ['name' => $migrationName, '--schema' => $fields, '--pk' => $primaryKey]);
        $this->call('make:test', ['name' => $modelName]);

        // For optimizing the class loader
        $this->callSilent('optimize');

        // Updating the Http/routes.php file old laravels app_path('Http/routes.php')
        $routeFile = base_path('routes/api.php'); //change it to config file
        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $this->controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';

            if (\App::VERSION() >= '5.2') {
                $isAdded = File::append($routeFile,
                    " "
                    . "\n\t" . implode("\n\t", $this->addRoutes())
                    . " "
                );
            } else {
                $isAdded = File::append($routeFile, "\n".implode("\n", $this->addRoutes()));
            }

            if ($isAdded) {
                $this->info('API Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }

    protected function addRoutes() {
        return ["Route::apiResource('" . $this->routeName . "', '" . $this->controller . "');"];
    }
}
