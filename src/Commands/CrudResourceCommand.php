<?php

namespace Slugger\Crud\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudResourceCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:resource
                            {name : The name of the resource.}
                            {--table= : The name of the table.}
                            {--fillable= : The names of the fillable columns.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/resource.stub'
        : __DIR__ . '/stubs/resource.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Resources';
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

        $fillable = $this->option('fillable');

        return $this->replaceNamespace($stub, $name)
            ->replaceFillable($stub, $fillable)
            ->replaceClass($stub, $name);
    }



    /**
     * Replace the fillable for the given stub.
     *
     * @param  string  $stub
     * @param  string  $fillable
     *
     * @return $this
     */
    protected function replaceFillable(&$stub, $fillable)
    {
       $fillableRender = $this->arrayToString($fillable);
        $stub = str_replace(
            '{{fillable}}', $fillableRender, $stub
        );

        return $this;
    }


    /**
     *Convert a input array val to string
     *
     * @param  string  $toString
     *
     * @return string
     */


    protected function arrayToString($toString)
    {
       return implode(',',array_map(function ($value,$key)
       {
          return sprintf("%s",$value,'');
       },
        $toString,
        array_keys($toString)
        )
       );
    }

}
