<?php

namespace Database\Seeders;

use App\TaskProviders\Bar;
use App\TaskProviders\Foo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            [
                'name' => 'foo',
                'url' => 'https://www.mocky.io/v2/5d47f235330000623fa3ebf7',
                'resolver' => Foo::class,
                'is_active' => 1,
            ],
            [
                'name' => 'bar',
                'url' => 'https://www.mocky.io/v2/5d47f24c330000623fa3ebfa',
                'resolver' => Bar::class,
                'is_active' => 1,
            ]
        ];

        foreach ($providers as $provider) {
            \App\Models\Provider::create($provider);
        }
    }
}
