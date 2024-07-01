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
            ['name' => 'MARIA BENITA',  'last_name' => 'CESPEDES GUIZADA',    'user_name' => 'MARIA',       'email' => '202103261@est.umss.edu'],                     /*ID 1*/                          
            ['name' => 'MAGDA LENA',    'last_name' => 'PEETERS ILONAA',      'user_name' => 'MAGDA',       'email' => 'xdalvarez.rojas.alexander.james5@gmail.com'], /*ID 2*/                
            ['name' => 'LIZBETH',       'last_name' => 'ARANIBAR',            'user_name' => 'LIZBETH',     'email' => 'bruteforcesolutionsbfs@gmail.com'],           /*ID 3*/        
            ['name' => 'AGUSTIN',       'last_name' => 'GUZMAN',              'user_name' => 'AGUSTIN',     'email' => 'sergio.garciacuchallo@gmail.com'],            /*ID 4*/
            ['name' => 'MARIBEL',       'last_name' => 'JAMIRA',              'user_name' => 'MARIBEL',     'email' => 'cwilliam14c@gmail.com'],                      /*ID 5*/
            ['name' => 'ALEJANDRO',     'last_name' => 'GARCIA',              'user_name' => 'ALEJANDRO',   'email' => 'erwin0pisis@gmail.com'],                      /*ID 6*/
            ['name' => 'ROBERTO',       'last_name' => 'VALENZUELA MIRANDA',  'user_name' => 'ROBERTO',     'email' => 'xdxdqtimpo1@gmail.com'],                      /*ID 7*/    
            ['name' => 'RENE',          'last_name' => 'MOREIRA CALIZAYA',    'user_name' => 'RENE',        'email' => 'veraemerson7019@gmail.com'],                  /*ID 8*/
            ['name' => 'JOSE FERNANDO', 'last_name' => 'PARISACA ALTAMIRADO', 'user_name' => 'JOSE',        'email' => 'xdxdjose.p@fcyt.umss.edu'],                   /*ID 9*/
            ['name' => 'LETICIA',       'last_name' => 'BLANCO COCA',         'user_name' => 'LETICIA',     'email' => 'xdxdleticia.b@fcyt.umss.edu'],                /*ID 10*/    
            ['name' => 'HERNAN',        'last_name' => 'USTARIZ VARGAS',      'user_name' => 'HERNAN',      'email' => 'xdxdhernan.u@fcyt.umss.edu'],                 /*ID 11*/    
            ['name' => 'HENRY FRANK',   'last_name' => 'VILLARROEL TAPIA',    'user_name' => 'HENRY',       'email' => 'xdxdhenry.v@fcyt.umss.edu'],                  /*ID 12*/
            ['name' => 'VLADIMIR ABEL', 'last_name' => 'COSTAS JAUREGUI',     'user_name' => 'VLADIMIR',    'email' => 'xdxdvladimir.c@fcyt.umss.edu'],               /*ID 13*/    
            ['name' => 'CARLA',         'last_name' => 'SALAZAR SERRUDO',     'user_name' => 'CARLA',       'email' => 'xdxdcarla.s@fcyt.umss.edu'],                  /*ID 14*/
            ['name' => 'ROSEMARY',      'last_name' => 'TORRICO BASCOPE',     'user_name' => 'ROSEMARY',    'email' => 'xdxdrosemary.t@fcyt.umss.edu'],               /*ID 15*/    
            ['name' => 'HELDER OCTAVIO','last_name' => 'FERNANDEZ GUZMAN',    'user_name' => 'HELDER',      'email' => 'xdxdhelder.f@fcyt.umss.edu'],                 /*ID 16*/    
            ['name' => 'SAMUEL',        'last_name' => 'ACHA PEREZ',          'user_name' => 'SAMUEL',      'email' => 'xdxdsamuel.a@fcyt.umss.edu'],                 /*ID 17*/    
            ['name' => 'CORINA',        'last_name' => 'FLORES VILLARROEL',   'user_name' => 'CORINA',      'email' => 'xdxdcorina.f@fcyt.umss.edu'],                 /*ID 18*/    
            ['name' => 'VICTOR HUGO',   'last_name' => 'MONTANO QUIROGA',     'user_name' => 'VICTOR',      'email' => 'xdxdvictor.m@fcyt.umss.edu'],                 /*ID 19*/
            ['name' => 'DEMETRIO',      'last_name' => 'JUCHANI BAZUALDO',    'user_name' => 'DEMETRIO',    'email' => 'xdxddemetrio.j@fcyt.umss.edu'],               /*ID 20*/    
            ['name' => 'YONY RICHARD',  'last_name' => 'MONTOYA BURGOS',      'user_name' => 'YONY',        'email' => 'xdxdyonyrichard.m@fcyt.umss.edu'],            /*ID 21*/        
            ['name' => 'TATIANA',       'last_name' => 'APARICIO YUJA',       'user_name' => 'TATIANA',     'email' => 'xdxdtatiana.a@fcyt.umss.edu'],                /*ID 22*/    
            ['name' => 'BORIS',         'last_name' => 'CALANCHA NAVIA',      'user_name' => 'BORIS',       'email' => 'xdxdboris.c@fcyt.umss.edu'],                  /*ID 23*/
            ['name' => 'VITTER JESUS',  'last_name' => 'MEDRANO PEREZ',       'user_name' => 'VITTER',      'email' => 'xdxdvitterjesus.m@fcyt.umss.edu'],            /*ID 24*/        
            ['name' => 'CARMEN ROSA',   'last_name' => 'GARCIA PEREZ',        'user_name' => 'CARMEN',      'email' => 'xdxdcarmenrosa.g@fcyt.umss.edu'],             /*ID 25*/            
            ['name' => 'DANIEL',        'last_name' => 'GARCIA CUCHALLO',     'user_name' => 'DANIEL',      'email' => 'qtimpo1@gmail.com'],                          /*ID 26*/
            ['name' => 'ALEXANDER JAMES','last_name' => 'ROJAS ALVAREZ',      'user_name' => 'ALEXANDER',   'email' => 'alvarez.rojas.alexander.james5@gmail.com'],   /*ID 27*/  
            ['name' => 'ENCARGADO3',    'last_name' => '',                    'user_name' => 'ENCARGADO3',  'email' => 'encargado3@gmail.com'],                       /*ID 28*/  
            ['name' => 'ENCARGADO4',    'last_name' => '',                    'user_name' => 'ENCARGADO4',  'email' => 'encargado4@gmail.com'],                       /*ID 29*/  
            ['name' => 'ENCARGADO5',    'last_name' => '',                    'user_name' => 'ENCARGADO5',  'email' => 'encargado5@gmail.com'],                       /*ID 30*/              
            ['name' => 'SISTEMA',       'last_name' => '',                    'user_name' => 'SISTEMA',     'email' => '']                                            /*ID 31*/                                      
        ];                  

        $password = '12345678';

        foreach ($people as $person) {
            Person::create([
                'name' => $person['name'],
                'last_name' => $person['last_name'],
                'user_name' => $person['user_name'],
                'email' => $person['email'],
                'password' => bcrypt($password)
            ]);
        }
    }
}
