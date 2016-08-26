<?php

namespace Devlob\Generators\Commands;

use Illuminate\Console\Command;

class UnitTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devlob:unittest
                            {nameSingularLower : Example test}
                            {namePlural : Example Tests}
                            {namePluralLower : Example tests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Unit Test';

    public function fire()
    {
        $unitTest = $this->argument('namePlural') . 'Test';

        $this->info('Installed unit test (' . $unitTest . ')');

        file_put_contents(
            base_path('tests/' . $unitTest . '.php'),
            $this->compileControllerStub()
        );
    }

    protected function compileControllerStub()
    {
        $replace = [
            '{{nameSingularLower}}' => $this->argument('nameSingularLower'),
            '{{namePlural}}' => $this->argument('namePlural'),
            '{{namePluralLower}}' => $this->argument('namePluralLower')
        ];

        return str_replace(array_keys($replace), $replace, file_get_contents(__DIR__ . '/../Stubs/UnitTesting.stub'));
    }

    protected function getStub()
    {
        return __DIR__ . '/../Stubs/UnitTesting.stub';
    }
}
