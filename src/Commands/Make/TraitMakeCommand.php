<?php

namespace SequelONE\sOne\Modules\Commands\Make;

use Illuminate\Support\Str;
use SequelONE\sOne\Modules\Support\Config\GenerateConfigReader;
use SequelONE\sOne\Modules\Support\Stub;
use SequelONE\sOne\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TraitMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'module:make-trait';

    protected $description = 'Create a new trait class for the specified module.';

    public function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());

        $filePath = GenerateConfigReader::read('traits')->getPath() ?? config('modules.paths.app_folder').'Traits';

        return $path.$filePath.'/'.$this->getTraitName().'.php';
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
            ['name', InputArgument::REQUIRED, 'The name of the trait class.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'su.'],
        ];
    }

    protected function getTraitName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    private function getClassNameWithoutNamespace(): array|string
    {
        return class_basename($this->getTraitName());
    }

    public function getDefaultNamespace(): string
    {
        return config('modules.paths.generator.traits.namespace', 'Traits');
    }

    protected function getStubName(): string
    {
        return '/trait.stub';
    }
}
