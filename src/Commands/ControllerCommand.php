<?php

namespace Devlob\Generators\Commands;

use Illuminate\Console\Command;

class ControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'devlob:controller
                            {name : Entity name. Example "Test" without quotes.}
                            {nameSingularLower : Example test}
                            {namePlural : Example Tests}
                            {namePluralLower : Example tests}
                            {fields : Example fields="test:string"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create controller';
    
    public function fire()
    {
        $controller = $this->argument('namePlural') . 'Controller';
        
        $this->info('Installed controller (' . $controller . ')');

        file_put_contents(
            app_path('Http/Controllers/' . $controller . '.php'),
            $this->compileControllerStub()
        );
    }

    protected function compileControllerStub()
    {
        $fields = $this->argument('fields');

        // Create an array of fields
        $fieldsArray = explode(',', $fields);

        // Create key value pair fields

        $relationships = "with('";
        //$relationships = array();
        $counter = 0;
        
        $slug = '';

        foreach($fieldsArray as $k=>$v){
            $fieldsArray[$k] = str_replace(' ', '', explode(':', $v));

            if (strpos($v, '_id') == true){
                ($counter == 0) ? $relationships .= explode("_", $fieldsArray[$k][0])[0] . "'":
                    $relationships .= ", '" . explode("_", $fieldsArray[$k][0])[0] . "'";

                $counter++;
            }

            if($fieldsArray[$k][0] == 'slug')
                $slug = ' + [\'slug\' => str_slug($request->' . $this->argument('nameSingularLower') . ')]';
        }

        if($relationships == "with('")
            $relationships = "get()->sortByDesc('id')";
        else
            $relationships .= ")->get()->sortByDesc('id')";

        $replace = [
            '{{name}}' => $this->argument('name'),
            '{{nameSingularLower}}' => $this->argument('nameSingularLower'),
            '{{namePlural}}' => $this->argument('namePlural'),
            '{{namePluralLower}}' => $this->argument('namePluralLower'),
            '{{relationships}}' => $relationships,
            '{{slug}}' => $slug
        ];

        return str_replace(
            array_keys($replace), $replace, file_get_contents(__DIR__ . '/../Stubs/Controller.stub')
        );
    }
}
