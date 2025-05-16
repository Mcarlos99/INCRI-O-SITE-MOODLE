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
    // Preços específicos para os cursos deste polo
    'course_prices' => array(
      // Técnico em Informática
      '1' => array(
        'price' => 297.00,
        'duration' => '6 meses',
        'installments' => '6x de R$ 49,50'
      ),
      // Técnico em Enfermagem
      '2' => array(
        'price' => 347.00,
        'duration' => '12 meses',
        'installments' => '12x de R$ 28,92'
      ),
      // Técnico em Segurança do Trabalho
      '6' => array(
        'price' => 397.00,
        'duration' => '10 meses',
        'installments' => '10x de R$ 39,70'
      ),
      // Valor padrão para outros cursos deste polo
      'default' => array(
        'price' => 297.00,
        'duration' => '6 meses',
        'installments' => '6x de R$ 49,50'
      )
    )
  ),
  'breu-branco' => 
  array (
    'name' => 'Breu Branco',
    'moodle_url' => 'https://breubranco.imepedu.com.br',
    'api_token' => '0441051a5b5bc8968f3e65ff7d45c3de',
    'description' => 'Polo de Educação Superior de Breu Branco',
    'address' => 'Rua Parauapebas, 145 - Novo Horizonte, Breu Branco - PA',
    // Preços específicos para os cursos deste polo
    'course_prices' => array(
      // Técnico em Enfermagem
      '1' => array(
        'price' => 337.00,
        'duration' => '24 meses',
        'installments' => '24x de R$ 259,00'
      ),
      // Técnico em Segurança do Trabalho
      '6' => array(
        'price' => 387.00,
        'duration' => '18 meses',
        'installments' => '18x de R$ 180,00'
      ),
      // Valor padrão para outros cursos deste polo
      'default' => array(
        'price' => 287.00,
        'duration' => '8 meses',
        'installments' => '6x de R$ 77,77'
      )
    )
  ),
  'igarape-miri' => 
  array (
    'name' => 'Igarapé-Miri',
    'moodle_url' => 'https://igarape.imepedu.com.br',
    'api_token' => '051a62d5f60167246607b195a9630d3b',
    'description' => 'Polo de Educação Superior de Igarapé-Miri',
    'address' => 'Tv. Principal, 890 - Centro, Igarapé-Miri - PA',
    // Preços específicos para os cursos deste polo
    'course_prices' => array(
      // Técnico em Enfermagem
      '1' => array(
        'price' => 277.00,
        'duration' => '6 meses',
        'installments' => '6x de R$ 46,17'
      ),
      // Técnico em Enfermagem
      '2' => array(
        'price' => 327.00,
        'duration' => '12 meses',
        'installments' => '12x de R$ 27,25'
      ),
      // Técnico em Segurança do Trabalho
      '6' => array(
        'price' => 377.00,
        'duration' => '10 meses',
        'installments' => '10x de R$ 37,70'
      ),
      // Valor padrão para outros cursos deste polo
      'default' => array(
        'price' => 01.00,
        'duration' => '6 meses',
        'installments' => '6x de R$ 46,17'
      )
    )
  ),
  'moju' => 
  array (
    'name' => 'Moju',
    'moodle_url' => 'https://moju.imepedu.com.br',
    'api_token' => 'xxx',
    'description' => 'Polo de Educação Superior de Moju',
    'address' => 'Av. das Palmeiras, 123 - Centro, Moju - PA',
    // Preços específicos para os cursos deste polo
    'course_prices' => array(
      // Técnico em Informática
      '1' => array(
        'price' => 267.00,
        'duration' => '6 meses',
        'installments' => '6x de R$ 44,50'
      ),
      // Técnico em Enfermagem
      '2' => array(
        'price' => 317.00,
        'duration' => '12 meses',
        'installments' => '12x de R$ 26,42'
      ),
      // Técnico em Segurança do Trabalho
      '6' => array(
        'price' => 367.00,
        'duration' => '10 meses',
        'installments' => '10x de R$ 36,70'
      ),
      // Valor padrão para outros cursos deste polo
      'default' => array(
        'price' => 267.00,
        'duration' => '6 meses',
        'installments' => '6x de R$ 44,50'
      )
    )
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