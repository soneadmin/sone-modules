<?php

namespace SequelONE\sOne\Modules\Commands\Make;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Str;
use SequelONE\sOne\Modules\Support\Config\GenerateConfigReader;
use SequelONE\sOne\Modules\Support\Stub;
use SequelONE\sOne\Modules\Traits\ModuleCommandTrait;
use Symfony\Component\Console\Input\InputArgument;

class ViewMakeCommand extends GeneratorCommand
{
    use ModuleCommandTrait;

    protected $argumentName = 'name';

    protected $name = 'module:make-view';

    protected $description = 'Create a new view for the specified module.';

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the view.'],
            ['module', InputArgument::OPTIONAL, 'The name of module will be used.'],
        ];
    }

    protected function getTemplateContents(): string
    {
        return (new Stub('/view.stub', ['QUOTE' => Inspiring::quotes()->random()]))->render();
    }

    protected function getDestinationFilePath(): string
    {
        $path = $this->laravel['modules']->getModulePath($this->getModuleName());
        $factoryPath = GenerateConfigReader::read('views');

        return $path.$factoryPath->getPath().'/'.$this->getFileName();
    }

    private function getFileName(): string
    {
        return Str::lower($this->argument('name')).'.blade.php';
    }
}
