<?php
// Configuração dos polos e suas respectivas instâncias Moodle
$POLO_CONFIG = array (
  'tucurui' => 
  array (
    'name' => 'Tucuruí',
    'moodle_url' => 'https://tucurui.imepedu.com.br',
    'api_token' => 'xxx',
    'description' => 'Polo de Educação Superior de Tucuruí',
    'address' => 'Av. Principal, 1234 - Centro, Tucuruí - PA',
    'course_prices' => 
    array (
      1 => 
      array (
        'price' => 1.0,
        'duration' => '6 meses',
        'installments' => '6x de R$ 49,50',
      ),
      'default' => 
      array (
        'price' => 4.0,
        'duration' => '6 meses',
        'installments' => '6x de R$ 49,50',
      ),
    ),
  ),
  'breu-branco' => 
  array (
    'name' => 'Breu Branco',
    'moodle_url' => 'https://breubranco.imepedu.com.br',
    'api_token' => '0441051a5b5bc8968f3e65ff7d45c3de',
    'description' => 'Polo de Educação Superior de Breu Branco',
    'address' => 'Rua Parauapebas, 145 - Novo Horizonte, Breu Branco - PA',
    'course_prices' => 
    array (
      1 => 
      array (
        'price' => 4599.0,
        'duration' => '24 meses',
        'installments' => '24x de R$ 259,00',
      ),
      16 => 
      array (
        'price' => 4599.0,
        'duration' => '24 meses',
        'installments' => '24x de R$ 259,00',
      ),
      21 => 
      array (
        'price' => 4599.0,
        'duration' => '24 meses',
        'installments' => '24x de R$ 259,00',
      ),
      6 => 
      array (
        'price' => 2799.0,
        'duration' => '18 meses',
        'installments' => '18x de R$ 180,00',
      ),
      'default' => 
      array (
        'price' => 287.0,
        'duration' => '8 meses',
        'installments' => '6x de R$ 77,77',
      ),
    ),
  ),
  'igarape-miri' => 
  array (
    'name' => 'Igarapé-Miri',
    'moodle_url' => 'https://igarape.imepedu.com.br',
    'api_token' => '051a62d5f60167246607b195a9630d3b',
    'description' => 'Polo de Educação Superior de Igarapé-Miri',
    'address' => 'Tv. Principal, 890 - Centro, Igarapé-Miri - PA',
    'course_prices' => 
    array (
      1 => 
      array (
        'price' => 5.0,
        'duration' => '6 meses',
        'installments' => '6x de R$ 46,17',
      ),
      'default' => 
      array (
        'price' => 8.0,
        'duration' => '6 meses',
        'installments' => '6x de R$ 46,17',
      ),
    ),
  ),
  'moju' => 
  array (
    'name' => 'Moju',
    'moodle_url' => 'https://moju.imepedu.com.br',
    'api_token' => 'xxx',
    'description' => 'Polo de Educação Superior de Moju',
    'address' => 'Av. das Palmeiras, 123 - Centro, Moju - PA',
    'course_prices' => 
    array (
      1 => 
      array (
        'price' => 9.0,
        'duration' => '6 meses',
        'installments' => '6x de R$ 44,50',
      ),
      'default' => 
      array (
        'price' => 12.0,
        'duration' => '6 meses',
        'installments' => '6x de R$ 44,50',
      ),
    ),
  ),
);

// Configuração padrão para qualquer polo que não tenha preços específicos
$DEFAULT_COURSE_PRICES = array(
  // Valor padrão para todos os cursos em todos os polos que não têm configuração específica
  'default' => array(
    'price' => 297.00,
    'duration' => '6 meses',
    'installments' => '6x de R$ 49,50'
  )
);
?>