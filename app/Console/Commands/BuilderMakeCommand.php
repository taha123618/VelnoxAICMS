<?php

namespace App\Console\Commands;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Nwidart\Modules\Commands\Make\GeneratorCommand;
use Nwidart\Modules\Exceptions\FileAlreadyExistException;
use Nwidart\Modules\Generators\FileGenerator;
use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Stub;
use Symfony\Component\Console\Input\InputArgument;

use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class BuilderMakeCommand extends GeneratorCommand implements PromptsForMissingInput
{
    protected $signature = 'builder:make {name} {category}';

    protected $description = 'Create a new builder element.';

    protected function promptForMissingArgumentsUsing(): array
    {
        return [
            'name' => fn () => text(
                label: 'Enter a name for the new Builder element',
                validate: ['name' => 'required|max:25|alpha_dash']
            ),
            'category' => fn () => select(
                label: 'Select an element category',
                options: [
                    'containers' => 'Container element',
                    'typography' => 'Typography element',
                    'forms' => 'Form element',
                    'media' => 'Media element',
                    'components' => 'Component',
                ]
            ),

        ];
    }

    public function handle(): int
    {

        $path = str_replace('\\', '/', $this->getDestinationFilePath());

        if (! $this->laravel['files']->isDirectory($dir = dirname($path))) {
            $this->laravel['files']->makeDirectory($dir, 0777, true);
        }

        $contents = $this->getTemplateContents();

        $configContent = $this->getConfigContents();
        $configPath = str_replace('\\', '/', $this->getConfigDestinationFilePath());

        $renderContent = $this->getRenderContents();
        $renderPath = str_replace('\\', '/', $this->getRenderDestinationFilePath());

        $settingsContent = $this->getSettingsContents();
        $settingsPath = str_replace('\\', '/', $this->getSettingsDestinationFilePath());

        try {
            $this->components->task("Generating file {$path}", function () use ($path, $contents, $configContent, $configPath, $renderContent, $renderPath, $settingsContent, $settingsPath) {
                $overwriteFile = $this->hasOption('force') ? $this->option('force') : false;
                (new FileGenerator($path, $contents))->withFileOverwrite($overwriteFile)->generate();

                (new FileGenerator($configPath, $configContent))->withFileOverwrite($overwriteFile)->generate();
                (new FileGenerator($renderPath, $renderContent))->withFileOverwrite($overwriteFile)->generate();
                (new FileGenerator($settingsPath, $settingsContent))->withFileOverwrite($overwriteFile)->generate();
            });
        } catch (FileAlreadyExistException $e) {
            $this->components->error("File : {$path} already exists.");

            return E_ERROR;
        }

        return 0;
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the element.'],
            ['type', InputArgument::REQUIRED, 'The type of element (element or component).'],
        ];
    }

    // ELEMENTS
    protected function getElementName(): array|string
    {
        return Str::studly($this->argument('name'));
    }

    protected function getElementCategory(): array|string
    {
        return Str::lower($this->argument('category'));
    }

    protected function getTemplateContents(): string
    {
        $elementName = $this->getElementName();

        return (new Stub($this->getElementStubName(), [
            'NAME' => Str::lower($elementName),
        ]))->render();
    }

    protected function getElementStubName(): string
    {
        return '/builder/element.stub';
    }

    protected function getBaseDraggablePath(): string
    {
        return GenerateConfigReader::read('draggables')->getPath().'/'.$this->getElementCategory();
    }

    public function getDestinationFilePath(): string
    {
        $elementName = Str::lower($this->getElementName());

        $path = $this->laravel['modules']->getModulePath('Builder');

        $filePath = $this->getBaseDraggablePath();

        return $path.$filePath.'/'.$elementName.'/'.$elementName.'.vue';
    }

    // CONFIG
    protected function getConfigStubName(): string
    {
        return '/builder/config.stub';
    }

    protected function getConfigContents(): string
    {
        $elementName = $this->getElementName();

        return (new Stub($this->getConfigStubName(), [
            'CATEGORY' => $this->getElementCategory(),
            'ID' => Str::lower($elementName),
            'NAME' => $elementName,
        ]))->render();
    }

    public function getConfigDestinationFilePath(): string
    {
        $elementName = Str::lower($this->getElementName());

        $path = $this->laravel['modules']->getModulePath('Builder');

        $filePath = $this->getBaseDraggablePath();

        return $path.$filePath.'/'.$elementName.'/config.ts';
    }

    // RENDER ELEMENT
    protected function getRenderStubName(): string
    {
        return '/builder/render.stub';
    }

    protected function getRenderContents(): string
    {
        $elementName = $this->getElementName();

        return (new Stub($this->getRenderStubName(), [
            'NAME' => Str::lower($elementName),
        ]))->render();
    }

    public function getRenderDestinationFilePath(): string
    {
        $elementName = Str::lower($this->getElementName());

        $path = $this->laravel['modules']->getModulePath('Builder');

        $filePath = $this->getBaseDraggablePath();

        return $path.$filePath.'/'.$elementName.'/render.vue';
    }

    // SETTINGS
    protected function getSettingsStubName(): string
    {
        return '/builder/settings.stub';
    }

    protected function getSettingsContents(): string
    {
        $elementName = $this->getElementName();

        return (new Stub($this->getSettingsStubName(), [
            'STUDLY_NAME' => $elementName,
            'NAME' => Str::lower($elementName),
            'CATEGORY' => $this->getElementCategory(),
        ]))->render();
    }

    public function getSettingsDestinationFilePath(): string
    {
        $elementName = Str::lower($this->getElementName());

        $path = $this->laravel['modules']->getModulePath('Builder');

        $filePath = $this->getBaseDraggablePath();

        return $path.$filePath.'/'.$elementName.'/settings.ts';
    }
}
