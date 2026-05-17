<?php

use App\Http\Controllers\Familia\FamilyFinancialController;
use App\Http\Controllers\Familia\FamilyHubController;
use App\Http\Controllers\Familia\MinhaCaminhadaController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\GuardianshipController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Secretaria\AccessProfileController;
use App\Http\Controllers\Secretaria\DepartmentController;
use App\Http\Controllers\Secretaria\DepartmentPersonController;
use App\Http\Controllers\Secretaria\UserAccessProfileController;
use App\Http\Controllers\SecretaryAlertController;
use App\Http\Controllers\SecretaryDashboardController;
use App\Http\Controllers\SecretaryRequestController;
use App\Http\Controllers\SecretaryUserAccessController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Public/Inicio', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rotas públicas reais do site institucional.
// Cada tela pública renderiza seu próprio componente para evitar navegação por hash/âncora.
Route::get('/inicio', function () {
    return Inertia::render('Public/Inicio', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.inicio');

Route::get('/acompanhar_oracao', function () {
    return Inertia::render('Public/AcompanharOracao', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.acompanhar_oracao');

Route::get('/sobre_nos', function () {
    return Inertia::render('Public/SobreNos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.sobre_nos');

Route::get('/cultos', function () {
    return Inertia::render('Public/Cultos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.cultos');

Route::get('/eventos', function () {
    return Inertia::render('Public/Eventos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.eventos');

Route::get('/contato', function () {
    return Inertia::render('Public/Contato', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('public.contato');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/boas_vindas', function () {
    $user = request()->user();
    $person = $user->person ?? null;
    $fullName = $person->full_name ?? $user->name ?? null;
    $nameParts = $fullName ? preg_split('/\s+/', trim($fullName)) : [];
    $fallbackName = count($nameParts) >= 3
        ? $nameParts[0].' '.$nameParts[count($nameParts) - 1]
        : ($nameParts[0] ?? 'Família Resgate');

    // Futuramente este destino será definido por permissões e perfil do usuário.
    // No cadastro futuro, o ideal é separar first_name, last_name, preferred_name e full_name.
    return Inertia::render('Auth/WelcomeTransition', [
        'destination' => route('familia-resgate.index', absolute: false),
        'greetingName' => $person->preferred_name ?? $user->preferred_name ?? $user->display_name ?? $user->first_name ?? $fallbackName,
    ]);
})->middleware(['auth', 'verified'])->name('welcome.transition');

Route::get('/familia', [FamilyHubController::class, 'index'])
    ->middleware(['auth'])
    ->name('familia.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/familia-resgate', function () {
        $user = request()->user();
        $person = $user->person ?? null;

        return Inertia::render('FamiliaResgate/Index', [
            'greetingName' => $person->preferred_name ?? $person->full_name ?? $user->name ?? 'Família Resgate',
        ]);
    })->name('familia-resgate.index');

    Route::get('/familia-resgate/meu-perfil', fn () => Inertia::render('FamiliaResgate/MeuPerfil'))
        ->name('familia-resgate.meu_perfil');

    Route::get('/familia-resgate/meu-financeiro', [FamilyFinancialController::class, 'index'])
        ->name('familia-resgate.meu_financeiro');

    Route::get('/familia-resgate/minha-caminhada', [MinhaCaminhadaController::class, 'index'])
        ->name('familia-resgate.minha_caminhada');

    Route::get('/familia-resgate/minha-caminhada/conquistas', [MinhaCaminhadaController::class, 'achievements'])
        ->name('familia-resgate.minha_caminhada.conquistas');

    Route::get('/familia-resgate/minha-caminhada/nivel', [MinhaCaminhadaController::class, 'level'])
        ->name('familia-resgate.minha_caminhada.nivel');

    Route::get('/familia-resgate/minha-caminhada/geral', fn () => Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
        'area' => 'geral',
        'journey' => 'geral',
    ]))->name('familia-resgate.minha_caminhada.geral');

    Route::get('/familia-resgate/minha-caminhada/jovem', fn () => Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
        'area' => 'jovem',
        'journey' => 'jovem',
    ]))->name('familia-resgate.minha_caminhada.jovem');

    Route::get('/familia-resgate/minha-caminhada/historico', [MinhaCaminhadaController::class, 'history'])
        ->name('familia-resgate.minha_caminhada.historico');

    Route::get('/familia-resgate/minha-caminhada/mentor', fn () => Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
        'area' => 'mentor',
        'journey' => 'all',
    ]))->name('familia-resgate.minha_caminhada.mentor');

    Route::get('/familia-resgate/minha-caminhada/regras', fn () => Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
        'area' => 'regras',
        'journey' => 'all',
    ]))->name('familia-resgate.minha_caminhada.regras');

    Route::get('/familia-resgate/minha-caminhada/ranking', fn () => Inertia::render('FamiliaResgate/MinhaCaminhadaArea', [
        'area' => 'ranking',
        'journey' => 'all',
    ]))->name('familia-resgate.minha_caminhada.ranking');

    Route::get('/familia-resgate/minha-caminhada/mapa', [MinhaCaminhadaController::class, 'map'])
        ->name('familia-resgate.minha_caminhada.mapa');

    // Acesso futuro: membro comum acessa caminhada geral; jovem/resgatado acessa geral + jovem. A rota jovem deverá ser protegida no backend/policy, pois frontend não é segurança final.
    Route::get('/familia-resgate/minha-caminhada/geral/mapa', [MinhaCaminhadaController::class, 'generalMap'])
        ->name('familia-resgate.minha_caminhada.geral.mapa');

    Route::get('/familia-resgate/minha-caminhada/jovem/mapa', [MinhaCaminhadaController::class, 'youthMap'])
        ->name('familia-resgate.minha_caminhada.jovem.mapa');

    Route::get('/familia-resgate/minha-caminhada/geral/niveis/{level}', [MinhaCaminhadaController::class, 'generalLevel'])
        ->where('level', '[0-9]+')
        ->name('familia-resgate.minha_caminhada.geral.niveis.show');

    Route::get('/familia-resgate/minha-caminhada/jovem/niveis/{level}', [MinhaCaminhadaController::class, 'youthLevel'])
        ->where('level', '[0-9]+')
        ->name('familia-resgate.minha_caminhada.jovem.niveis.show');

    Route::get('/familia-resgate/minha-caminhada/niveis/{level}', [MinhaCaminhadaController::class, 'legacyGeneralLevel'])
        ->where('level', '[0-9]+')
        ->name('familia-resgate.minha_caminhada.niveis.show');

    $financialFamilyAreas = [
        'historico' => 'historico',
        'recibos' => 'recibos',
        'comprovantes' => 'comprovantes',
        'enviar-comprovante' => 'enviar-comprovante',
        'solicitar-correcao' => 'solicitar-correcao',
        'solicitacoes' => 'solicitacoes',
        'cantina' => 'cantina',
        'eventos' => 'eventos',
        'filhos' => 'filhos',
        'pendencias' => 'pendencias',
        'creditos' => 'creditos',
    ];

    foreach ($financialFamilyAreas as $path => $area) {
        Route::get('/familia-resgate/meu-financeiro/'.$path, fn (FamilyFinancialController $controller) => $controller->area(request(), $area))
            ->name('familia-resgate.meu_financeiro.'.str_replace('-', '_', $path));
    }

    Route::get('/familia-resgate/meu-financeiro/filhos/{person}', [FamilyFinancialController::class, 'childDetail'])
        ->name('familia-resgate.meu_financeiro.filhos.show');

    Route::get('/familia-resgate/meu-financeiro/recibos/{receipt}/baixar', [FamilyFinancialController::class, 'downloadReceipt'])
        ->name('familia-resgate.meu_financeiro.recibos.baixar');

    Route::get('/cartao-resgate/validar/{token}', fn (string $token) => Inertia::render('FamiliaResgate/Placeholder', [
        'title' => 'Validar Cartão Resgate',
        'description' => 'Área preparada para validação segura do Cartão Resgate por token não previsível, sem expor dados sensíveis.',
        'icon' => '▣',
    ]))->name('cartao-resgate.validar');

    Route::get('/familia-resgate/cartao-resgate/validar/{token}', fn (string $token) => Inertia::render('FamiliaResgate/Placeholder', [
        'title' => 'Validar Cartão Resgate',
        'description' => 'Área preparada para leitura segura de QR Code, confirmação de vínculo e registro futuro de activity_log.',
        'icon' => '▣',
    ]))->name('familia-resgate.cartao-resgate.validar');

    $familyResgatePages = [
        'meu-perfil/editar' => ['Editar Perfil', 'Área preparada para atualizar informações pessoais do perfil.', '♙'],
        'meu-perfil/alterar-foto' => ['Alterar Foto de Perfil', 'Área preparada para atualizar a foto pessoal do membro.', '▧'],
        'meu-perfil/alterar-senha' => ['Alterar Senha', 'Área preparada para atualizar credenciais de acesso com segurança.', '▥'],
        'meu-perfil/notificacoes' => ['Notificações do Perfil', 'Preferências pessoais de notificações e mensagens.', '♢'],
        'meu-perfil/privacidade' => ['Privacidade do Perfil', 'Preferências de visibilidade, diretório, aniversário e dados pessoais.', '▥'],
        'meu-perfil/baixar-dados' => ['Baixar Meus Dados', 'Área preparada para solicitação de exportação dos dados pessoais.', '☷'],
        'meu-perfil/historico-atividades' => ['Histórico de Atividades', 'Registro das atividades pessoais dentro da Central da Família.', '▤'],
        'meu-perfil/documentos' => ['Documentos do Perfil', 'Documentos pessoais e cadastrais vinculados ao membro.', '☷'],
        'meu-perfil/preferencias' => ['Preferências do Perfil', 'Configurações pessoais de participação, comunicação e visibilidade.', '⚙'],
        'meu-perfil/sobre-mim/editar' => ['Editar Sobre Mim', 'Área preparada para atualizar a apresentação pessoal do membro.', '♙'],
        'meu-perfil/contatos-emergencia' => ['Contatos de Emergência', 'Contatos pessoais autorizados para situações de cuidado e emergência.', '♡'],
        'meu-perfil/contatos-emergencia/novo' => ['Novo Contato de Emergência', 'Área preparada para adicionar contato pessoal de emergência.', '♡'],
        'meu-perfil/cartao-resgate' => ['Meu Cartão Resgate', 'Credencial digital segura do membro na Família Resgate.', '▣'],
        'meu-perfil/cartao-resgate/download' => ['Baixar Cartão Resgate', 'Área preparada para gerar download seguro do cartão em PDF ou PNG.', '▣'],
        'meu-perfil/cartao-resgate/pdf' => ['Cartão Resgate em PDF', 'Área preparada para geração futura do cartão digital em PDF.', '▣'],
        'meu-perfil/cartao-resgate/png' => ['Cartão Resgate em PNG', 'Área preparada para geração futura do cartão digital em PNG.', '▣'],
        'meu-perfil/cartao-resgate/qrcode' => ['QR Code do Cartão Resgate', 'Área preparada para validação segura por QR Code.', '▣'],
        'minha-caminhada/presencas' => ['Minhas Presenças', 'Histórico pessoal de presenças em cultos, encontros e eventos.', '▤'],
        'escalas/meus-servicos' => ['Meus Serviços', 'Escalas e serviços pessoais em ministérios e equipes.', '✦'],
        'documentos/manual-do-membro' => ['Manual do Membro', 'Direitos, deveres e orientações da Família Resgate.', '☷'],
        'privacidade' => ['Política de Privacidade', 'Como seus dados são tratados e protegidos no ecossistema.', '▥'],
        'codigo-conduta' => ['Código de Conduta', 'Princípios que orientam convivência, serviço e cuidado.', '✦'],
        'fale-com-lideranca' => ['Fale com a Liderança', 'Canal preparado para contato com liderança e cuidado pastoral.', '♡'],
        'ajuda' => ['Ajuda e Suporte', 'Orientações e suporte para uso da Central da Família.', '?'],
        'minha-caminhada/pontuacao' => ['Sistema de Pontuação', 'Explicação dos critérios de XP, frequência, serviço e leitura bíblica.', '▤'],
        'minha-caminhada/destaques/mensal' => ['Membro Destaque do Mês', 'Reconhecimento mensal de frutos, constância, serviço, devoção e comunhão saudável.', '♕'],
        'minha-caminhada/regras-de-pontos' => ['Regras de Pontuação', 'Critérios, pesos e camadas de pontuação da caminhada espiritual.', '▤'],
        'centro-sabedoria' => ['Centro da Sabedoria', 'Bíblia, devocionais, planos de leitura e perguntas da jornada bíblica.', '▣'],
        'centro-sabedoria/biblia' => ['Bíblia Online', 'Área preparada para leitura bíblica em diferentes versões.', '▣'],
        'centro-sabedoria/devocionais' => ['Devocionais', 'Devocionais guiados para fortalecer sua vida com Deus.', '◌'],
        'centro-sabedoria/devocionais/hoje' => ['Devocional de Hoje', 'Mensagem diária para meditação e prática espiritual.', '◌'],
        'centro-sabedoria/perguntas-da-leitura' => ['Perguntas da Leitura', 'Perguntas para aprofundar sua compreensão bíblica.', '?'],
        'centro-sabedoria/leitura-biblica' => ['Minha Leitura Bíblica', 'Progresso dos seus planos e histórico de leitura.', '▤'],
        'centro-sabedoria/leitura-biblica/destaques/mensal' => ['Destaque em Leitura do Mês', 'Reconhecimento mensal de constância, progresso e amor pela Palavra.', '▣'],
        'centro-sabedoria/versiculo-do-dia' => ['Versículo do Dia', 'Palavra diária em destaque para edificação pessoal.', '✦'],
        'rede-fe' => ['Rede de Fé', 'Conexões espirituais, discipulado e acompanhamento comunitário.', '∞'],
        'oracoes' => ['Orações', 'Pedidos, respostas e intercessões da família espiritual.', '♡'],
        'oracoes/novo-pedido' => ['Novo Pedido de Oração', 'Formulário reservado para registrar pedidos de oração.', '♡'],
        'oracoes/respostas' => ['Respostas de Oração', 'Testemunhos e respostas recebidas nos pedidos de oração.', '♡'],
        'grupos-ministerios' => ['Grupos e Ministérios', 'Participação em grupos, ministérios e equipes de serviço.', '♧'],
        'grupos-ministerios/meus-grupos' => ['Meus Grupos', 'Resumo dos grupos e ministérios dos quais você participa.', '♧'],
        'grupos-ministerios/ministerios' => ['Ministérios', 'Lista de ministérios, equipes de serviço e oportunidades de participação.', '♧'],
        'grupos-ministerios/jovens' => ['Jovens', 'Área dos jovens com grupos, encontros e acompanhamento.', '♧'],
        'grupos-ministerios/louvor' => ['Louvor', 'Área do ministério de louvor, equipes e encontros.', '♪'],
        'calendario' => ['Calendário', 'Eventos, cultos, reuniões e compromissos da Família Resgate.', '▤'],
        'calendario/proximo-culto' => ['Próximo Culto', 'Informações do próximo culto e orientações de participação.', '▤'],
        'calendario/eventos/ensaio-louvor' => ['Ensaio de Louvor', 'Detalhes do ensaio de louvor reservado na agenda.', '♪'],
        'calendario/eventos/reuniao-lideres' => ['Reunião de Líderes', 'Detalhes da próxima reunião de líderes.', '✦'],
        'calendario/eventos/mutirao-limpeza' => ['Mutirão de Limpeza', 'Detalhes do mutirão de cuidado com a casa de Deus.', '✦'],
        'cultos/confirmar-presenca' => ['Confirmar Presença', 'Confirmação de presença para o próximo culto.', '✓'],
        'cultos/registrar-biblia' => ['Registrar Bíblia', 'Registro de presença com Bíblia e participação em culto.', '✚'],
        'obreiros-servicos' => ['Obreiros e Serviços', 'Escalas, equipes e oportunidades de servir.', '✦'],
        'galeria' => ['Galeria da Família', 'Memórias, fotos e registros dos momentos da igreja.', '▧'],
        'galeria/fotos' => ['Fotos da Galeria', 'Fotos recentes e momentos registrados da Família Resgate.', '▧'],
        'galeria/eventos' => ['Fotos de Eventos', 'Registros dos cultos, eventos e encontros da família da igreja.', '▧'],
        'destaques-semana' => ['Destaques da Semana', 'Cultos, eventos e comunicados visuais em destaque na semana.', '✦'],
        'destaques-semana/cultos' => ['Cultos em Destaque', 'Flyers e comunicados aprovados dos cultos da semana.', '✦'],
        'destaques-semana/eventos' => ['Eventos em Destaque', 'Flyers e comunicados aprovados dos eventos da semana.', '✦'],
        'destaques-semana/comunicados' => ['Comunicados em Destaque', 'Comunicados visuais aprovados pela Administração Geral.', '✦'],
        'minhas-solicitacoes' => ['Minhas Solicitações', 'Pedidos administrativos e acompanhamentos pessoais.', '☷'],
        'rede-oportunidades' => ['Rede de Oportunidades', 'Oportunidades de serviço, apoio e crescimento.', '◇'],
        'notificacoes' => ['Notificações', 'Avisos, lembretes e comunicados importantes.', '♢'],
        'notificacoes/vigilia-oracao' => ['Vigília de Oração', 'Comunicado sobre a próxima vigília de oração.', '♢'],
        'notificacoes/curso-discipulado' => ['Curso de Discipulado', 'Comunicado sobre inscrições do curso de discipulado.', '♢'],
        'notificacoes/congresso-jovens' => ['Congresso de Jovens', 'Comunicado sobre o congresso de jovens.', '♢'],
        'mensagens' => ['Mensagens', 'Central de mensagens pessoais e comunicados internos.', '☷'],
        'busca' => ['Busca', 'Busca geral dentro do Centro da Família Resgate.', '⌕'],
        'minha-familia' => ['Minha Família', 'Vínculos familiares, membros e atualizações cadastrais.', '♡'],
        'minha-familia/membros' => ['Membros da Família', 'Lista de membros vinculados à sua família.', '♡'],
        'minha-familia/solicitar-alteracao' => ['Solicitar Alteração Familiar', 'Área preparada para solicitar atualização cadastral familiar.', '♡'],
        'aniversariantes' => ['Aniversariantes da Família', 'Celebração das vidas da família da igreja, respeitando privacidade e vínculos familiares.', '♡'],
        'aniversariantes/hoje' => ['Aniversariante do Dia', 'Detalhes do aniversariante do dia e ações de cuidado familiar.', '♡'],
        'aniversariantes/mes' => ['Aniversariantes do Mês', 'Lista dos aniversariantes do mês conforme permissões de privacidade.', '♡'],
        'aniversariantes/hoje/enviar-carinho' => ['Enviar Carinho', 'Área preparada para enviar uma mensagem de felicitação ao aniversariante.', '♡'],
        'documentos' => ['Documentos', 'Declarações, recibos e documentos disponíveis para você.', '☷'],
        'acessos-privados' => ['Acessos Privados', 'Lista de áreas restritas liberadas por perfil e permissão.', '▥'],
        'configuracoes' => ['Configurações', 'Preferências de conta, notificações e privacidade.', '⚙'],
        'lideranca' => ['Liderança', 'Área preparada para liderança, acompanhamento e futuras permissões ministeriais.', '♕'],
    ];

    foreach ($familyResgatePages as $path => [$title, $description, $icon]) {
        Route::get('/familia-resgate/'.$path, fn () => Inertia::render('FamiliaResgate/Placeholder', [
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
        ]))->name('familia-resgate.'.str_replace(['/', '-'], ['.', '_'], $path));
    }

    $privateAreas = [
        'secretaria' => ['Secretaria', 'Entrada segura para rotinas administrativas da secretaria.', '▦'],
        'centro-pastoral' => ['Centro Pastoral', 'Entrada segura para acompanhamento pastoral e cuidado espiritual.', '♡'],
        'financeiro' => ['Financeiro', 'Entrada segura para gestão financeira autorizada.', '◈'],
        'cantina' => ['Cantina', 'Entrada segura para gestão de cantina e consumos.', '▤'],
        'intercessao' => ['Intercessão', 'Entrada segura para equipes de oração e intercessão.', '♢'],
        'admin-geral' => ['Administração Geral', 'Entrada segura para administração geral do ecossistema.', '♙'],
    ];

    foreach ($privateAreas as $path => [$title, $description, $icon]) {
        Route::get('/acesso/'.$path, fn () => Inertia::render('Acessos/AreaAccessGate', [
            'title' => $title,
            'description' => $description,
            'icon' => $icon,
        ]))->name('acesso.'.str_replace('-', '_', $path));
    }
});

Route::get('/secretaria', [SecretaryDashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('secretaria.dashboard');

// Rotas para Alertas da Secretaria - Etapa 5
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/alertas')->name('secretaria.alerts.')->middleware(['auth'])->group(function () {
    // Listar todos os alertas
    Route::get('/', [SecretaryAlertController::class, 'index'])->name('index');

    // Mostrar detalhes de um alerta específico
    Route::get('/{systemAlert}', [SecretaryAlertController::class, 'show'])->name('show');

    // Mostrar tela de resolução/tratamento do alerta
    Route::get('/{systemAlert}/resolver', [SecretaryAlertController::class, 'resolveShow'])->name('resolve.show');

    // Marcar alerta como em andamento
    Route::patch('/{systemAlert}/em-andamento', [SecretaryAlertController::class, 'markInProgress'])->name('mark-in-progress');

    // Verificar se o alerta foi realmente resolvido
    Route::post('/{systemAlert}/verificar-resolucao', [SecretaryAlertController::class, 'verifyResolution'])->name('verify-resolution');

    // Marcar alerta como ignorado
    Route::patch('/{systemAlert}/ignorar', [SecretaryAlertController::class, 'ignore'])->name('ignore');

    // Regenerar alertas com base nas regras atuais
    Route::post('/regenerar', [SecretaryAlertController::class, 'regenerate'])->name('regenerate');
});

// Rotas para Solicitações da Secretaria - Etapa 6
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/solicitacoes')->name('secretaria.requests.')->middleware(['auth'])->group(function () {
    // Listar todas as solicitações
    Route::get('/', [SecretaryRequestController::class, 'index'])->name('index');

    // Mostrar formulário de criação
    Route::get('/criar', [SecretaryRequestController::class, 'create'])->name('create');

    // Salvar nova solicitação
    Route::post('/', [SecretaryRequestController::class, 'store'])->name('store');

    // Mostrar detalhes de uma solicitação
    Route::get('/{secretaryRequest}', [SecretaryRequestController::class, 'show'])->name('show');

    // Mostrar formulário de edição
    Route::get('/{secretaryRequest}/editar', [SecretaryRequestController::class, 'edit'])->name('edit');

    // Atualizar solicitação
    Route::put('/{secretaryRequest}', [SecretaryRequestController::class, 'update'])->name('update');
    Route::patch('/{secretaryRequest}', [SecretaryRequestController::class, 'update'])->name('update');

    // Marcar solicitação como em análise
    Route::patch('/{secretaryRequest}/em-analise', [SecretaryRequestController::class, 'markInReview'])->name('mark-in-review');

    // Aprovar solicitação
    Route::patch('/{secretaryRequest}/aprovar', [SecretaryRequestController::class, 'approve'])->name('approve');

    // Rejeitar solicitação
    Route::patch('/{secretaryRequest}/rejeitar', [SecretaryRequestController::class, 'reject'])->name('reject');

    // Concluir solicitação
    Route::patch('/{secretaryRequest}/concluir', [SecretaryRequestController::class, 'complete'])->name('complete');

    // Cancelar solicitação
    Route::patch('/{secretaryRequest}/cancelar', [SecretaryRequestController::class, 'cancel'])->name('cancel');
});

// Rotas para Acessos do Sistema - Etapa 7
// Todas as rotas exigem autenticação para segurança
Route::prefix('secretaria/acessos')->name('secretaria.access.')->middleware(['auth'])->group(function () {
    // Verificar elegibilidade de pessoa (deve vir antes da rota dinâmica)
    Route::get('/elegibilidade/{person}', [SecretaryUserAccessController::class, 'eligibility'])->name('eligibility');

    // Listar todos os acessos
    Route::get('/', [SecretaryUserAccessController::class, 'index'])->name('index');

    // Mostrar formulário para criar novo acesso
    Route::get('/criar', [SecretaryUserAccessController::class, 'create'])->name('create');

    // Salvar novo acesso
    Route::post('/', [SecretaryUserAccessController::class, 'store'])->name('store');

    // Mostrar detalhes de um acesso
    Route::get('/{user}', [SecretaryUserAccessController::class, 'show'])->name('show');

    // Mostrar formulário para editar acesso
    Route::get('/{user}/editar', [SecretaryUserAccessController::class, 'edit'])->name('edit');

    // Atualizar acesso
    Route::put('/{user}', [SecretaryUserAccessController::class, 'update'])->name('update');

    // Suspender acesso
    Route::patch('/{user}/suspender', [SecretaryUserAccessController::class, 'suspend'])->name('suspend');

    // Reativar acesso
    Route::patch('/{user}/reativar', [SecretaryUserAccessController::class, 'reactivate'])->name('reactivate');

    // Gerenciar perfis de acesso de um usuário
    Route::get('/{user}/perfis', [UserAccessProfileController::class, 'edit'])->name('perfis.edit');
    Route::put('/{user}/perfis', [UserAccessProfileController::class, 'update'])->name('perfis.update');
});

// Rotas para Perfis de Acesso - Secretaria - Etapa 8
Route::prefix('secretaria/perfis-acesso')->name('secretaria.access-profiles.')->middleware(['auth'])->group(function () {
    Route::get('/', [AccessProfileController::class, 'index'])->name('index');
    Route::get('/criar', [AccessProfileController::class, 'create'])->name('create');
    Route::post('/', [AccessProfileController::class, 'store'])->name('store');
    Route::get('/{accessProfile}', [AccessProfileController::class, 'show'])->name('show');
    Route::get('/{accessProfile}/editar', [AccessProfileController::class, 'edit'])->name('edit');
    Route::put('/{accessProfile}', [AccessProfileController::class, 'update'])->name('update');
    Route::delete('/{accessProfile}', [AccessProfileController::class, 'destroy'])->name('destroy');
});

// Rotas para Departamentos - Secretaria - Etapa 10
Route::prefix('secretaria/departamentos')->name('secretaria.departments.')->middleware(['auth'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index'])->name('index');
    Route::get('/criar', [DepartmentController::class, 'create'])->name('create');
    Route::post('/', [DepartmentController::class, 'store'])->name('store');
    Route::get('/{department}', [DepartmentController::class, 'show'])->name('show');
    Route::get('/{department}/editar', [DepartmentController::class, 'edit'])->name('edit');
    Route::put('/{department}', [DepartmentController::class, 'update'])->name('update');
    Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('destroy');

    // Rotas para vínculos pessoa-departamento
    Route::post('/{department}/pessoas', [DepartmentPersonController::class, 'store'])->name('people.store');
    Route::put('/{department}/pessoas/{departmentPerson}', [DepartmentPersonController::class, 'update'])->name('people.update');
    Route::delete('/{department}/pessoas/{departmentPerson}', [DepartmentPersonController::class, 'destroy'])->name('people.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para CRUD de Pessoas - Módulo Secretaria - Fase 2.1
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('people')->name('people.')->group(function () {
        // Buscar pessoas por nome para autocomplete (deve vir antes das rotas dinâmicas)
        Route::get('/search', [PersonController::class, 'search'])->name('search');

        // Listar todas as pessoas
        Route::get('/', [PersonController::class, 'index'])->name('index');

        // Mostrar formulário para criar nova pessoa
        Route::get('/create', [PersonController::class, 'create'])->name('create');

        // Salvar nova pessoa
        Route::post('/', [PersonController::class, 'store'])->name('store');

        // Mostrar detalhes de uma pessoa específica
        Route::get('/{person}', [PersonController::class, 'show'])->name('show');

        // Mostrar formulário para editar pessoa existente
        Route::get('/{person}/edit', [PersonController::class, 'edit'])->name('edit');

        // Atualizar pessoa existente
        Route::put('/{person}', [PersonController::class, 'update'])->name('update');

        // Remover pessoa (soft delete)
        Route::delete('/{person}', [PersonController::class, 'destroy'])->name('destroy');
    });

    // Rotas para busca de usuários - Autocomplete (protegidas por autenticação)
    Route::prefix('users')->name('users.')->group(function () {
        // Buscar usuários por nome para autocomplete
        Route::get('/search', [UserController::class, 'search'])->name('search');
    });

    // Rotas para CRUD de Famílias - Módulo Secretaria - Etapa 2
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('families')->name('families.')->group(function () {
        // Listar todas as famílias
        Route::get('/', [FamilyController::class, 'index'])->name('index');

        // Mostrar formulário para criar nova família
        Route::get('/create', [FamilyController::class, 'create'])->name('create');

        // Salvar nova família
        Route::post('/', [FamilyController::class, 'store'])->name('store');

        // Mostrar detalhes de uma família específica
        Route::get('/{family}', [FamilyController::class, 'show'])->name('show');

        // Mostrar formulário para editar família existente
        Route::get('/{family}/edit', [FamilyController::class, 'edit'])->name('edit');

        // Atualizar família existente
        Route::put('/{family}', [FamilyController::class, 'update'])->name('update');

        // Remover família (soft delete)
        Route::delete('/{family}', [FamilyController::class, 'destroy'])->name('destroy');

        // Vincular pessoa à família
        Route::post('/{family}/attach', [FamilyController::class, 'attachPerson'])->name('attachPerson');

        // Atualizar vínculo de membro
        Route::put('/{family}/members/{member}', [FamilyController::class, 'updateMember'])->name('updateMember');

        // Desvincular pessoa da família (soft detach)
        Route::delete('/{family}/members/{member}', [FamilyController::class, 'detachPerson'])->name('detachPerson');
    });

    // Rotas para CRUD de Responsáveis e Supervisores - Módulo Secretaria - Etapa 3
    // Todas as rotas exigem autenticação para segurança
    Route::prefix('guardianships')->name('guardianships.')->group(function () {
        // Listar todas as responsabilidades
        Route::get('/', [GuardianshipController::class, 'index'])->name('index');

        // Mostrar formulário para criar nova responsabilidade
        Route::get('/create', [GuardianshipController::class, 'create'])->name('create');

        // Salvar nova responsabilidade
        Route::post('/', [GuardianshipController::class, 'store'])->name('store');

        // Mostrar detalhes de uma responsabilidade específica
        Route::get('/{guardianship}', [GuardianshipController::class, 'show'])->name('show');

        // Mostrar formulário para editar responsabilidade existente
        Route::get('/{guardianship}/edit', [GuardianshipController::class, 'edit'])->name('edit');

        // Atualizar responsabilidade existente
        Route::put('/{guardianship}', [GuardianshipController::class, 'update'])->name('update');

        // Encerrar responsabilidade (soft delete com status 'ended')
        Route::delete('/{guardianship}', [GuardianshipController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
