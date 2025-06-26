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
    'hierarchical_navigation' => false, // Navegação simples - só categorias
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
    'hierarchical_navigation' => true, // Navegação hierárquica - categorias > subcategorias
    'course_prices' => array(
      26 => array( // Técnico em Enfermagem 
          'price' => 2500.00,
          'duration' => '18 meses',
          'installments' => '18x de R$ 138,89',
      ),
      27 => array( // Técnico em Eletromecânica 
          'price' => 2500.00,
          'duration' => '18 meses',
          'installments' => '18x de R$ 138,89',
      ),
      28 => array( // Técnico em Eletrotécnica 
          'price' => 2500.00,
          'duration' => '18 meses',
          'installments' => '18x de R$ 138,89',
      ),
      29 => array( // Técnico em Segurança do Trabalho
          'price' => 2500.00,
          'duration' => '18 meses',
          'installments' => '18x de R$ 138,89',
      ),
      33 => array( // NR'S 
          'price' => 2500.00,
          'duration' => '18 meses',
          'installments' => '18x de R$ 138,89',
      ),
      'default' => array(
          'price' => 1500.00,
          'duration' => '12 meses',
          'installments' => '12x de R$ 125,00',
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
    'hierarchical_navigation' => false, // Navegação simples - só categorias
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
    'hierarchical_navigation' => false, // Navegação simples - só categorias
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