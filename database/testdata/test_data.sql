/*
    LAST MODIFICATION: 11/07/2024 15:54
    VERSION: 
        2.0 (RESERVAS ESPECIALES + GESTION ACADEMICA)
*/
use test3;
/*
    roles
*/
insert into roles(name, created_at, updated_at) values 
('ENCARGADO', NOW(), NOW()),		/*ID 1*/
('DOCENTE', NOW(), NOW())			/*ID 2*/
;			
/*
    permissions
	request_reserve => solicitud de reserva o hacer una reserva (DOCENTE - ENCARGADO) 
	reservation_handling => Atender solicitudes de reserva (ENCARGADO)
	notify => Notificaciones (ENCARGADO)
	report => Generar reportes de uso de ambiente (ENCARGADO) 
    block_register => Registro de nuesvos bloques (ENCARGADO)
    block_update => Edicion de bloques (ENCARGADO)
    block_remove => Eliminar un bloque (ENCARGADO)
    environment_register => Registrar ambiente (ENCARGADO)
    environment_update => Editar ambiente (ENCARGADO)
    environment_remove => Eliminar ambiente (ENCARGADO)
    reservation_cancel => Cancelar solicitues de reserva (DOCENTE)
    history => Obtener el historia de solicitudes (DOCENTE)
    special_reservation => Realizar una reserva especial (ENCARGADO)
 */
insert into permissions(name, created_at, updated_at) values
('request_reserve', NOW(), NOW()),			/*ID 1*/
('reservation_handling', NOW(), NOW()),		/*ID 2*/
('notify', NOW(), NOW()),					/*ID 3*/
('report', NOW(), NOW()),					/*ID 4*/
('block_register', NOW(), NOW()),			/*ID 5*/
('block_update', NOW(), NOW()),				/*ID 6*/
('block_remove', NOW(), NOW()),				/*ID 7*/
('environment_register', NOW(), NOW()),		/*ID 8*/
('environment_update', NOW(), NOW()),		/*ID 9*/
('environment_remove', NOW(), NOW()),		/*ID 10*/
('reservation_cancel', NOW(), NOW()),		/*ID 11*/
('history', NOW(), NOW()),		            /*ID 12*/
('special_reservation', NOW(), NOW())		/*ID 13*/
;			
/*
    role_permission
    ENCARGADO 	-> ID 1
    DOCENTE 	-> ID 2
*/
insert into role_permission(role_id, permission_id, created_at, updated_at) values
(1,2,NOW(), NOW()),
(1,3,NOW(), NOW()),
(1,4,NOW(), NOW()),
(1,5,NOW(), NOW()),
(1,6,NOW(), NOW()),
(1,7,NOW(), NOW()),
(1,8,NOW(), NOW()),
(1,9,NOW(), NOW()),
(1,10,NOW(), NOW()),
(1,13,NOW(), NOW()),
(1,1,NOW(), NOW()),
(2,1,NOW(), NOW()),
(2,11,NOW(), NOW()),
(2,12,NOW(), NOW())
;
/*
    some roles: 
*/
insert into person_role(person_id, role_id) values
    (1, 2), 
    (2, 2), 
    (3, 2), 
    (4, 2), 
    (5, 2), 
    (6, 2), 
    (7, 2), 
    (8, 2), 
    (9, 2), 
    (10, 2),
    (11, 2), 
    (12, 2), 
    (13, 2), 
    (14, 2), 
    (15, 2),
    (16, 2),
    (17, 2),
    (18, 2),
    (19, 2),
    (20, 2),
    (21, 2),
    (22, 2),
    (23, 2),
    (24, 2),
    (25, 2),
    (26, 2),
    (27, 2),
    (28, 2),
    (29, 2),
    (30, 2),
    (31, 2),
    (32, 2),
    (33, 2),
    (34, 2),
    (35, 2),
    (36, 2),
    (37, 2),
    (38, 2),
    (39, 2),
    (40, 2),
    (41, 2),
    (42, 2),
    (43, 2),
    (44, 2),
    (45, 2),
    (46, 2),
    (47, 2),
    (48, 2),
    (49, 2),
    (50, 2),
    (51, 2),
    (52, 2),
    (53, 1),
    (54, 1),
    (55, 1),
    (56, 1),
    (57, 1),
    (58, 1),
    (59, 1)
;
/*
    notification_types
*/
insert into notification_types(description) values 
('INFORMATIVO'),
('ACEPTADA'),
('RECHAZADA'), 
('CANCELADA'),
('ADVERTENCIA')
;
/*
    departments
*/
insert into departments(name) 
value 
    ('DEPARTAMENTO DE INFORMATICA - SISTEMAS')
;
/**
    academic_periods
*/
insert into academic_periods(name, initial_date, end_date, activated) values
    ('I-2024', '2024-04-01', '2024-09-01', 1) /*ID 1 - I-2024*/
;
/*
    careers
*/
insert into   study_plans (academic_period_id, name, created_at, updated_at) 
values 
    (1, 'LICENCIATURA EN INGENIERIA INFORMATICA', NOW(), NOW()), 
    (1, 'LICENCIATURA EN INGENIERIA DE SISTEMAS', NOW(), NOW()), 
    (1, 'LICENCIATURA EN ELECTROMECANICA'       , NOW(), NOW())
;
/*
    university_subjects
*/
insert into university_subjects (department_id, id, name, created_at)
values 
    (1, 1803001, 'INGLES I',                                            NOW()), /*ID 1 */  
    (1, 2006063, 'FISICA GENERAL',                                      NOW()), /*ID 2 */ 
    (1, 2008019, 'ALGEBRA I',                                           NOW()), /*ID 3 */ 
    (1, 2008054, 'CALCULO I',                                           NOW()), /*ID 4 */ 
    (1, 2010010, 'INTRODUCCION A LA PROGRAMACION',                      NOW()), /*ID 5 */
    (1, 1803002, 'INGLES II',                                           NOW()), /*ID 6 */
    (1, 2008022, 'ALGEBRA II',                                          NOW()), /*ID 7 */
    (1, 2008056, 'CALCULO II',                                          NOW()), /*ID 8 */
    (1, 2010003, 'ELEMENTOS DE PROGRAMACION Y ESTRUCTURAS DE DATOS',    NOW()), /*ID 9 */
    (1, 2010013, 'ARQUITECTURA DE COMPUTADORAS I',                      NOW()), /*ID 10 */
    (1, 2010200, 'PROGRAMACION',                                        NOW()), /*ID 11 */
    (1, 2008060, 'CALCULO NUMERICO',                                    NOW()), /*ID 12 */
    (1, 2008140, 'LOGICA',                                              NOW()), /*ID 13 */
    (1, 2010014, 'ARQUITECTURA DE COMPUTADORAS II',                     NOW()), /*ID 14 */
    (1, 2010037, 'TEORIA DE GRAFOS',                                    NOW()), /*ID 15 */
    (1, 2010041, 'ORGANIZACION Y METODOS',                              NOW()),  /*ID 16 */
    (1, 2010206, 'METODOS Y TECNICAS DE PROGRAMACION',                  NOW()),  /*ID 17 */
    (1, 2008029, 'PROBABILIDAD Y ESTADISTICA',                          NOW()),  /*ID 18 */
    (1, 2010005, 'TALLER DE PROGRAMACION EN BAJO NIVEL',                NOW()),  /*ID 19 */
    (1, 2010015, 'BASE DE DATOS I',                                     NOW()),  /*ID 20 */
    (1, 2010018, 'SISTEMAS DE INFORMACION I',                           NOW()),  /*ID 21 */
    (1, 2010038, 'PROGRAMACION FUNCIONAL',                              NOW()),  /*ID 22 */
    (1, 2010197, 'ALGORITMOS AVANZADOS',                                NOW()),  /*ID 23 */
    (1, 2010016, 'BASE DE DATOS II',                                    NOW()),  /*ID 24 */
    (1, 2010017, 'TALLER DE SISTEMAS OPERATIVOS',                       NOW()),  /*ID 25 */
    (1, 2010022, 'SISTEMAS DE INFORMACION II',                          NOW()),  /*ID 26 */
    (1, 2010040, 'TEORIA DE AUTOMATAS Y LENGUAJES DE FORMALES',         NOW()),  /*ID 27 */
    (1, 2010042, 'GRAFICACION POR COMPUTADORA',                         NOW()),  /*ID 28 */
    (1, 2010201, 'INTELIGENCIA ARTIFICIAL I',                           NOW()),  /*ID 29 */
    (1, 2010020, 'INGENIERIA DE SOFTWARE',                              NOW()),  /*ID 30 */
    (1, 2010047, 'REDES DE COMPUTADORAS',                               NOW()),  /*ID 31 */
    (1, 2010049, 'ESTRUCTURA Y SEMANTICA DE LENGUAJES DE PROGRAMACION', NOW()),  /*ID 32 */
    (1, 2010053, 'TALLER DE BASE DE DATOS',                             NOW()),  /*ID 33 */
    (1, 2010202, 'INTELIGENCIA ARTIFICIAL II',                          NOW()),  /*ID 34 */
    (1, 2010203, 'PROGRAMACION WEB',                                    NOW()),  /*ID 35 */
    (1, 2010019, 'SIMULACION DE SISTEMAS',                              NOW()),  /*ID 36 */
    (1, 2010024, 'TALLER DE INGENIERIA DE SOFTWARE',                    NOW()),  /*ID 37 */
    (1, 2010100, 'ARQUITECTURA DE SOFTWARE',                            NOW()),  /*ID 38 */
    (1, 2010204, 'INTERACCION HUMANO COMPUTADOR',                       NOW()),  /*ID 39 */
    (1, 2010205, 'TECNOLOGIA DE REDES AVANZADAS',                       NOW()),  /*ID 40 */
    (1, 2010035, 'APLICACION DE SISTEMAS OPERATIVOS',                   NOW()),  /*ID 41 */
    (1, 2010102, 'EVALUACION Y AUDITORIA DE SISTEMAS',                  NOW()),  /*ID 42 */
    (1, 2010214, 'TALLER DE GRADO I',                                   NOW()),  /*ID 43 */
    (1, 2010066, 'PROCESOS AGILES',                                     NOW()),  /*ID 44 */
    (1, 2010178, 'ENTORNOS VIRTUALES DE APRENDIZAJE',                   NOW()),  /*ID 45 */
    (1, 2010188, 'SERVICIOS TELEMATICOS',                               NOW()),  /*ID 46 */
    (1, 2010189, 'RECONOCIMIENTO DE VOZ',                               NOW()),  /*ID 47 */
    (1, 2010209, 'SEGURIDAD DE SISTEMAS',                               NOW()),  /*ID 48 */
    (1, 2010215, 'TALLER DE GRADO II',                                  NOW()),  /*ID 49 */
    (1, 2010216, 'CLOUD COMPUTING',                                     NOW()),  /*ID 50 */
    (1, 2010217, 'BUSSINESS INTELLIGENCE Y BIG DATA',                   NOW()),  /*ID 51 */
    (1, 2010218, 'CIENCIA DE DATOS Y MACHINE LEARNING',                 NOW())  /*ID 52 */
;
/**
    study_plan_university_subject
*/
insert into study_plan_university_subject (study_plan_id, university_subject_id, grade)
values
    (1, 1803001,  'A'), /* INFORMATICA - INGLES I */
    (1, 2006063,  'A'), /* INFORMATICA - FISICA GENERAL */
    (1, 2008019,  'A'), /* INFORMATICA - ALGEBRA I */
    (1, 2008054,  'A'), /* INFORMATICA - CALCULO I */
    (1, 2010010,  'A'), /* INFORMATICA - INTRODUCCION A LA PROGRAMACION */
    (1, 1803002,  'B'), /* INFORMATICA - INGLES II */
    (1, 2008022,  'B'), /* INFORMATICA - ALGEBRA II */
    (1, 2008056,  'B'), /* INFORMATICA - CALCULO II */
    (1, 2010003,  'B'), /* INFORMATICA - ELEMENTOS DE PROGRAMACION Y EST. DE DATOS */
    (1, 2010013, 'B'), /* INFORMATICA - ARQUITECTURA DE COMPUTADORAS I */
    (1, 2010200, 'B'), /* INFORMATICA - PROGRAMACION */
    (1, 2008060, 'C'), /* INFORMATICA - CALCULO NUMERICO */
    (1, 2008140, 'C'), /* INFORMATICA - LOGICA */
    (1, 2010014, 'C'), /* INFORMATICA - ARQUITECTURA DE COMPUTADORAS II */
    (1, 2010037, 'C'), /* INFORMATICA - TEORIA DE GRAFOS */
    (1, 2010041, 'C'), /* INFORMATICA - ORGANIZACION Y METODOS */
    (1, 2010206, 'C'), /* INFORMATICA - METODOS Y TECNICAS */
    (1, 2008029, 'D'), /* INFORMATICA - PROBABILIDAD Y ESTADISTICA */
    (1, 2010005, 'D'), /* INFORMATICA - TALLER DE PROG. BAJO NIVEL */
    (1, 2010015, 'D'), /* INFORMATICA - BASE DE DATOS I */
    (1, 2010018, 'D'), /* INFORMATICA - SISTEMAS DE INFORMACION I */
    (1, 2010038, 'D'), /* INFORMATICA - PROGRAMACION FUNCIONAL */
    (1, 2010197, 'D'), /* INFORMATICA - ALGORITMOS AVANZADOS */
    (1, 2010016, 'E'), /* INFORMATICA - BASE DE DATOS II */
    (1, 2010017, 'E'), /* INFORMATICA - TALLER DE SISTEMAS OPERATIVOS */
    (1, 2010022, 'E'), /* INFORMATICA - SISTEMAS DE INFORMACION II */
    (1, 2010040, 'E'), /* INFORMATICA - TEORIA DE AUTOMATAS */
    (1, 2010042, 'E'), /* INFORMATICA - GRAFICACION POR COMPUTADORAS */
    (1, 2010201, 'E'), /* INFORMATICA - INTELIGENCIA ARTIFICIAL I */
    (1, 2010020, 'F'), /* INFORMATICA - INGENIERIA DE SOFTWARE */
    (1, 2010047, 'F'), /* INFORMATICA - REDES DE COMPUTADORAS */
    (1, 2010049, 'F'), /* INFORMATICA - ESTRUCTURA Y SEMANTICA */
    (1, 2010053, 'F'), /* INFORMATICA - TALLER DE BASE DE DATOS */
    (1, 2010202, 'F'), /* INFORMATICA - INTELIGENCIA ARTIFICIAL II */
    (1, 2010203, 'F'), /* INFORMATICA - PROGRAMACION WEB */
    (1, 2010019, 'G'), /* INFORMATICA - SIMULACION DE SISTEMAS */
    (1, 2010024, 'G'), /* INFORMATICA - TALLER DE INGENIERIA DE SOFTWARE */
    (1, 2010100, 'G'), /* INFORMATICA - ARQUITECTURA DE SOFTWARE */
    (1, 2010204, 'G'), /* INFORMATICA - INTERACCION HUMANO COMPUTADOR */
    (1, 2010205, 'G'), /* INFORMATICA - REDES AVANZADAS */
    (1, 2010035, 'G'), /* INFORMATICA - APLICACION DE SISTEMAS OPERATIVOS */
    (1, 2010102, 'H'), /* INFORMATICA - EVALUACION Y AUDITORIA DE SISTEMAS */
    (1, 2010214, 'H'), /* INFORMATICA - TALLER DE GRADO I */
    (1, 2010066, 'H'), /* INFORMATICA - PROCESOS AGILES */
    (1, 2010178, 'H'), /* INFORMATICA - ENTORNOS VIRTUALES DE APRENDIZAJE */
    (1, 2010188, 'H'), /* INFORMATICA - SERVICIOS TELEMATICOS */
    (1, 2010189, 'H'), /* INFORMATICA - RECONOCIMIENTO DE VOZ */
    (1, 2010209, 'H'), /* INFORMATICA - SEGURIDAD DE SISTEMAS */
    (1, 2010215, 'I'), /* INFORMATICA - TALLER DE GRADO II */
    (1, 2010216, 'I'), /* INFORMATICA - CLOUD COMPUTING */
    (1, 2010217, 'I'), /* INFORMATICA - BUSSINESS INTELLIGENCE */
    (1, 2010218, 'I') /* INFORMATICA - CIENCIA DE DATOS Y MACHINE LEARNING */
;
/*
    teacher_subjects
*/
insert into teacher_subjects (group_number, person_id, university_subject_id)
values 
    ('1', 1, 1803001),      /* ID 1  |INGLES I G1 - MARIA BENITA*/
    ('2', 1, 1803001),      /* ID 2  |INGLES I G2 - MARIA BENITA*/
    ('3', 2, 1803001),      /* ID 3  |INGLES I G3 - PEETERS*/

    ('B',  3, 2006063),      /* ID 4  |FISICA GENERAL - ROBERTO VALENZUELA*/
    ('B1', 4, 2006063),      /* ID 5  |FISICA GENERAL - RENE MOREIRA*/
    ('B2', 5, 2006063),      /* ID 6  |FISICA GENERAL - ROCIO GUZMAN*/
    ('B3', 7, 2006063),      /* ID 7  |FISICA GENERAL - MIGUEL ANGEL ORDONEZ*/
    ('B4', 6, 2006063),      /* ID 8  |FISICA GENERAL - ROCIO GUZMAN*/
    ('B5', 8, 2006063),      /* ID 9  |FISICA GENERAL - JUAN CARLOS TERRAZAS*/
    ('B6', 8, 2006063),      /* ID 10 |FISICA GENERAL - JUAN CARLOS TERRAZAS*/

    ('10', 9, 2008019),      /* ID 11  |ALGEBRA I - JUAN ANTONIO RODRIGUEZ*/
    ('15', 10, 2008019),      /* ID 12  |ALGEBRA I - ALVARO HERNANDO CALVO*/
    ('8',  11, 2008019),      /* ID 13  |ALGEBRA I - GUALBERTO LEON*/

    ('10', 12, 2008054),      /* ID 14  |CALCULO I - POR DESIGNAR*/
    ('11', 13, 2008054),      /* ID 15  |CALCULO I - RAMIRO ZURITA*/

    ('1',  14, 2010010),      /* ID 16  |INTRO A LA PROGRA - CARLA SALAZAR*/
    ('10', 15, 2010010),      /* ID 17  |INTRO A LA PROGRA - VLADIMIR COSTAS*/
    ('2',  16, 2010010),      /* ID 18  |INTRO A LA PROGRA - LETICIA BLANCO*/
    ('3',  17, 2010010),      /* ID 19  |INTRO A LA PROGRA - HERNAN USTARIZ*/
    ('4',  18, 2010010),      /* ID 20  |INTRO A LA PROGRA - HENRY FRANK*/
    ('5',  19, 2010010),      /* ID 21  |INTRO A LA PROGRA - VICTOR HUGO*/
    ('6',  14, 2010010),      /* ID 22 |INTRO A LA PROGRA - CARLA SALAZAR*/
    ('7',  15, 2010010),      /* ID 23  |INTRO A LA PROGRA - VLADIMIR COSTAS*/

    ('1', 2, 1803002),      /* ID 24  |INGLES II - MAGDA LENA*/
    ('2', 2, 1803002),      /* ID 25  |INGLES II - MAGDA LENA*/

    ('5A', 20, 2008022),      /* ID 26  |ALGEBRA II - WALTER GONZALO*/
    ('6',  21, 2008022),      /* ID 27  |ALGEBRA II - HERNAN SILVA*/
    ('8',  22, 2008022),      /* ID 28  |ALGEBRA II - JOSE OMONTE*/

    ('12', 23, 2008056),      /* ID 29  |CALCULO II - AMILCAR*/
    ('6',  24, 2008056),      /* ID 30  |CALCULO II - LOBO*/

    ('1', 25, 2010003),      /* ID 31  |ELEMENTOS DE PROGRAMACION - ROSEMARY*/
    ('2', 16, 2010003),      /* ID 32  |ELEMENTOS DE PROGRAMACION - LETICIA*/
    ('3', 16, 2010003),      /* ID 33  |ELEMENTOS DE PROGRAMACION - LETICIA*/
    ('5', 26, 2010003),      /* ID 34  |ELEMENTOS DE PROGRAMACION - HELDER OCTAVIO*/

    ('1', 27, 2010013),      /* ID 35  |ARQUITECTURA I - SAMUEL ACHA*/
    ('2', 16, 2010013),      /* ID 36  |ARQUITECTURA I - LETI*/

    ('1', 25, 2010200),      /* ID 37  |PROGRAMACION - ROSEMARY*/

    ('2', 28, 2008060),      /* ID 38  |CALCULO NUMERICO - JUCHANI*/
    ('3', 29, 2008060),      /* ID 39  |CALCULO NUMERICO - OSCAR ZABALAGA*/

    ('1', 30, 2008140),      /* ID 40  |LOGICA - HOEPFNER*/

    ('1', 31, 2010014),      /* ID 41  |ARQUITECTURA II - ROBERTO AGREDA*/

    ('1', 32, 2010037),      /* ID 42  |TEORIA DE GRAFOS - YONY MONTOYA*/

    ('1', 33, 2010041),      /* ID 43  |ORGANIZACION Y METODOS - INDIRA*/

    ('1', 34, 2010206),      /* ID 44  |METODOS Y TECNICAS DE PROG. - CORINA*/
    ('2', 35, 2010206),      /* ID 45  |METODOS Y TECNICAS DE PROG. - CARLOS MANZUR*/
    ('5', 32, 2010206),      /* ID 46  |METODOS Y TECNICAS DE PROG. -  YONY MONTOYA*/

    ('3', 36, 2008029),      /* ID 47  |PROB. Y ESTADISTICA - DELGADILLO COSSIO*/
    ('4', 22, 2008029),      /* ID 48  |PROB. Y ESTADISTICA - OMONTE*/

    ('1', 37, 2010005),      /* ID 49  |TALLER DE BAJO NIVEL - MONTECINOS*/

    ('1', 38, 2010015),      /* ID 50  |BASE DE DATOS I - VITTER JESUS MEDRANO*/
    ('2', 39, 2010015),      /* ID 51  |BASE DE DATOS I - BORIS CALANCHA*/

    ('1', 14, 2010018),      /* ID 52  |SISTEMAS DE INFORMACION I - CARLA SALAZAR*/
    ('2', 14, 2010018),      /* ID 53  |SISTEMAS DE INFORMACION I - CARLA SALAZAR*/

    ('1', 40, 2010038),      /* ID 54  |FUNCIONAL - TATIANA*/

    ('1', 16, 2010197),      /* ID 55  |ALGORITMOS AVANZADOS - LETICIA*/

    ('1', 40, 2010016),      /* ID 56  |BASE DE DATOS II - TATIANA*/
    ('2', 40, 2010016),      /* ID 57  |BASE DE DATOS II - TATIANA*/

    ('1', 41, 2010017),      /* ID 58  |TSO - JORGE ORELLANA*/
    ('2', 41, 2010017),      /* ID 59  |TSO - JORGE ORELLANA*/
    ('5', 42, 2010017),      /* ID 60  |TSO - GROVER CUSSI*/

    ('1', 43, 2010022),      /* ID 61  |SISTEMAS DE INFO II - MARCELO FLORES*/
    ('2', 44, 2010022),      /* ID 62  |SISTEMAS DE INFO II - K. JALDIN*/

    ('1', 19, 2010040),      /* ID 63  |AUTOMATAS. - VICTOR HUGO MONTANO*/

    ('1', 39, 2010042),      /* ID 64  |GRAFICACION POR COMPUTADORA - BORIS CALANCHA*/

    ('1', 45, 2010201),      /* ID 65  |INTELIGENCIA ARTIFICIAL I - CARMEN ROSA*/
    ('2', 46, 2010201),      /* ID 66  |INTELIGENCIA ARTIFICIAL I - ERIKA PATRICIA*/

    ('1', 33, 2010020),      /* ID 67  |INGENIERIA DE SOFTWARE - INDIRA*/
    ('2', 25, 2010020),      /* ID 68  |INGENIERIA DE SOFTWARE - ROSEMARY*/

    ('1', 41, 2010047),      /* ID 69  |REDES DE COMPUTADORAS - JORGE ORELLANA*/
    ('2', 41, 2010047),      /* ID 70  |REDES DE COMPUTADORAS - JORGE ORELLANA*/

    ('1', 47, 2010049),      /* ID 71  |ESTRUCTURA Y SEMANTICA - PATRICIA ROMERO*/

    ('1', 39, 2010053),      /* ID 72  |TALLER DE BASE DE DATOS - BORIS CALANCHA*/
    ('2', 39, 2010053),      /* ID 73  |TALLER DE BASE DE DATOS - BORIS CALANCHA*/
    ('3', 43, 2010053),      /* ID 74  |TALLER DE BASE DE DATOS - MARCELO FLORES*/
    ('4', 39, 2010053),      /* ID 75  |TALLER DE BASE DE DATOS - BORIS CALANCHA*/

    ('2', 45, 2010202),      /* ID 76  |INTELIGENCIA ARTIFICIAL II - CARMEN ROSA*/

    ('1', 15, 2010203),      /* ID 77  |PROGRAMACION WEB - VLADIMIR COSTAS*/

    ('1', 18, 2010019),      /* ID 78  |SIMULACION DE SISTEMAS - HENRY FRANK VILLARROEL*/

    ('1', 34, 2010024),      /* ID 79  |TALLER DE INGENIERIA DE SOFTWARE - CORINA*/
    ('2', 16, 2010024),      /* ID 80  |TALLER DE INGENIERIA DE SOFTWARE - LETICIA*/

    ('1', 48, 2010100),      /* ID 81  |ARQUITECTURA DE SOFTWARE - CLAUDIA URENA*/

    ('1', 34, 2010204),      /* ID 82  |IHC - CORINA*/

    ('1', 37, 2010205),      /* ID 83  |REDES AVANZADAS - MONTECINOS*/

    ('1', 42, 2010035),      /* ID 84  |APLICACION DE SISTEMAS OPERATIVOS - GROVER CUSSI*/
    ('2', 42, 2010035),      /* ID 85  |APLICACION DE SISTEMAS OPERATIVOS - GROVER CUSSI*/

    ('1', 47, 2010102),      /* ID 86  |EVALUACION Y AUDITORIA DE SISTEMAS - PATRICIA ROMERO*/
    ('2', 49, 2010102),      /* ID 87  |EVALUACION Y AUDITORIA DE SISTEMAS - JIMMY NOVILLO*/

    ('6', 34, 2010214),      /* ID 88  |TALLER DE GRADO I - CORINA*/
    ('7', 47, 2010214),      /* ID 89  |TALLER DE GRADO I - PATRICIA ROMERO*/

    ('1', 50, 2010066),      /* ID 90  |PROCESOS AGILES - NIBETH MENA*/

    ('1', 15, 2010178),      /* ID 91  |ENTORNOS VIRTUALES DE APRENDIZAJE - VLADIMIR COSTAS*/

    ('1', 51, 2010188),      /* ID 92  |TELEMATICOS - AMERICO FIORILO*/

    ('1', 45, 2010189),      /* ID 93  |RECONOCIMIENTO DE VOZ - CARMEN ROSA*/

    ('1', 52, 2010209),      /* ID 94  |SEGURIDAD DE SISTEMAS - MARCELO ANTEZANA*/

    ('2', 19, 2010215),      /* ID 95  |TALLER DE GRADO II - VICTOR HUGO MONTANO*/
    ('3', 45, 2010215),      /* ID 96  |TALLER DE GRADO II - CARMEN ROSA*/
    ('4', 47, 2010215),      /* ID 97  |TALLER DE GRADO II - PATRICIA ROMERO*/

    ('1', 32, 2010216),      /* ID 98  |CLOUD COMPUTING - YONY MONTOYA*/

    ('1', 53, 2010217),      /* ID 99  |BUSSINESS INTELIGENCE Y BIG DATA - VICTOR ADOLFO*/

    ('1', 46, 2010218)      /* ID 99  |MACHINE LEARNING - ERIKA PATRICIA*/
; 
/*
    block_statuses
*/
insert into block_statuses(name) values
    ('HABILITADO'),
    ('DESHABILITADO'),
    ('ELIMINADO')
;
/*
    blocks
*/
insert into blocks (id, name, max_floor, block_status_id, max_classrooms)
values 
    (29, 'BLOQUE 29',                           0, 1, 20),     /*ID F*/
    (9,  'DEPARTAMENTO DE BIOLOGIA',            2, 1, 20),     /*ID F*/       
    (27, 'BLOQUE 27',                           3, 1, 20),     /*ID F*/    
    (7,  'DEPARTAMENTO DE QUIMICA',             0, 1, 20),     /*ID F*/    
    (28, 'BLOQUE 28',                           2, 1, 20),     /*ID F*/    
    (4,  'DEPARTAMENTO DE FISICA',              0, 1, 20),     /*ID F*/
    (12, 'BLOQUE 12',                           2, 1, 20),     /*ID F*/       
    (13, 'BLOQUE 13',                           3, 1, 20),     /*ID F*/    
    (11, 'BLOQUE 11',                           0, 1, 20),     /*ID F*/    
    (15, 'BIBLIOTECA FCYT',                     2, 1, 20),     /*ID F*/    
    (22, 'DEPARTAMENTO INDUSTRIAL',             0, 1, 20),     /*ID F*/
    (17, 'PLANTA DE PROCESOS INDUSTRIALES',     2, 1, 20),     /*ID F*/       
    (19, 'SECTOR LABORATORIOS MECANICA',        3, 1, 20),     /*ID F*/    
    (20, 'EDIFICIO CAD - CAM',                  0, 1, 20),     /*ID F*/    
    (1,  'BLOQUE CENTRAL EDIFICIO DECANATURA',  2, 1, 20),     /*ID F*/    
    (16, 'EDIFICIO ACADEMICO 2',                0, 1, 20),     /*ID F*/
    (65, 'BLOQUE TRENCITO',                     2, 1, 20),     /*ID F*/       
    (63, 'AULAS INF - LAB',                     3, 1, 20),     /*ID F*/    
    (64, 'EDIFICIO MEMI',                       2, 1, 20),     /*ID F*/    
    (10, 'EDIFICIO ELEKTRO',                    0, 1, 20),     /*ID F*/
    (26, 'EDIFICIO DE LABORATORIOS BASICOS',    2, 1, 20)      /*ID F*/       
; 
/*
    classroom_types
*/
insert into classroom_types (description, created_at, updated_at) 
values 
    ('LABORATORIO', NOW(), NOW()), 
    ('AULA',        NOW(), NOW()), 
    ('AUDITORIO',   NOW(), NOW())
;
/*
    classroom_statues
*/
insert into classroom_statuses (name, created_at, updated_at) 
values 
    ('HABILITADO', NOW(), NOW()), 
    ('DESHABILITADO', NOW(), NOW()), 
    ('ELIMINADO', NOW(), NOW())
;
/*
    classroom
*/
insert into classrooms (name, capacity, floor, block_id, classroom_type_id, classroom_status_id, created_at, updated_at)
values 
    ('607',     50, 0, 29, 1, 1, NOW(), NOW()),          /*ID 1 |AULAS INF-LAB*/ 
    
    ('606',     50, 1, 9, 1, 1, NOW(), NOW()),          /*ID 2 |AULAS INF-LAB*/ 
    ('608',     25, 0, 9, 1, 1, NOW(), NOW()),          /*ID 3 |AULAS INF-LAB*/ 
    ('609',     25, 0, 9, 1, 1, NOW(), NOW()),          /*ID 4 |AULAS INF-LAB*/ 
    ('608A',    25, 0, 9, 1, 1, NOW(), NOW()),          /*ID 5 |AULAS INF-LAB*/ 
    ('608B',    50, 0, 9, 1, 1, NOW(), NOW()),          /*ID 6 |EDIFICIO MEMI*/ 
    ('609A',    50, 0, 9, 1, 1, NOW(), NOW()),          /*ID 7 |EDIFICIO MEMI*/ 

    ('612',     75, 0, 27, 3, 1, NOW(), NOW()),             /*ID 8 |EDIFICIO MEMI*/ 

    ('613',  50, 0, 7, 2, 1, NOW(), NOW()),                /*ID 9  |EDIFICIO ACADEMICO 2*/ 
    ('614',  50, 0, 7, 2, 1, NOW(), NOW()),                /*ID 10 |EDIFICIO ACADEMICO 2*/ 
    ('615',  50, 0, 7, 2, 1, NOW(), NOW()),                /*ID 11 |EDIFICIO ACADEMICO 2*/ 
    ('616',  50, 0, 7, 2, 1, NOW(), NOW()),                /*ID 12 |EDIFICIO ACADEMICO 2*/ 
    ('616A', 50, 1, 7, 2, 1, NOW(), NOW()),                /*ID 13 |EDIFICIO ACADEMICO 2*/ 

    ('617',   50, 1, 28, 2, 1, NOW(), NOW()),              /*ID 14 |EDIFICIO ACADEMICO 2*/ 
    ('617B', 100, 0, 28, 2, 1, NOW(), NOW()),               /*ID 15 |EDIFICIO ACADEMICO 2*/ 
    ('617C', 100, 0, 28, 2, 1, NOW(), NOW()),               /*ID 16 |EDIFICIO ACADEMICO 2*/ 

    ('618',                            100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 17 |EDIFICIO ACADEMICO 2*/ 
    ('619',                            100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 18 |EDIFICIO ACADEMICO 2*/ 
    ('619A',                           100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 19 |EDIFICIO ACADEMICO 2*/ 
    ('620',                            100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 20 |EDIFICIO ACADEMICO 2*/ 
    ('620B',                           100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 21 |EDIFICIO ACADEMICO 2*/ 
    ('621',                            100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 22 |EDIFICIO ACADEMICO 2*/ 
    ('621A',                           100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 23 |EDIFICIO ACADEMICO 2*/ 
    ('FISCOM GABINETE COMPUTO FISICA', 100, 0, 4, 2, 1, NOW(), NOW()),               /*ID 24 |EDIFICIO ACADEMICO 2*/ 

    ('622', 100, 0, 12, 2, 1, NOW(), NOW()),               /*ID 25 |EDIFICIO ACADEMICO 2*/ 

    ('623', 100, 0, 13, 2, 1, NOW(), NOW()),               /*ID 26 |EDIFICIO ACADEMICO 2*/ 
    
    ('624',  75, 0, 11, 2, 1,  NOW(), NOW()),               /*ID 27 |EDIFICIO ACADEMICO 2*/ 
    
    ('625C',  75, 2, 15, 2, 1,  NOW(), NOW()),               /*ID 28 |EDIFICIO ACADEMICO 2*/ 
    ('625D',  75, 2, 15, 2, 1,  NOW(), NOW()),               /*ID 29 |EDIFICIO ACADEMICO 2*/ 
    ('CAE',   75, 1, 15, 2, 1,  NOW(), NOW()),               /*ID 30 |EDIFICIO ACADEMICO 2*/ 

    ('631',   100, 0, 22, 2, 1,        NOW(), NOW()),        /*ID 31 |EDIFICIO ACADEMICO 2*/ 
    ('632',   100, 0, 22, 2, 1,        NOW(), NOW()),        /*ID 32 |EDIFICIO ACADEMICO 2*/ 
    ('CAE 2', 100, 0, 22, 2, 1,        NOW(), NOW()),        /*ID 33 |EDIFICIO ACADEMICO 2*/ 

    ('635', 100, 1, 17, 2, 1, NOW(), NOW()),                /*ID 34 |BLOQUE TRENCITO*/ 

    ('640', 100, 0, 19, 2, 1, NOW(), NOW()),                /*ID 35 |BLOQUE TRENCITO*/ 
    ('642', 100, 0, 19, 2, 1, NOW(), NOW()),                /*ID 36 |BLOQUE TRENCITO*/ 
    ('643', 100, 0, 19, 2, 1, NOW(), NOW()),                /*ID 37 |BLOQUE TRENCITO*/ 

    ('644',    100, 1, 20, 2, 1, NOW(), NOW()),                /*ID 38 |BLOQUE TRENCITO*/ 
    ('644A',   100, 1, 20, 2, 1, NOW(), NOW()),                /*ID 39 |BLOQUE TRENCITO*/ 
    ('AUDCIV', 100, 3, 20, 2, 1, NOW(), NOW()),                /*ID 40 |BLOQUE TRENCITO*/ 

    ('651',    50, 0, 1, 2, 1, NOW(), NOW()),                 /*ID 41 |BLOQUE CENTRAL - EDIFICIO DECANATURA*/ 
    ('652',    50, 0, 1, 2, 1, NOW(), NOW()),                 /*ID 42 |BLOQUE CENTRAL - EDIFICIO DECANATURA*/ 
    ('655',    50, 0, 1, 2, 1, NOW(), NOW()),                 /*ID 43 |BLOQUE CENTRAL - EDIFICIO DECANATURA*/ 
    ('AUDMEC', 50, 2, 1, 2, 1, NOW(), NOW()),                 /*ID 44 |BLOQUE CENTRAL - EDIFICIO DECANATURA*/ 
    ('MAGCIV', 50, 2, 1, 2, 1, NOW(), NOW()),                 /*ID 45 |BLOQUE CENTRAL - EDIFICIO DECANATURA*/ 

    ('690A',   50, 0, 16, 2, 1, NOW(), NOW()),                /*ID 46 |EDIFICIO ACADEMICO 2*/ 
    ('690B',   50, 0, 16, 2, 1, NOW(), NOW()),                /*ID 47 |EDIFICIO ACADEMICO 2*/ 
    ('690C',   50, 0, 16, 2, 1, NOW(), NOW()),                /*ID 48 |EDIFICIO ACADEMICO 2*/ 
    ('690D',   50, 0, 16, 2, 1, NOW(), NOW()),                /*ID 49 |EDIFICIO ACADEMICO 2*/ 
    ('690E',   50, 0, 16, 2, 1, NOW(), NOW()),                /*ID 50 |EDIFICIO ACADEMICO 2*/ 
    ('690MAT', 50, 0, 16, 2, 1, NOW(), NOW()),                /*ID 51 |EDIFICIO ACADEMICO 2*/ 
    
    ('691A', 100, 1, 16, 2, 1, NOW(), NOW()),               /*ID 52 |EDIFICIO ACADEMICO 2*/ 
    ('691B', 100, 1, 16, 2, 1, NOW(), NOW()),               /*ID 53 |EDIFICIO ACADEMICO 2*/ 
    ('691C', 100, 1, 16, 2, 1, NOW(), NOW()),               /*ID 54 |EDIFICIO ACADEMICO 2*/ 
    ('691D', 100, 1, 16, 2, 1, NOW(), NOW()),               /*ID 55 |EDIFICIO ACADEMICO 2*/ 
    ('691E', 100, 1, 16, 2, 1, NOW(), NOW()),               /*ID 56 |EDIFICIO ACADEMICO 2*/ 
    ('691F', 100, 1, 16, 2, 1, NOW(), NOW()),               /*ID 57 |EDIFICIO ACADEMICO 2*/ 

    ('692A', 100, 2, 16, 2, 1, NOW(), NOW()),               /*ID 58 |EDIFICIO ACADEMICO 2*/ 
    ('692B', 100, 2, 16, 2, 1, NOW(), NOW()),               /*ID 59 |EDIFICIO ACADEMICO 2*/ 
    ('692C', 100, 2, 16, 2, 1, NOW(), NOW()),               /*ID 60 |EDIFICIO ACADEMICO 2*/ 
    ('692D', 100, 2, 16, 2, 1, NOW(), NOW()),               /*ID 61 |EDIFICIO ACADEMICO 2*/ 
    ('692E', 100, 2, 16, 2, 1, NOW(), NOW()),               /*ID 62 |EDIFICIO ACADEMICO 2*/ 
    ('692F', 100, 2, 16, 2, 1, NOW(), NOW()),               /*ID 63 |EDIFICIO ACADEMICO 2*/ 
    ('692G',  75, 2, 16, 2, 1,  NOW(), NOW()),               /*ID 64 |EDIFICIO ACADEMICO 2*/ 
    ('692H',  75, 2, 16, 2, 1,  NOW(), NOW()),               /*ID 65 |EDIFICIO ACADEMICO 2*/ 

    ('693A',        100, 2, 16, 2, 1,        NOW(), NOW()),        /*ID 66 |EDIFICIO ACADEMICO 2*/ 
    ('693B',        100, 2, 16, 2, 1,        NOW(), NOW()),        /*ID 67 |EDIFICIO ACADEMICO 2*/ 
    ('693C',        100, 2, 16, 2, 1,        NOW(), NOW()),        /*ID 68 |EDIFICIO ACADEMICO 2*/ 
    ('693D',        100, 2, 16, 2, 1,        NOW(), NOW()),        /*ID 69 |EDIFICIO ACADEMICO 2*/ 
    ('AUDITORIO 2', 300, 2, 16, 3, 1,        NOW(), NOW()),        /*ID 70 |EDIFICIO ACADEMICO 2*/ 

    ('660',     50, 0, 65, 1, 1, NOW(), NOW()),          /*ID 71 |AULAS INF-LAB*/ 
    ('661',     50, 0, 65, 1, 1, NOW(), NOW()),          /*ID 72 |AULAS INF-LAB*/ 

    ('LABORATORIO DE COMPUTO 1',     50, 0, 63, 1, 1, NOW(), NOW()),          /*ID 73 |AULAS INF-LAB*/ 
    ('LABORATORIO DE COMPUTO 2',     50, 0, 63, 1, 1, NOW(), NOW()),          /*ID 74 |AULAS INF-LAB*/ 
    ('LABORATORIO DE REDES',         25, 0, 63, 1, 1, NOW(), NOW()),          /*ID 75 |AULAS INF-LAB*/ 
    ('LABORATORIO DE DESARROLLO',    25, 0, 63, 1, 1, NOW(), NOW()),          /*ID 76 |AULAS INF-LAB*/ 
    ('LABORATORIO DE MANTENIMIENTO', 25, 0, 63, 1, 1, NOW(), NOW()),          /*ID 77 |AULAS INF-LAB*/ 

    ('LABORATORIO DE COMPUTO 3',                   50, 2, 64, 1, 1, NOW(), NOW()),          /*ID 78 |EDIFICIO MEMI*/ 
    ('LABORATORIO DE COMPUTO 4 - VIRTUALIZACION',  50, 2, 64, 1, 1, NOW(), NOW()),          /*ID 79 |EDIFICIO MEMI*/ 
    ('AUDITORIO DE USO MULTIPLE',                  75, 2, 64, 3, 1, NOW(), NOW()),          /*ID 80 |EDIFICIO MEMI*/ 

    ('667A', 50, 0, 10, 2, 1, NOW(), NOW()),                /*ID 81 |EDIFICIO ACADEMICO 2*/ 
    ('667B', 50, 0, 10, 2, 1, NOW(), NOW()),                /*ID 82 |EDIFICIO ACADEMICO 2*/ 
    ('668',  50, 0, 10, 2, 1, NOW(), NOW()),                /*ID 83 |EDIFICIO ACADEMICO 2*/ 
    
    ('669A', 100, 1, 10, 2, 1, NOW(), NOW()),               /*ID 84 |EDIFICIO ACADEMICO 2*/ 
    ('669B', 100, 1, 10, 2, 1, NOW(), NOW()),               /*ID 85 |EDIFICIO ACADEMICO 2*/ 
    ('670',  100, 1, 10, 2, 1, NOW(), NOW()),               /*ID 86 |EDIFICIO ACADEMICO 2*/ 

    ('671',  100, 2, 10, 2, 1, NOW(), NOW()),               /*ID 87 |EDIFICIO ACADEMICO 2*/ 
    ('671A', 100, 2, 10, 2, 1, NOW(), NOW()),               /*ID 88 |EDIFICIO ACADEMICO 2*/ 
    ('671B', 100, 2, 10, 2, 1, NOW(), NOW()),               /*ID 89 |EDIFICIO ACADEMICO 2*/ 
    ('671C', 100, 2, 10, 2, 1, NOW(), NOW()),               /*ID 90 |EDIFICIO ACADEMICO 2*/ 
    ('672',  100, 2, 10, 2, 1, NOW(), NOW()),               /*ID 91 |EDIFICIO ACADEMICO 2*/ 

    ('674A', 100, 2, 10, 2, 1,        NOW(), NOW()),        /*ID 92 |EDIFICIO ACADEMICO 2*/ 
    ('674B', 100, 2, 10, 2, 1,        NOW(), NOW()),        /*ID 93 |EDIFICIO ACADEMICO 2*/ 
    ('675',  100, 2, 10, 2, 1,        NOW(), NOW()),        /*ID 94 |EDIFICIO ACADEMICO 2*/ 

    ('680-A', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 95 |EDIFICIO ACADEMICO 2*/ 
    ('680-B', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 96 |EDIFICIO ACADEMICO 2*/ 
    ('680-C', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 97 |EDIFICIO ACADEMICO 2*/ 
    ('680-E', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 98 |EDIFICIO ACADEMICO 2*/ 
    ('680-F', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 99 |EDIFICIO ACADEMICO 2*/ 
    ('680-G', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 100 |EDIFICIO ACADEMICO 2*/ 
    ('680-H', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 101 |EDIFICIO ACADEMICO 2*/ 
    ('680-I', 50, 0, 26, 2, 1, NOW(), NOW()),              /*ID 102 |EDIFICIO ACADEMICO 2*/ 

    ('681-A', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 103 |EDIFICIO ACADEMICO 2*/ 
    ('681-B', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 104 |EDIFICIO ACADEMICO 2*/ 
    ('681-C', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 105 |EDIFICIO ACADEMICO 2*/ 
    ('681-D', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 106 |EDIFICIO ACADEMICO 2*/ 
    ('681-E', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 107 |EDIFICIO ACADEMICO 2*/ 
    ('681-F', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 108 |EDIFICIO ACADEMICO 2*/ 
    ('681-G', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 109 |EDIFICIO ACADEMICO 2*/ 
    ('681-H', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 110 |EDIFICIO ACADEMICO 2*/ 
    ('681-I', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 111 |EDIFICIO ACADEMICO 2*/ 
    ('681-J', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 112 |EDIFICIO ACADEMICO 2*/ 
    ('681-K', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 113 |EDIFICIO ACADEMICO 2*/ 
    ('681-L', 100, 1, 26, 2, 1, NOW(), NOW()),               /*ID 114 |EDIFICIO ACADEMICO 2*/ 

    ('682-A', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 115 |EDIFICIO ACADEMICO 2*/ 
    ('682-B', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 116 |EDIFICIO ACADEMICO 2*/ 
    ('682-C', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 117 |EDIFICIO ACADEMICO 2*/ 
    ('682-D', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 118 |EDIFICIO ACADEMICO 2*/ 
    ('682-E', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 119 |EDIFICIO ACADEMICO 2*/ 
    ('682-F', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 120 |EDIFICIO ACADEMICO 2*/ 
    ('682-G',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 121 |EDIFICIO ACADEMICO 2*/ 
    ('682-H',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 122 |EDIFICIO ACADEMICO 2*/ 
    ('682-I', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 123 |EDIFICIO ACADEMICO 2*/ 
    ('682-J',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 124 |EDIFICIO ACADEMICO 2*/ 
    ('682-K',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 125 |EDIFICIO ACADEMICO 2*/ 
    ('682-L',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 126 |EDIFICIO ACADEMICO 2*/ 

    ('683-A', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 127 |EDIFICIO ACADEMICO 2*/ 
    ('683-B', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 128 |EDIFICIO ACADEMICO 2*/ 
    ('683-C', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 129 |EDIFICIO ACADEMICO 2*/ 
    ('683-D', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 130 |EDIFICIO ACADEMICO 2*/ 
    ('683-E', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 131 |EDIFICIO ACADEMICO 2*/ 
    ('683-F', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 132 |EDIFICIO ACADEMICO 2*/ 
    ('683-G',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 133 |EDIFICIO ACADEMICO 2*/ 
    ('683-H',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 134 |EDIFICIO ACADEMICO 2*/ 
    ('683-I', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 135 |EDIFICIO ACADEMICO 2*/ 
    ('683-J',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 136 |EDIFICIO ACADEMICO 2*/ 
    ('683-K',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 137 |EDIFICIO ACADEMICO 2*/ 
    ('683-L',  75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 138 |EDIFICIO ACADEMICO 2*/ 

    ('683-A',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 139 |EDIFICIO ACADEMICO 2*/ 
    ('683-B',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 140 |EDIFICIO ACADEMICO 2*/ 
    ('683-C',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 141 |EDIFICIO ACADEMICO 2*/ 
    ('683-D',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 142 |EDIFICIO ACADEMICO 2*/ 
    ('BIBLIO', 100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 143 |EDIFICIO ACADEMICO 2*/ 
    ('683-E',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 144 |EDIFICIO ACADEMICO 2*/ 
    ('683-F',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 145 |EDIFICIO ACADEMICO 2*/ 
    ('683-G',   75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 146 |EDIFICIO ACADEMICO 2*/ 
    ('683-H',   75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 147 |EDIFICIO ACADEMICO 2*/ 
    ('683-I',  100, 2, 26, 2, 1, NOW(), NOW()),               /*ID 148 |EDIFICIO ACADEMICO 2*/ 
    ('683-J',   75, 2, 26, 2, 1,  NOW(), NOW()),              /*ID 149 |EDIFICIO ACADEMICO 2*/ 
    ('683-K',   75, 2, 26, 2, 1,  NOW(), NOW())               /*ID 150 |EDIFICIO ACADEMICO 2*/ 
;
/*
    reservation_status
*/
insert into reservation_statuses (status, created_at, updated_at)
values 
    ('ACEPTADO',                NOW(), NOW()),  /*ID 1*/
    ('RECHAZADO',               NOW(), NOW()),  /*ID 2*/  
    ('PENDIENTE',               NOW(), NOW()),  /*ID 3*/ 
    ('CANCELADO',               NOW(), NOW()),  /*ID 4*/
    ('EXPIRADO ACEPTADO',       NOW(), NOW()),  /*ID 5*/
    ('EXPIRADO RECHAZADO',      NOW(), NOW()),  /*ID 6*/
    ('EXPIRADO PENDIENTE',      NOW(), NOW())   /*ID 7*/
; 
/*
    reservation_reasons
*/
insert into reservation_reasons (reason, created_at, updated_at) 
values 
    ('EXAMEN',           NOW(), NOW()), /*ID 1*/ 
    ('CLASES',           NOW(), NOW()), /*ID 2*/
    ('DEFENSA DE TESIS', NOW(), NOW()), /*ID 3*/
    ('PRACTICA',         NOW(), NOW())  /*ID 4*/
;
/*
    time_slot
*/
insert into time_slots(time, created_at, updated_at)
values
    ('06:45:00', NOW(), NOW()), /*ID 1*/
    ('07:30:00', NOW(), NOW()), /*ID 2*/
    ('08:15:00', NOW(), NOW()), /*ID 3*/
    ('09:00:00', NOW(), NOW()), /*ID 4*/
    ('09:45:00', NOW(), NOW()), /*ID 5*/
    ('10:30:00', NOW(), NOW()), /*ID 6*/
    ('11:15:00', NOW(), NOW()), /*ID 7*/
    ('12:00:00', NOW(), NOW()), /*ID 8*/
    ('12:45:00', NOW(), NOW()), /*ID 9*/
    ('13:30:00', NOW(), NOW()), /*ID 10*/
    ('14:15:00', NOW(), NOW()), /*ID 11*/
    ('15:00:00', NOW(), NOW()), /*ID 12*/
    ('15:45:00', NOW(), NOW()), /*ID 13*/
    ('16:30:00', NOW(), NOW()), /*ID 14*/
    ('17:15:00', NOW(), NOW()), /*ID 15*/
    ('18:00:00', NOW(), NOW()), /*ID 16*/
    ('18:45:00', NOW(), NOW()), /*ID 17*/
    ('19:30:00', NOW(), NOW()), /*ID 18*/
    ('20:15:00', NOW(), NOW()), /*ID 19*/
    ('21:00:00', NOW(), NOW()), /*ID 20*/
    ('21:45:00', NOW(), NOW())  /*ID 21*/
; 
/* 
    reservation
*/
/*
    lunes:     '2024-04-22'
    martes:    '2024-04-23'
    miercoles: '2024-04-24'
    jueves:    '2024-04-25'
    viernes:   '2024-04-26'
    sabado:    '2024-04-27'
    domingo:   '2024-04-28'
*/
insert into reservations (quantity, `repeat`, observation, priority, `date`, parent_id, verified, configuration_flag, academic_period_id, reservation_reason_id, reservation_status_id, created_at, updated_at)
VALUES
    (125, 7, 'Clases', 0, '2024-04-23', 1, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 2, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 3, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 4, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 5, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 6, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 7, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 8, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-25', 9, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-22', 10, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-24', 11, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-24', 12, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-24', 13, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-25', 14, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 15, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 16, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 17, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 18, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 19, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 20, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-24', 21, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-25', 22, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-26', 23, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-24', 24, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 25, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-27', 26, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 27, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 28, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 29, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-25', 30, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-26', 31, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-26', 32, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-23', 33, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 34, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-25', 35, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-23', 36, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-24', 37, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 38, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-22', 39, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-24', 40, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-26', 41, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-23', 42, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-24', 43, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-26', 44, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-24', 45, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 46, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-27', 47, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-24', 48, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 49, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-27', 50, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-23', 51, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-24', 52, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 53, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 54, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 55, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-25', 56, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 57, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 58, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 59, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 60, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 61, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 62, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-25', 63, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-26', 64, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-27', 65, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-22', 66, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-23', 67, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 68, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-23', 69, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-24', 70, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-26', 71, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-24', 72, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-24', 73, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 74, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-23', 75, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-24', 76, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-26', 77, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-22', 78, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-24', 79, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 80, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-23', 81, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-24', 82, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-25', 83, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Practica', 0, '2024-04-22', 84, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-23', 85, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-25', 86, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 87, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 88, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 89, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 90, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 91, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 92, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 93, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 94, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 95, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 96, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 97, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 98, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 99, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-22', 100, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-23', 101, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-25', 102, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 103, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 104, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases',   0, '2024-04-22', 105, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases',   0, '2024-04-23', 106, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Practica', 0, '2024-04-25', 107, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 108, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 109, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 110, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 111, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 112, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 113, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 114, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-27', 115, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 116, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 117, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 118, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 119, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 120, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-27', 121, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 122, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 123, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 124, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 125, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 126, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 127, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 128, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 129, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 130, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 131, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 132, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 133, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-24', 134, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 135, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 136, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 137, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 138, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 139, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 140, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 141, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 142, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 143, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 144, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 145, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 146, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 147, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 148, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 149, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 150, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 151, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 152, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 153, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 154, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 155, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 156, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 157, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 158, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 159, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-27', 160, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 161, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 162, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 163, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-24', 164, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 165, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 166, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 167, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 168, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 169, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 170, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 171, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 172, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 173, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 174, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 175, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 176, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 177, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 178, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 179, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 180, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 181, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 182, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 183, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 184, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 185, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 186, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 187, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 188, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 189, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 190, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 191, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 192, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 193, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 194, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 195, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 196, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 197, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 198, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 199, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-24', 200, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 201, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 202, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 203, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 204, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 205, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 206, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 207, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 208, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 209, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 210, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 211, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 212, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 213, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 214, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 215, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 216, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 217, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 218, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 219, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 220, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 221, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-26', 222, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-27', 223, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 224, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 225, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 226, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 227, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 228, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 229, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 230, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 231, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 232, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 233, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 234, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 235, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 236, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 237, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-24', 238, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 239, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 240, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 241, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 242, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-26', 243, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 244, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 245, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 246, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-23', 247, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 248, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 249, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 250, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-25', 251, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-23', 252, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 253, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 254, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 255, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-27', 256, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-27', 257, 1, 1, 1, 2, 1, NOW(), NOW()),         /*2 INGLES I - GRUPO 1*/

    (125, 7, 'Clases', 0, '2024-04-22', 258, 1, 1, 1, 2, 1, NOW(), NOW()),         /*1 INGLES I - GRUPO 1*/
    (125, 7, 'Clases', 0, '2024-04-24', 259, 1, 1, 1, 2, 1, NOW(), NOW())         /*2 INGLES I - GRUPO 1*/
;
/*
    time_slot_reservations
*/
insert into reservation_time_slot (reservation_id, time_slot_id, created_at, updated_at)
values
    (1, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (1, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (2, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (2, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (3, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (3, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (4, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (4, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (5, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (5, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (6, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (6, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (7, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (7, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (8, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (8, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (9, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (9, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (10, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (10, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (11, 9,  NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (11, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (12, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (12, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (13, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (13, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (14, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (14, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (15, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (15, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (16, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (16, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (17, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (17, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (18, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (18, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (19, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (19, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (20, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (20, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (21, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (21, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (22, 9,  NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (22, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (23, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (23, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (24, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (24, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (25, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (25, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (26, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (26, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (27, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (27, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (28, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (28, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (29, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (29, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (30, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (30, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (31, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (31, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (32, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (32, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (33, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (33, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (34, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (34, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (35, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (35, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (36, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (36, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (37, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (37, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (38, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (38, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (39, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (39, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (40, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (40, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (41, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (41, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (42, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (42, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (43, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (43, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (44, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (44, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (45, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (45, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (46, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (46, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (47, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (47, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (48, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (48, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (49, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (49, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (50, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (50, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (51, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (51, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (52, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (52, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (53, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (53, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (54, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (54, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (55, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (55, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (56, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (56, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (57, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (57, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (58, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (58, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (59, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (59, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (60, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (60, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (61, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (61, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (62, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (62, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (63, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (63, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (64, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (64, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (65, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (65, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (66, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (66, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (67, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (67, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (68, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (68, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (69, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (69, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (70, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (70, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (71, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (71, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (72, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (72, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (73, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (73, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (74, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (74, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (75, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (75, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (76, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (76, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (77, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (77, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (78, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (78, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (79, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (79, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (80, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (80, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (81, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (81, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (82, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (82, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (83, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (83, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (84, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (84, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (85, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (85, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (86, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (86, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (87, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (87, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (88, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (88, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (89, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (89, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (90, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (90, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (91, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (91, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (92, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (92, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (93, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (93, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (94, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (94, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (95, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (95, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (96, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (96, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (97, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (97, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (98, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (98, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (99, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (99, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (100, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (100, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (101, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (101, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (102, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (102, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (103, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (103, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (104, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (104, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (105, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (105, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (106, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (106, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (107, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (107, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (108, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (108, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (109, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (109, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (110, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (110, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (111, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (111, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (112, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (112, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (113, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (113, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (114, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (114, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (115, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (115, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (116, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (116, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (117, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (117, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (118, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (118, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (119, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (119, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (120, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (120, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (121, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (121, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (122, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (122, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (123, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (123, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (124, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (124, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (125, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (125, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (126, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (126, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (127, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (127, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (128, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (128, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (129, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (129, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (130, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (130, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (131, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (131, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (132, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (132, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (133, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (133, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (134, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (134, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (135, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (135, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (136, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (136, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (137, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (137, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (138, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (138, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (139, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (139, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (140, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (140, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (141, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (141, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (142, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (142, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (143, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (143, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (144, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (144, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (145, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (145, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (146, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (146, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (147, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (147, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (148, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (148, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (149, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (149, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (150, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (150, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (151, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (151, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (152, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (152, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (153, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (153, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (154, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (154, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (155, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (155, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (156, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (156, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (157, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (157, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (158, 19, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (158, 20, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (159, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (159, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (160, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (160, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (161, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (161, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (162, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (162, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (163, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (163, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (164, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (164, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (165, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (165, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (166, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (166, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (167, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (167, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (168, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (168, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (169, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (169, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (170, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (170, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (171, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (171, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (172, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (172, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (173, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (173, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (174, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (174, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (175, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (175, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (176, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (176, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (177, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (177, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (178, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (178, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (179, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (179, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (180, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (180, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (181, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (181, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (182, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (182, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (183, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (183, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (184, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (184, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (185, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (185, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (186, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (186, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (187, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (187, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (188, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (188, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (189, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (189, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (190, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (190, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (191, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (191, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (192, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (192, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (193, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (193, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (194, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (194, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (195, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (195, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (196, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (196, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (197, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (197, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (198, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (198, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (199, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (199, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (200, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (200, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (201, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (201, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (202, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (202, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (203, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (203, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (204, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (204, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (205, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (205, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (206, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (206, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (207, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (207, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (208, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (208, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (209, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (209, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (210, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (210, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (211, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (211, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (212, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (212, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (213, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (213, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (214, 13, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (214, 14, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (215, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (215, 2, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (216, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (216, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (217, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (217, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (218, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (218, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (219, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (219, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (220, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (220, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (221, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (221, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (222, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (222, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (223, 17, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (223, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (224, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (224, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (225, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (225, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (226, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (226, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (227, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (227, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (228, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (228, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (229, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (229, 10, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (230, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (230, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (231, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (231, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (232, 15, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (232, 16, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (233, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (233, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (234, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (234, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (235, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (235, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (236, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (236, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (237, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (237, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (238, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (238, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/    

    (239, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (239, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (240, 19, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (240, 20, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (241, 19, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (241, 20, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (242, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (242, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (243, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (243, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (244, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (244, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (245, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (245, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (246, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (246, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (247, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (247, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (248, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (248, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/    
    
    (249, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (249, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (250, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (250, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (251, 11, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (251, 12, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (252, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (252, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (253, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (253, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (254, 3, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (254, 4, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (255, 5, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (255, 6, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (256, 7, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (256, 8, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    
    (257, 9, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (257, 10, NOW(), NOW())              /*INGLES I - GRUPO 1*/
;
/*HASTA AQUI TODO CORRECTO*/
/*
    reservation_classrooms
*/
insert into classroom_reservation (reservation_id, classroom_id, created_at, updated_at)
values 
    (1, 30, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (2, 18, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (3, 12, NOW(), NOW()),              /*INGLES I - GRUPO 2*/
    (4, 12, NOW(), NOW()),              /*INGLES I - GRUPO 2*/

    (5, 33, NOW(), NOW()),              /*INGLES I - GRUPO 3*/
    (6, 28, NOW(), NOW()),              /*INGLES I - GRUPO 3*/

    (7,  3, NOW(), NOW()),              /*INGLES II - GRUPO 1*/
    (8, 35, NOW(), NOW()),              /*INGLES II - GRUPO 1*/

    ( 9, 16, NOW(), NOW()),              /*INGLES II - GRUPO 2*/
    (10, 17, NOW(), NOW()),              /*INGLES II - GRUPO 2*/

    (11, 15, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/
    (12, 19, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/
    (13,  8, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/

    (14, 14, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 2*/
    (15, 16, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 2*/
    (16, 14, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 2*/

    (17, 34, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 3*/
    (18, 34, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 3*/
    (19, 34, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 3*/

    (20, 4, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 4*/
    (21, 4, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 4*/
    (22, 4, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 4*/

    (23, 10, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 5*/
    (24,  4, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 5*/
    (25, 17, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 5*/

    (26, 24, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/
    (27, 19, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/
    (28, 35, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/

    (29, 11, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/
    (30, 25, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/
    (31, 20, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/

    (32, 16, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/
    (33, 15, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/
    (34, 16, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/

    (35,  4, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/
    (36,  4, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/
    (37, 11, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/

    (38, 34, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/
    (39, 34, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/
    (40, 35, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/

    (41, 35, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/
    (42, 17, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/
    (43, 11, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/

    (44, 16, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 5*/
    (45, 38, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 5*/
    (46, 32, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 5*/

    (47,  5, NOW(), NOW()),             /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 1*/
    (48, 12, NOW(), NOW()),             /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 1*/

    (49, 16, NOW(), NOW()),             /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 2*/
    (50, 38, NOW(), NOW()),             /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 2*/

    (51, 17, NOW(), NOW()),             /*ALGORITMOS AVANZADOS - GRUPO 1*/
    (52, 12, NOW(), NOW()),             /*ALGORITMOS AVANZADOS - GRUPO 1*/
    (53, 32, NOW(), NOW()),             /*ALGORITMOS AVANZADOS - GRUPO 1*/

    (54, 12, NOW(), NOW()),             /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 1*/
    (55, 13, NOW(), NOW()),             /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 1*/

    (56, 36, NOW(), NOW()),             /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 2*/
    (57,  1, NOW(), NOW()),             /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 2*/

    /*------------------------------------------------------------------------------*/

    (58, 5, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1, 6*/
    (58, 8, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1, 6*/
    (58, 14, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 1, 6*/
    
    (59, 20, NOW(), NOW()),             /*CALCULO I - GRUPO 1*/
    
    (60, 28, NOW(), NOW()),             /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 1*/
    
    (61, 33, NOW(), NOW()),             /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1, 2, 3*/
    (62, 33, NOW(), NOW()),             /*FISICA GENERAL - GRUPO 1, 2*/
    (63, 33, NOW(), NOW()),             /*INTRODUCCION A LA PROGRAMACION - GRUPO 7, 10*/
    
    (64, 35, NOW(), NOW()),             /*INGLES II - GRUPO 1, 2*/
    
    (65, 38, NOW(), NOW()),             /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 2*/

    /*------------------------------------------------------------------------------*/

    (66, 9, NOW(), NOW()),              /*CALCULO NUMERICO - GRUPO 1*/
    (66, 10, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/
    (66, 11, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/
    (66, 12, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/

    (67, 3, NOW(), NOW()),              /*CALCULO NUMERICO - GRUPO 1*/

    (68, 29, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/
    (68, 30, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/
    (68, 31, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/

    (69, 32, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/
    (69, 33, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/

    (70, 9, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/
    (70, 10, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/
    (70, 11, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/
    (70, 12, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/

    (71, 3, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/

    (72, 29, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/
    (72, 30, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/
    (72, 31, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/

    (73, 32, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/
    (73, 33, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/

    (74, 26, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/

    (75, 36, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (75, 37, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (76, 21, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (76, 24, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (76, 28, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (77, 34, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (78, 36, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (79, 8, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (80, 36, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 2*/
    (80, 37, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 2*/

    (81, 21, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 2*/
    (81, 24, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 2*/
    (81, 28, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 2*/

    (82, 34, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/
    (82, 35, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/

    (83, 11, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/
    (83, 12, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/
    (83, 13, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/
    (83, 15, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/

    (84, 33, NOW(), NOW()),             /*BASE DE DATOS II - GRUPO 1*/

    (85, 29, NOW(), NOW()),             /*BASE DE DATOS II - GRUPO 1*/
    (85, 30, NOW(), NOW()),             /*BASE DE DATOS II - GRUPO 1*/

    (86, 33, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    (87, 29, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (87, 30, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    (88, 35, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (88, 36, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    (89, 11, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (89, 12, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    /*------------------------------------------------------------------------------*/

    (90, 9, NOW(), NOW()),              /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (90, 10, NOW(), NOW()),             /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    (91, 15, NOW(), NOW()),             /*BASE DE DATOS II - GRUPO 1*/
    (91, 16, NOW(), NOW()),             /*BASE DE DATOS II - GRUPO 1*/

    (92, 13, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/
    (92, 15, NOW(), NOW()),             /*BASE DE DATOS I - GRUPO 1*/

    (93, 21, NOW(), NOW()),             /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (94, 26, NOW(), NOW()),             /*TEORIA DE GRAFOS - GRUPO 1*/

    (95, 27, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/
    (95, 28, NOW(), NOW()),             /*CALCULO NUMERICO - GRUPO 1*/

    /*RESERVACION ESPECIAL POR DIA SE RESERVAN LOS AMBIENTES*/

    (96, 9, NOW(), NOW()),              /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 10, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 11, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 12, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 13, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 14, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 15, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 16, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 17, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 18, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 19, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 20, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 21, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 22, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 23, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 24, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 25, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 26, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 27, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 28, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 29, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 30, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 31, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 32, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (96, 33, NOW(), NOW()),             /*96  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (97, 9, NOW(), NOW()),              /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 10, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 11, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 12, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 13, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 14, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 15, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 16, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 17, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 18, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 19, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 20, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 21, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 22, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 23, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 24, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 25, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 26, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 27, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 28, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 29, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 30, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 31, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 32, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (97, 33, NOW(), NOW()),             /*97  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (98, 9, NOW(), NOW()),              /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 10, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 11, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 12, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 13, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 14, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 15, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 16, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 17, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 18, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 19, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 20, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 21, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 22, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 23, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 24, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 25, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 26, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 27, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 28, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 29, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 30, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 31, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 32, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (98, 33, NOW(), NOW()),             /*98  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (99, 9, NOW(), NOW()),              /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 10, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 11, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 12, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 13, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 14, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 15, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 16, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 17, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 18, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 19, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 20, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 21, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 22, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 23, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 24, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 25, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 26, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 27, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 28, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 29, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 30, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 31, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 32, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (99, 33, NOW(), NOW()),             /*99  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (100, 9, NOW(), NOW()),             /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 10, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 11, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 12, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 13, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 14, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 15, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 16, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 17, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 18, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 19, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 20, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 21, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 22, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 23, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 24, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 25, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 26, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 27, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 28, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 29, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 30, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 31, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 32, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (100, 33, NOW(), NOW()),            /*100  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (101, 9, NOW(), NOW()),             /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 10, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 11, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 12, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 13, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 14, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 15, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 16, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 17, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 18, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 19, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 20, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 21, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 22, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 23, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 24, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 25, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 26, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 27, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 28, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 29, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 30, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 31, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 32, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (101, 33, NOW(), NOW()),            /*101  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (102, 9, NOW(), NOW()),             /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 10, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 11, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 12, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 13, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 14, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 15, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 16, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 17, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 18, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 19, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 20, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 21, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 22, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 23, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 24, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 25, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 26, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 27, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 28, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 29, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 30, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 31, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 32, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (102, 33, NOW(), NOW()),            /*102  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (103, 9, NOW(), NOW()),             /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 10, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 11, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 12, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 13, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 14, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 15, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 16, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 17, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 18, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 19, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 20, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 21, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 22, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 23, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 24, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 25, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 26, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 27, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 28, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 29, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 30, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 31, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 32, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (103, 33, NOW(), NOW()),            /*103  RESERVA ESPECIAL - EXAMEN DE INGRESO*/

    (104, 9, NOW(), NOW()),             /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 10, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 11, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 12, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 13, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 14, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 15, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 16, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 17, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 18, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 19, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 20, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 21, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 22, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 23, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 24, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 25, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 26, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 27, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 28, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 29, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 30, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 31, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 32, NOW(), NOW()),            /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
    (104, 33, NOW(), NOW())             /*104  RESERVA ESPECIAL - EXAMEN DE INGRESO*/
; 
/*
	reservation_teacher_subject
*/
insert into reservation_teacher_subject (reservation_id, teacher_subject_id, created_at, updated_at)
values 
    (1, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/
    (2, 1, NOW(), NOW()),              /*INGLES I - GRUPO 1*/

    (3, 2, NOW(), NOW()),              /*INGLES I - GRUPO 2*/
    (4, 2, NOW(), NOW()),              /*INGLES I - GRUPO 2*/

    (5, 3, NOW(), NOW()),              /*INGLES I - GRUPO 3*/
    (6, 3, NOW(), NOW()),              /*INGLES I - GRUPO 3*/

    (7, 4, NOW(), NOW()),              /*INGLES II - GRUPO 1*/
    (8, 4, NOW(), NOW()),              /*INGLES II - GRUPO 1*/

    (9, 5, NOW(), NOW()),              /*INGLES II - GRUPO 2*/
    (10, 5, NOW(), NOW()),              /*INGLES II - GRUPO 2*/

    (11, 6, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/
    (12, 6, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/
    (13, 6, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/

    (14, 7, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 2*/
    (15, 7, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 2*/
    (16, 7, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 2*/

    (17, 8, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 3*/
    (18, 8, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 3*/
    (19, 8, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 3*/

    (20, 9, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 4*/
    (21, 9, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 4*/
    (22, 9, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 4*/

    (23, 10, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 5*/
    (24, 10, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 5*/
    (25, 10, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 5*/

    (26, 11, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/
    (27, 11, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/
    (28, 11, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/

    (29, 12, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/
    (30, 12, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/
    (31, 12, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/

    (32, 13, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/
    (33, 13, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/
    (34, 13, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/

    (35, 14, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/
    (36, 14, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/
    (37, 14, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/

    (38, 15, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/
    (39, 15, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/
    (40, 15, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/

    (41, 16, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/
    (42, 16, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/
    (43, 16, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/

    (44, 17, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 5*/
    (45, 17, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 5*/
    (46, 17, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 5*/

    (47, 18, NOW(), NOW()),              /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 1*/
    (48, 18, NOW(), NOW()),              /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 1*/

    (49, 19, NOW(), NOW()),              /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 2*/
    (50, 19, NOW(), NOW()),              /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 2*/

    (51, 20, NOW(), NOW()),              /*ALGORITMOS AVANZADOS - GRUPO 1*/
    (52, 20, NOW(), NOW()),              /*ALGORITMOS AVANZADOS - GRUPO 1*/
    (53, 20, NOW(), NOW()),              /*ALGORITMOS AVANZADOS - GRUPO 1*/

    (54, 21, NOW(), NOW()),              /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 1*/
    (55, 21, NOW(), NOW()),              /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 1*/

    (56, 22, NOW(), NOW()),              /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 2*/
    (57, 22, NOW(), NOW()),              /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 2*/

    /*-------------------------------------------------------------------------------*/

    (58, 6, NOW(), NOW()),               /*INTRODUCCION A LA PROGRAMACION - GRUPO 1*/
    (58, 11, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 6*/
    
    (59, 23, NOW(), NOW()),              /*CALCULO I - GRUPO 1*/
        
    (60, 18, NOW(), NOW()),              /*ARQUITECTURA DE COMPUTADORAS I - GRUPO 1*/
    
    (61, 14, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 1*/
    (61, 15, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 2*/
    (61, 16, NOW(), NOW()),              /*ELEM. DE PROGRAMACION Y ESTRUC. DE DATOS - GRUPO 3*/
    
    (62, 24, NOW(), NOW()),              /*FISICA GENERAL - GRUPO 1*/
    (62, 25, NOW(), NOW()),              /*FISICA GENERAL - GRUPO 2*/
    
    (63, 12, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 7*/
    (63, 13, NOW(), NOW()),              /*INTRODUCCION A LA PROGRAMACION - GRUPO 10*/
    
    (64, 4, NOW(), NOW()),               /*INGLES II - GRUPO 1*/
    (64, 5, NOW(), NOW()),               /*INGLES II - GRUPO 2*/
    
    (65, 22, NOW(), NOW()),              /*TALLER DE INGENIERIA DE SOFTWARE - GRUPO 2*/

    /*-------------------------------------------------------------------------------*/

    (66, 26, NOW(), NOW()),              /*CALCULO NUMERICO - GRUPO 1*/
    (67, 26, NOW(), NOW()),              /*CALCULO NUMERICO - GRUPO 1*/
    (68, 26, NOW(), NOW()),              /*CALCULO NUMERICO - GRUPO 1*/
    (69, 26, NOW(), NOW()),              /*CALCULO NUMERICO - GRUPO 1*/

    (70, 27, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/
    (71, 27, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/
    (72, 27, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/
    (73, 27, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/
    (74, 27, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/

    (75, 28, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (76, 28, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (77, 28, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (78, 28, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/
    (79, 28, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (80, 29, NOW(), NOW()),              /*BASE DE DATOS I - GRUPO 2*/
    (81, 29, NOW(), NOW()),              /*BASE DE DATOS I - GRUPO 2*/
    (82, 30, NOW(), NOW()),              /*BASE DE DATOS I - GRUPO 1*/
    (83, 30, NOW(), NOW()),              /*BASE DE DATOS I - GRUPO 1*/

    (84, 31, NOW(), NOW()),              /*BASE DE DATOS II - GRUPO 1*/
    (85, 31, NOW(), NOW()),              /*BASE DE DATOS II - GRUPO 1*/

    (86, 32, NOW(), NOW()),              /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (87, 32, NOW(), NOW()),              /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (88, 32, NOW(), NOW()),              /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/
    (89, 32, NOW(), NOW()),              /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    /*-------------------------------------------------------------------------------*/

    (90, 32, NOW(), NOW()),              /*INTELIGENCIA ARTIFICIAL I - GRUPO 1*/

    (91, 31, NOW(), NOW()),              /*BASE DE DATOS II - GRUPO 1*/

    (92, 30, NOW(), NOW()),              /*BASE DE DATOS I - GRUPO 1*/

    (93, 28, NOW(), NOW()),              /*PROGRAMACION FUNCIONAL - GRUPO 1*/

    (94, 27, NOW(), NOW()),              /*TEORIA DE GRAFOS - GRUPO 1*/

    (95, 26, NOW(), NOW())               /*CALCULO NUMERICO - GRUPO 1*/

    /*LAS RESERVACIONES ESPECIALES NO CUENTAN CON UN "reservation_teacher_subject"*/
; 