<?php

use GuzzleHttp\Psr7\Request;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([UserSeeder::class, ActionSeeder::class, CategorySeeder::class]);
        $adapter = GuzzleAdapter::createWithConfig([]);
        $request = new Request('GET', config('app.url') . '/api/import');
        $adapter->sendRequest($request);
    }

}
