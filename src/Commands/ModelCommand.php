<?php

namespace Devlob\Generators\Commands;

use Illuminate\Console\Command;

class ModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devlob:model
                            {name : Entity name. Example "Test" without quotes.}
                            {fields : Fields example fields="test:string"}
                            {keysOnly}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create model';

    public function fire()
    {
        $model = $this->argument('name');

        $this->info('Installed model (' . $model . ')');

        file_put_contents(
            app_path($model . '.php'),
            $this->compileControllerStub()
        );
    }

    protected function compileControllerStub()
    {
        $getRouteKeyName = '';
        
        $relationships = '';
        
        foreach($this->argument('fields') as $field){
            $key = explode(':', $field)[0];
            
\Log::info('fs', ['f' => $key]);
            
            if(preg_match('/_id/', $key)){
                $withoutId = str_replace('_id', '', $key);
                
                $relationships .= 'public function ' . $withoutId . '(){return $this->belongsTo(\'App\\' . ucwords($withoutId) . '\');}';
            }
        }
        
        if(preg_match('\'slug\'', $this->argument('keysOnly')))
            if ($this->confirm('Are you using "slug" instead of "id"? This will change the route key name from "id" to "slug" for this model only. [y|n]'))
                $getRouteKeyName = PHP_EOL . PHP_EOL . 'public function getRouteKeyName() {' . PHP_EOL . 'return \'slug\';' . PHP_EOL . '}';

        $replace = [
            '{{name}}' => $this->argument('name'),
            '{{keysOnly}}' => $this->argument('keysOnly'),
            '{{getRouteKeyName}}' => $getRouteKeyName,
            '{{relationships}}' => $relationships
        ];

        return str_replace(array_keys($replace), $replace, file_get_contents(__DIR__ . '/../Stubs/Model.stub'));
    }
}
