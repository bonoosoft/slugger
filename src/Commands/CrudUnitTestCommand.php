<?php

namespace Slugger\Crud\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudUnitTestCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:test
                            {name : The name of the test .}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--view-path= : The name of the view path.}
                            {--required-fields= : Required fields for validations.}
                            {--route-group= : Prefix of the route group.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new unit test.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Test';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/test.stub'
        : __DIR__ . '/stubs/test.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return base_path('tests/Unit');
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());
        
        return $this->replaceClass($stub, $name);

          }


    /**
     * Replace the crudNameSingular for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudNameSingular
     *
     * @return $this
     */
    protected function replaceCrudNameSingular(&$stub, $crudNameSingular)
    {
        $stub = str_replace(
            '{{crudNameSingular}}', $crudNameSingular, $stub
        );

        return $this;
    }

}
