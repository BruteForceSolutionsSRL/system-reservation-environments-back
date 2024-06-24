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
            ['name' => 'MARIA BENITA',  'last_name' => 'CESPEDES GUIZADA',    'email' => '202103261@est.umss.edu'],                     /*ID 1*/                          
            ['name' => 'MAGDA LENA',    'last_name' => 'PEETERS ILONAA',      'email' => 'alvarez.rojas.alexander.james5@gmail.com'],   /*ID 2*/                
            ['name' => 'LIZBETH',       'last_name' => 'ARANIBAR',            'email' => 'bruteforcesolutionsbfs@gmail.com'],           /*ID 3*/        
            ['name' => 'AGUSTIN',       'last_name' => 'GUZMAN',              'email' => 'sergio.garciacuchallo@gmail.com'],            /*ID 4*/
            ['name' => 'MARIBEL',       'last_name' => 'JAMIRA',              'email' => 'cwilliam14c@gmail.com'],                      /*ID 5*/
            ['name' => 'ALEJANDRO',     'last_name' => 'GARCIA',              'email' => 'erwin0pisis@gmail.com'],                      /*ID 6*/
            ['name' => 'ROBERTO',       'last_name' => 'VALENZUELA MIRANDA',  'email' => 'qtimpo1@gmail.com'],                          /*ID 7*/    
            ['name' => 'RENE',          'last_name' => 'MOREIRA CALIZAYA',    'email' => 'veraemerson7019@gmail.com'],                  /*ID 8*/
            ['name' => 'JOSE FERNANDO', 'last_name' => 'PARISACA ALTAMIRADO', 'email' => 'jose.p@fcyt.umss.edu'],                       /*ID 9*/
            ['name' => 'LETICIA',       'last_name' => 'BLANCO COCA',         'email' => 'leticia.b@fcyt.umss.edu'],                    /*ID 10*/    
            ['name' => 'HERNAN',        'last_name' => 'USTARIZ VARGAS',      'email' => 'hernan.u@fcyt.umss.edu'],                     /*ID 11*/    
            ['name' => 'HENRY FRANK',   'last_name' => 'VILLARROEL TAPIA',    'email' => 'henry.v@fcyt.umss.edu'],                      /*ID 12*/
            ['name' => 'VLADIMIR ABEL', 'last_name' => 'COSTAS JAUREGUI',     'email' => 'vladimir.c@fcyt.umss.edu'],                   /*ID 13*/    
            ['name' => 'CARLA',         'last_name' => 'SALAZAR SERRUDO',     'email' => 'carla.s@fcyt.umss.edu'],                      /*ID 14*/
            ['name' => 'ROSEMARY',      'last_name' => 'TORRICO BASCOPE',     'email' => 'rosemary.t@fcyt.umss.edu'],                   /*ID 15*/    
            ['name' => 'HELDER OCTAVIO','last_name' => 'FERNANDEZ GUZMAN',    'email' => 'helder.f@fcyt.umss.edu'],                     /*ID 16*/    
            ['name' => 'SAMUEL',        'last_name' => 'ACHA PEREZ',          'email' => 'samuel.a@fcyt.umss.edu'],                     /*ID 17*/    
            ['name' => 'CORINA',        'last_name' => 'FLORES VILLARROEL',   'email' => 'corina.f@fcyt.umss.edu'],                     /*ID 18*/    
            ['name' => 'VICTOR HUGO',   'last_name' => 'MONTANO QUIROGA',     'email' => 'victor.m@fcyt.umss.edu'],                     /*ID 19*/
            ['name' => 'DEMETRIO',      'last_name' => 'JUCHANI BAZUALDO',    'email' => 'demetrio.j@fcyt.umss.edu'],                   /*ID 20*/    
            ['name' => 'YONY RICHARD',  'last_name' => 'MONTOYA BURGOS',      'email' => 'yonyrichard.m@fcyt.umss.edu'],                /*ID 21*/        
            ['name' => 'TATIANA',       'last_name' => 'APARICIO YUJA',       'email' => 'tatiana.a@fcyt.umss.edu'],                    /*ID 22*/    
            ['name' => 'BORIS',         'last_name' => 'CALANCHA NAVIA',      'email' => 'boris.c@fcyt.umss.edu'],                      /*ID 23*/
            ['name' => 'VITTER JESUS',  'last_name' => 'MEDRANO PEREZ',       'email' => 'vitterjesus.m@fcyt.umss.edu'],                /*ID 24*/        
            ['name' => 'CARMEN ROSA',   'last_name' => 'GARCIA PEREZ',        'email' => 'carmenrosa.g@fcyt.umss.edu'],                 /*ID 25*/            
            ['name' => 'ENCARGADO',     'last_name' => '',                    'email' => 'encargado@gmail.com'],                        /*ID 26*/            
            ['name' => 'SISTEMA',       'last_name' => '',                    'email' => '']                                            /*ID 27*/                                      
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
