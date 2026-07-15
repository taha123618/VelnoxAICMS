<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Nwidart\Modules\Support\Stub;
// use Illuminate\Console\GeneratorCommand;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Nwidart\Modules\Support\Config\GenerateConfigReader;

class DataMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;
    
    protected $argumentName = 'name';

    protected $name = 'builder:make-data';

    protected $description = 'Create a new data class for the specified module.';

    
    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('data')->getPath() ?? config('modules.paths.app_folder').'Data';

        return $path.$filePath.'/'.$this->getDataName().'.php';
    }

    protected function getTemplateContents(): string
    {
        $module = $this->laravel['modules']->findOrFail($this->getModuleName());

        return (new Stub($this->getStubName(), [
            'CLASS_NAMESPACE' => $this->getClassNamespace($module),
            'CLASS' => $this->getClassNameWithoutNamespace(),
        ]))->render();
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the data class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['request', 'r', InputOption::VALUE_NONE, 'Generate a request data class', null],
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    protected function getDataName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getDataName());
    }

    public function getDefaultNamespace(): string
    {
        return config('modules.paths.generator.data.namespace', 'Data');
    }

    protected function getStubName(): string
    {
        return $this->option('request') === true ? '/data-request.stub' : '/data.stub';
    }
}
