<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {

        /*Este hack permite utilizar la clase Test_Case dentro de Matura. Realmente, se
        esta bootstrapenado la app dos veces al lanzar los tests, y no estoy seguro de que esto no rompa partes de Test_Case
        que no estoy usando.

        Realmente lo suyo sería crear un objeto que pueda usar el trait MakesHttpRequests y no marear con Test_Case, pero
        ese trait tiene ciertas precondiciones que Test_Case parece cumplir con este pequeño cambio.*/
        $this->app = require __DIR__.'/../bootstrap/app.php';

        $this->app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();


        /*
            $app = require __DIR__.'/../bootstrap/app.php';

            $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
         */


        return $app;
    }
}
