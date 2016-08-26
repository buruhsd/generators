<?php

namespace Devlob\Generators\Commands;

use Illuminate\Console\Command;

class RequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devlob:request
                            {namePlural : Example Tests}
                            {namePluralLower : Example tests}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create request';

    public function fire()
    {
        $request = $this->argument('namePlural') . 'Request';

        $this->info('Installed request (' . $request . ')');

        if (!file_exists(app_path('Http/Requests'))) {
            mkdir(app_path('Http/Requests'), 0777, true);
        }

        file_put_contents(
            app_path('Http/Requests/' . $request . '.php'),
            $this->compileControllerStub()
        );
    }

    protected function compileControllerStub()
    {
        $replace = [
            '{{namePlural}}' => $this->argument('namePlural'),
            '{{namePluralLower}}' => $this->argument('namePluralLower')
        ];

        return str_replace(array_keys($replace), $replace, file_get_contents(__DIR__ . '/../Stubs/Request.stub'));
    }
}
