<?php

namespace Devlob\Generators\Commands;

use Illuminate\Console\Command;

class CrudCommand extends Command
{
    protected $name; //Test
    protected $nameSingularLower; //test
    protected $namePlural; //Tests
    protected $namePluralLower; //tests

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'devlob:devlob
                            {name : Entity name. Example "Test" without quotes.}
                            {--fields= : Example fields="test:string"}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will create -controller -model -request -routes -views -unit tests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Initialize all the variables
        $this->initializeVariables($this->argument('name'));

        // Get fields
        $fields = $this->option('fields');

        // Array of fields
        $fieldsArray = explode(',', str_replace(' ', '', $fields));

        $keysOnly = "";

        // Keys only
        foreach ($fieldsArray as $field) {
            // Last iteration
            if ($field === end($fieldsArray))
                $keysOnly .= '\'' . explode(':', $field)[0] . '\'';
            else
                $keysOnly .= '\'' . explode(':', $field)[0] . '\', ';
        }

        // Each command accepts different parameters (shit happens)
        $varsControllerAndView = [
            'name' => $this->name,
            'nameSingularLower' => $this->nameSingularLower,
            'namePlural' => $this->namePlural,
            'namePluralLower' => $this->namePluralLower,
            'fields' => $fields
        ];

        $varsUnittest = [
            'nameSingularLower' => $this->nameSingularLower,
            'namePlural' => $this->namePlural,
            'namePluralLower' => $this->namePluralLower
        ];

        $varsRequest = [
            'namePlural' => $this->namePlural,
            'namePluralLower' => $this->namePluralLower
        ];

        $varsModel = [
            'name' => $this->name,
            'fields' => $fieldsArray,
            'keysOnly' => $keysOnly
        ];
        
        if(config('devlob.include_routes') == true) $this->createRoute('resource');

        if(config('devlob.include_controller') == true) $this->call('devlob:controller', $varsControllerAndView, $fields);

        if(config('devlob.include_request') == true) $this->call('devlob:request', $varsRequest);

        if(config('devlob.include_model') == true) $this->call('devlob:model', $varsModel, $fields);

        if(config('devlob.include_unittesting') == true) $this->call('devlob:unittest', $varsUnittest);

        if(config('devlob.include_views') == true){
            // Create directories
            $this->createCrudDirectory();
            
            $this->call('devlob:view', $varsControllerAndView, $fields);
        }
        
        //$this->call('devlob:migration', $vars, $fields);
    }

    protected function initializeVariables($entityName)
    {
        $this->name = $entityName; //Test
        $this->nameSingularLower = strtolower($entityName); //test
        $this->namePlural = str_plural($entityName); //Tests
        $this->namePluralLower = strtolower(str_plural($entityName)); //tests
    }

    protected function createCrudDirectory()
    {
        $pathViews = DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . $this->namePluralLower . DIRECTORY_SEPARATOR;

        if (!is_dir(base_path('resources/views' . $pathViews)))
            mkdir(base_path('resources/views' . $pathViews), 0755, true);
    }

    protected function createRoute($type)
    {
        $route = app_path('Http/routes.php');
        $search = '';

        if ($type == 'resource') {
            # Admin resource routing
            $search = '# Admin resource controllers START';
            
            if(!empty(config('devlob.resource_search')))
                $search = config('devlob.resource_search');
                
            $insert = "Route::resource('" . $this->namePluralLower . "', '" . $this->namePlural . "Controller');";

            $replace = $search . "\n\t" . $insert;

            if (file_put_contents($route, str_replace($search, $replace, file_get_contents($route))))
                $this->info('Resource route was added.');
            else
                $this->info('Could not add resource route.');

            # Admin datatable
            $search = '# Admin datatables START';

            if(!empty(config('devlob.datatable_search')))
                $search = config('devlob.datatable_search');
            
            $insert = "Route::post('datatable" . $this->namePlural . "', ['as' => 'datatable" . $this->namePlural . "', 'uses' => '" . $this->namePlural . "Controller@datatable" . $this->namePlural . "']);";

            $replace = $search . "\n\t" . $insert;

            if (file_put_contents($route, str_replace($search, $replace, file_get_contents($route))))
                $this->info('Datatable route was added.');
            else
                $this->info('Could not add datatable route.');
        } else {
            $this->info('Only type resource is available at the moment.');
        }
    }
}
