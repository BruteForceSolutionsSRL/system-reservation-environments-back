<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\{
    Person,
    User
};

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $people = [
            ['name' => 'MARIA BENITA',  'last_name' => 'CESPEDES GUIZADA',    'email' => '202103261@est.umss.edu'],
            ['name' => 'MAGDA LENA',    'last_name' => 'PEETERS ILONAA',      'email' => 'alvarez.rojas.alexander.james5@gmail.com'], 
            ['name' => 'LIZBETH',       'last_name' => 'ARANIBAR',            'email' => 'bruteforcesolutionsbfs@gmail.com'],
            ['name' => 'AGUSTIN',       'last_name' => 'GUZMAN',              'email' => 'xdxdagustin.g@gmail.com'], 
            ['name' => 'MARIBEL',       'last_name' => 'JAMIRA',              'email' => 'xdxdmaribel.j@gmail.com'], 
            ['name' => 'ALEJANDRO',     'last_name' => 'GARCIA',              'email' => 'xdxdalejandro.g@gmail.com'],
            ['name' => 'ROBERTO',       'last_name' => 'VALENZUELA MIRANDA',  'email' => 'xdxdroberto.v@fcyt.umss.edu'], 
            ['name' => 'RENE',          'last_name' => 'MOREIRA CALIZAYA',    'email' => 'xdxdrene.m@fcyt.umss.edu'], 
            ['name' => 'JOSE FERNANDO', 'last_name' => 'PARISACA ALTAMIRADO', 'email' => 'xdxdjose.p@fcyt.umss.edu'], 
            ['name' => 'LETICIA',       'last_name' => 'BLANCO COCA',         'email' => 'xdxdleticia.b@fcyt.umss.edu'], 
            ['name' => 'HERNAN',        'last_name' => 'USTARIZ VARGAS',      'email' => 'xdxdhernan.u@fcyt.umss.edu'], 
            ['name' => 'HENRY FRANK',   'last_name' => 'VILLARROEL TAPIA',    'email' => 'xdxdhenry.v@fcyt.umss.edu'], 
            ['name' => 'VLADIMIR ABEL', 'last_name' => 'COSTAS JAUREGUI',     'email' => 'xdxdvladimir.c@fcyt.umss.edu'], 
            ['name' => 'CARLA',         'last_name' => 'SALAZAR SERRUDO',     'email' => 'xdxdcarla.s@fcyt.umss.edu'], 
            ['name' => 'ROSEMARY',      'last_name' => 'TORRICO BASCOPE',     'email' => 'xdxdrosemary.t@fcyt.umss.edu'], 
            ['name' => 'HELDER OCTAVIO','last_name' => 'FERNANDEZ GUZMAN',    'email' => 'xdxdhelder.f@fcyt.umss.edu'], 
            ['name' => 'SAMUEL',        'last_name' => 'ACHA PEREZ',          'email' => 'xdxdsamuel.a@fcyt.umss.edu'], 
            ['name' => 'CORINA',        'last_name' => 'FLORES VILLARROEL',   'email' => 'xdxdcorina.f@fcyt.umss.edu'], 
            ['name' => 'VICTOR HUGO',   'last_name' => 'MONTANO QUIROGA',     'email' => 'xdxdvictor.m@fcyt.umss.edu'],
            ['name' => 'ENCARGADO',     'last_name' => '',                    'email' => 'encargado@gmail.com'],
            ['name' => 'SISTEMA',       'last_name' => '',                    'email' => '']
        ];

        $password = '12345678';

        foreach ($people as $person) {
            $personAct = Person::create($person);
            User::create([
                'person_id' => $personAct->id,
                'email' => $personAct['email'],
                'password' => bcrypt($password)
            ]);
        }
    }
}
