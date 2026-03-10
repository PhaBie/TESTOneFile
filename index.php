<?php
/**
 * ═══════════════════════════════════════════════════════════
 *  Snakes & Ladders Quiz Game — Single File Version
 *  All logic, AJAX handlers, styles, and JS in one index.php
 * ═══════════════════════════════════════════════════════════
 */
session_start();

// ════════════════════════════════════════════════════════════
//  DATA — Embedded directly (no external files needed)
// ════════════════════════════════════════════════════════════

$QUESTIONS = [
  ['id'=>1, 'category'=>'grammar',    'question'=>'Choose the correct sentence.',                           'choices'=>['She go to school','She goes to school','She going school','She gone school'],                                        'correct'=>1,'points'=>10],
  ['id'=>2, 'category'=>'grammar',    'question'=>'Which sentence is in the past tense?',                  'choices'=>['I eat lunch','I will eat lunch','I ate lunch','I eating lunch'],                                                      'correct'=>2,'points'=>10],
  ['id'=>3, 'category'=>'grammar',    'question'=>'Select the correct plural form.',                        'choices'=>['childs','childrens','children',"child's"],                                                                           'correct'=>2,'points'=>10],
  ['id'=>4, 'category'=>'vocabulary', 'question'=>"What is the meaning of 'enormous'?",                    'choices'=>['very small','very large','very fast','very slow'],                                                                    'correct'=>1,'points'=>15],
  ['id'=>5, 'category'=>'vocabulary', 'question'=>"Which word means the opposite of 'ancient'?",           'choices'=>['old','modern','historic','antique'],                                                                                  'correct'=>1,'points'=>15],
  ['id'=>6, 'category'=>'vocabulary', 'question'=>"What does 'grateful' mean?",                            'choices'=>['angry','sad','thankful','confused'],                                                                                  'correct'=>2,'points'=>15],
  ['id'=>7, 'category'=>'reading',    'question'=>"What part of speech is the word 'quickly'?",            'choices'=>['noun','adjective','verb','adverb'],                                                                                   'correct'=>3,'points'=>20],
  ['id'=>8, 'category'=>'reading',    'question'=>'Which sentence uses a metaphor?',                       'choices'=>['The dog ran fast','Life is a journey','She ran like the wind','He ate quickly'],                                      'correct'=>1,'points'=>20],
  ['id'=>9, 'category'=>'spelling',   'question'=>'Which word is spelled correctly?',                      'choices'=>['accomodation','accommodation','accomadation','accommadation'],                                                        'correct'=>1,'points'=>10],
  ['id'=>10,'category'=>'spelling',   'question'=>'Which word is spelled correctly?',                      'choices'=>['beleive','belive','believe','beleeve'],                                                                              'correct'=>2,'points'=>10],
  ['id'=>11,'category'=>'grammar',    'question'=>'She ___ to school every day.',                          'choices'=>['go','goes','going','gone'],                                                                                          'correct'=>1,'points'=>10],
  ['id'=>12,'category'=>'grammar',    'question'=>'They ___ watching TV when I arrived.',                  'choices'=>['was','were','are','is'],                                                                                             'correct'=>1,'points'=>15],
  ['id'=>13,'category'=>'vocabulary', 'question'=>"Which word is a synonym for 'happy'?",                  'choices'=>['sad','joyful','angry','tired'],                                                                                      'correct'=>1,'points'=>10],
  ['id'=>14,'category'=>'vocabulary', 'question'=>"What does 'ambiguous' mean?",                           'choices'=>['clear and obvious','having more than one meaning','very important','completely wrong'],                               'correct'=>1,'points'=>20],
  ['id'=>15,'category'=>'grammar',    'question'=>"Which is the correct comparative form of 'good'?",      'choices'=>['gooder','more good','better','best'],                                                                                'correct'=>2,'points'=>10],
  ['id'=>16,'category'=>'reading',    'question'=>"What is a 'protagonist' in a story?",                   'choices'=>['the villain','the main character','a supporting character','the narrator'],                                          'correct'=>1,'points'=>20],
  ['id'=>17,'category'=>'spelling',   'question'=>'Choose the correctly spelled word.',                    'choices'=>['recieve','receive','recive','receeve'],                                                                              'correct'=>1,'points'=>10],
  ['id'=>18,'category'=>'grammar',    'question'=>'I have ___ eaten dinner.',                              'choices'=>['yet','already','still','never'],                                                                                     'correct'=>1,'points'=>15],
  ['id'=>19,'category'=>'vocabulary', 'question'=>"What does 'eloquent' mean?",                            'choices'=>['rude and harsh','well-spoken and expressive','quiet and shy','loud and noisy'],                                      'correct'=>1,'points'=>20],
  ['id'=>20,'category'=>'grammar',    'question'=>'Which sentence uses the correct article?',              'choices'=>['I saw a elephant','I saw an elephant','I saw the elephant yesterday in zoo','I saw elephant'],                       'correct'=>1,'points'=>10],
];

$SNAKES_LADDERS = [
  25  => [
    'ladders' => [['start'=>3,'end'=>11],['start'=>8,'end'=>19],['start'=>15,'end'=>22]],
    'snakes'  => [['start'=>13,'end'=>5],['start'=>20,'end'=>9],['start'=>24,'end'=>16]],
  ],
  36  => [
    'ladders' => [['start'=>3,'end'=>12],['start'=>10,'end'=>25],['start'=>18,'end'=>30]],
    'snakes'  => [['start'=>15,'end'=>6],['start'=>27,'end'=>14],['start'=>34,'end'=>22]],
  ],
  64  => [
    'ladders' => [['start'=>4,'end'=>14],['start'=>9,'end'=>31],['start'=>20,'end'=>38],['start'=>40,'end'=>59],['start'=>51,'end'=>62]],
    'snakes'  => [['start'=>17,'end'=>7],['start'=>35,'end'=>18],['start'=>54,'end'=>33],['start'=>62,'end'=>44],['start'=>63,'end'=>42]],
  ],
  100 => [
    'ladders' => [['start'=>4,'end'=>14],['start'=>9,'end'=>31],['start'=>20,'end'=>38],['start'=>28,'end'=>84],['start'=>40,'end'=>59],['start'=>51,'end'=>67],['start'=>63,'end'=>81],['start'=>71,'end'=>91]],
    'snakes'  => [['start'=>17,'end'=>7],['start'=>54,'end'=>34],['start'=>62,'end'=>19],['start'=>64,'end'=>60],['start'=>87,'end'=>24],['start'=>93,'end'=>73],['start'=>95,'end'=>75],['start'=>99,'end'=>78]],
  ],
];

$BOARD_SIZES = [
  25  => ['grid'=>5,  'label'=>'5×5',   'desc'=>'~15 min', 'emoji'=>'⚡'],
  36  => ['grid'=>6,  'label'=>'6×6',   'desc'=>'~20 min', 'emoji'=>'🎯'],
  64  => ['grid'=>8,  'label'=>'8×8',   'desc'=>'~30 min', 'emoji'=>'🎲'],
  100 => ['grid'=>10, 'label'=>'10×10', 'desc'=>'~45 min', 'emoji'=>'🏆'],
];

$CATS = [
  'grammar'    => ['name'=>'Grammar',    'color'=>'#4caf50', 'icon'=>'📝'],
  'vocabulary' => ['name'=>'Vocabulary', 'color'=>'#2196f3', 'icon'=>'📚'],
  'reading'    => ['name'=>'Reading',    'color'=>'#ff9800', 'icon'=>'📖'],
  'spelling'   => ['name'=>'Spelling',   'color'=>'#9c27b0', 'icon'=>'✏️'],
];

// ── Helpers ────────────────────────────────────────────────────────────────────
function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function rndQ(array $qs): array  { return $qs[array_rand($qs)]; }
function qById(array $qs, int $id): ?array {
  foreach ($qs as $q) if ((int)$q['id'] === $id) return $q;
  return null;
}
function saveHistory(array &$game, bool $early): void {
  if (!isset($_SESSION['history'])) $_SESSION['history'] = [];
  $r = $game['players'];
  usort($r, fn($a,$b) => $b['score'] <=> $a['score']);
  array_unshift($_SESSION['history'], [
    'id'          => uniqid('game_', true),
    'date'        => date('Y-m-d H:i:s'),
    'winner'      => $game['winner'] ?? '—',
    'turns'       => $game['turn'],
    'board_size'  => $game['board_size'] ?? 100,
    'ended_early' => $early,
    'players'     => array_map(fn($p) => [
      'name'=>$p['name'],'score'=>$p['score'],'position'=>$p['position'],
      'color'=>$p['color'],'emoji'=>$p['emoji'],
    ], $r),
  ]);
}

// ════════════════════════════════════════════════════════════
//  AJAX HANDLERS  — all routed via ?action=...
// ════════════════════════════════════════════════════════════
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if ($action) {
  header('Content-Type: application/json');

  if (empty($_SESSION['game'])) {
    echo json_encode(['error' => 'No active game']); exit;
  }

  // ── action=dice ──────────────────────────────────────────────────────────────
  if ($action === 'dice') {
    $roll      = max(1, min(6, (int)($_GET['roll'] ?? 1)));
    $game      = &$_SESSION['game'];
    $boardSize = (int)($game['board_size'] ?? 100);
    $idx       = $game['current_player'];
    $player    = &$game['players'][$idx];
    $sl        = $SNAKES_LADDERS[$boardSize] ?? $SNAKES_LADDERS[100];

    $player['prev_position'] = $player['position'];
    $newPos = $player['position'] + $roll;
    if ($newPos > $boardSize) $newPos = $boardSize - ($newPos - $boardSize);
    $player['position'] = $newPos;

    $landedOn = $newPos; $movedTo = $newPos; $isSnake = false; $isLadder = false;

    foreach ($sl['ladders'] as $l) {
      if ($l['start'] === $newPos) {
        $player['position'] = $l['end']; $movedTo = $l['end']; $isLadder = true; break;
      }
    }
    if (!$isLadder) foreach ($sl['snakes'] as $s) {
      if ($s['start'] === $newPos) {
        $player['position'] = $s['end']; $movedTo = $s['end']; $isSnake = true; break;
      }
    }

    $game['last_move'] = [
      'player_idx' => $idx, 'roll' => $roll,
      'prev_position' => $player['prev_position'],
      'landed_on' => $landedOn, 'moved_to' => $movedTo,
      'is_snake' => $isSnake, 'is_ladder' => $isLadder,
    ];

    $question = (!$isSnake && !$isLadder) ? rndQ($QUESTIONS) : null;
    echo json_encode(['roll'=>$roll,'landed_on'=>$landedOn,'moved_to'=>$movedTo,'snake'=>$isSnake,'ladder'=>$isLadder,'question'=>$question]);
    exit;
  }

  // ── action=question (GET — after snake/ladder) ───────────────────────────────
  if ($action === 'question' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(rndQ($QUESTIONS)); exit;
  }

  // ── action=answer (POST) ─────────────────────────────────────────────────────
  if ($action === 'answer') {
    $game      = &$_SESSION['game'];
    $boardSize = (int)($game['board_size'] ?? 100);
    $idx       = $game['current_player'];
    $skip      = !empty($_POST['skip']);
    $correct   = !$skip && (int)($_POST['correct'] ?? 0) === 1;
    $player    = &$game['players'][$idx];
    $move      = $game['last_move'] ?? [];

    if (!$skip && $correct) {
      $q = qById($QUESTIONS, (int)($_POST['question_id'] ?? 0));
      $player['score'] += $q ? (int)$q['points'] : 10;
    } elseif (!$skip && !$correct) {
      if (isset($move['prev_position'])) $player['position'] = $move['prev_position'];
    }

    // Win check
    $gameOver = false; $winner = null;
    foreach ($game['players'] as $p) {
      if ($p['position'] >= $boardSize) { $gameOver = true; break; }
    }
    if ($gameOver) {
      $top = -1;
      foreach ($game['players'] as $p) {
        if ($p['score'] > $top) { $top = $p['score']; $winner = $p['name']; }
      }
      $game['game_over'] = true; $game['winner'] = $winner;
      saveHistory($game, false);
    } else {
      $n = count($game['players']);
      $game['current_player'] = ($idx + 1) % $n;
      if ($game['current_player'] === 0) $game['turn']++;
    }
    echo json_encode(['game_over'=>$gameOver,'winner'=>$winner,'players'=>$game['players'],'correct'=>$correct]);
    exit;
  }

  // ── action=end_game (POST) ───────────────────────────────────────────────────
  if ($action === 'end_game') {
    $game = &$_SESSION['game'];
    $top = -1; $winner = null;
    foreach ($game['players'] as $p) {
      if ($p['score'] > $top) { $top = $p['score']; $winner = $p['name']; }
    }
    $game['game_over'] = true; $game['winner'] = $winner; $game['ended_early'] = true;
    saveHistory($game, true);
    echo json_encode(['success'=>true,'winner'=>$winner,'players'=>$game['players']]);
    exit;
  }

  echo json_encode(['error' => 'Unknown action']); exit;
}

// ════════════════════════════════════════════════════════════
//  ADMIN PANEL — Auth + CRUD
// ════════════════════════════════════════════════════════════
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'password';
$page       = $_GET['page'] ?? '';
$adminTab   = $_GET['tab']  ?? 'questions';

if ($page === 'admin') {
  // Logout
  if (isset($_GET['logout'])) { unset($_SESSION['admin']); header('Location: '.$_SERVER['PHP_SELF'].'?page=admin'); exit; }

  // Login
  if (!empty($_POST['admin_login'])) {
    if ($_POST['u'] === $ADMIN_USER && $_POST['p'] === $ADMIN_PASS) {
      $_SESSION['admin'] = true;
      header('Location: '.$_SERVER['PHP_SELF'].'?page=admin'); exit;
    }
    $_SESSION['admin_err'] = 'Wrong username or password.';
    header('Location: '.$_SERVER['PHP_SELF'].'?page=admin'); exit;
  }

  // Require login for actions below
  if (!empty($_SESSION['admin']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['questions'])) $_SESSION['questions'] = $QUESTIONS;
    $aa = $_POST['aa'] ?? '';

    if ($aa === 'add') {
      $ids = array_column($_SESSION['questions'], 'id');
      $newId = $ids ? max($ids) + 1 : 1;
      $q = [
        'id'       => $newId,
        'category' => $_POST['cat'] ?? 'grammar',
        'question' => trim($_POST['q'] ?? ''),
        'choices'  => [trim($_POST['c0']??''), trim($_POST['c1']??''), trim($_POST['c2']??''), trim($_POST['c3']??'')],
        'correct'  => (int)($_POST['cor'] ?? 0),
        'points'   => max(5, (int)($_POST['pts'] ?? 10)),
      ];
      if ($q['question'] && $q['choices'][0]) { $_SESSION['questions'][] = $q; $_SESSION['admin_ok'] = 'Question added!'; }
      header('Location: '.$_SERVER['PHP_SELF'].'?page=admin&tab=questions'); exit;
    }
    if ($aa === 'del') {
      $did = (int)($_POST['id'] ?? 0);
      $_SESSION['questions'] = array_values(array_filter($_SESSION['questions'], fn($x) => $x['id'] !== $did));
      $_SESSION['admin_ok'] = 'Question deleted.';
      header('Location: '.$_SERVER['PHP_SELF'].'?page=admin&tab=questions'); exit;
    }
    if ($aa === 'del_hist') {
      $hid = $_POST['id'] ?? '';
      $_SESSION['history'] = array_values(array_filter($_SESSION['history'] ?? [], fn($h) => $h['id'] !== $hid));
      $_SESSION['admin_ok'] = 'Record deleted.';
      header('Location: '.$_SERVER['PHP_SELF'].'?page=admin&tab=history'); exit;
    }
    if ($aa === 'clear_hist') {
      $_SESSION['history'] = [];
      $_SESSION['admin_ok'] = 'History cleared.';
      header('Location: '.$_SERVER['PHP_SELF'].'?page=admin&tab=history'); exit;
    }
  }
}

// Merge session-edited questions into runtime
if (isset($_SESSION['questions'])) $QUESTIONS = $_SESSION['questions'];

// ════════════════════════════════════════════════════════════
//  SETUP FORM — POST handler
// ════════════════════════════════════════════════════════════
if (isset($_GET['reset'])) { unset($_SESSION['game']); header('Location: '.$_SERVER['PHP_SELF']); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$action) {
  $numPlayers = (int)($_POST['num_players'] ?? 2);
  $boardSize  = (int)($_POST['board_size']  ?? 36);
  if (!array_key_exists($boardSize, $BOARD_SIZES)) $boardSize = 36;
  $names = $_POST['player_names'] ?? [];

  if ($numPlayers < 2 || $numPlayers > 6) {
    $error = 'Please select 2–6 players.';
  } else {
    $colors = ['#e74c3c','#3498db','#2ecc71','#f39c12','#9b59b6','#1abc9c'];
    $emojiList = ['🔴','🔵','🟢','🟡','🟣','🩵'];
    $players = [];
    for ($i = 0; $i < $numPlayers; $i++) {
      $name = trim($names[$i] ?? '');
      if (!$name) $name = 'Player '.($i+1);
      $players[] = [
        'name'     => htmlspecialchars($name, ENT_QUOTES),
        'position' => 0, 'score' => 0,
        'color'    => $colors[$i],
        'emoji'    => $emojiList[$i],
      ];
    }
    $_SESSION['game'] = [
      'players'        => $players,
      'current_player' => 0,
      'turn'           => 1,
      'board_size'     => $boardSize,
      'started_at'     => time(),
      'game_over'      => false,
      'winner'         => null,
    ];
    header('Location: '.$_SERVER['PHP_SELF'].'?play=1'); exit;
  }
}

// ════════════════════════════════════════════════════════════
//  WHICH SCREEN TO SHOW?
// ════════════════════════════════════════════════════════════
$showAdmin = ($page === 'admin');
$showGame  = !$showAdmin && isset($_GET['play']) && !empty($_SESSION['game']);

if ($showGame) {
  $game      = &$_SESSION['game'];
  $boardSize = (int)($game['board_size'] ?? 100);
  $gridCols  = $BOARD_SIZES[$boardSize]['grid'] ?? 10;
  $sizeLabel = $BOARD_SIZES[$boardSize]['label'] ?? '';
  $sl        = $SNAKES_LADDERS[$boardSize] ?? $SNAKES_LADDERS[100];
  $ladderMap = []; foreach ($sl['ladders'] as $l) $ladderMap[$l['start']] = $l['end'];
  $snakeMap  = []; foreach ($sl['snakes']  as $s) $snakeMap[$s['start']]  = $s['end'];
  $tileInfo  = [];
  foreach ($ladderMap as $s => $e) $tileInfo[$s] = ['type'=>'ladder','end'=>$e];
  foreach ($snakeMap  as $s => $e) $tileInfo[$s] = ['type'=>'snake', 'end'=>$e];
  $curIdx    = $game['current_player'];
  $curPlayer = $game['players'][$curIdx];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>🐍 Snakes &amp; Ladders Quiz</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
/* ════════════════════════════════════════════════════════════
   BASE RESET & VARIABLES
════════════════════════════════════════════════════════════ */
:root {
  --primary:   #6c63ff;
  --secondary: #43b89c;
  --danger:    #e74c3c;
  --bg:        linear-gradient(135deg,#1a1a2e 0%,#16213e 50%,#0f3460 100%);
  --tile-lt:   #fef9f0;
  --tile-dk:   #fde4b4;
  --tile-lad:  #c8f7c5;
  --tile-snk:  #ffdde1;
  --gold:      #d4a855;
  --r:         12px;
  --sh:        0 4px 20px rgba(0,0,0,.12);
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Nunito','Segoe UI',sans-serif; color: #2d2d2d; font-size: 15px; line-height: 1.5; }

/* ════════════════════════════════════════════════════════════
   SETUP SCREEN
════════════════════════════════════════════════════════════ */
.setup-body {
  background: var(--bg);
  min-height: 100vh;
  display: flex; align-items: center; justify-content: center; padding: 1rem;
}
.setup-card {
  background: #fff; border-radius: 24px; padding: 2.2rem;
  max-width: 480px; width: 100%;
  box-shadow: 0 20px 60px rgba(0,0,0,.22);
}
.logo { text-align: center; margin-bottom: 1.5rem; }
.logo-emoji { font-size: 3rem; display: block; margin-bottom: .25rem; }
.logo h1 { font-family: 'Fredoka One',cursive; font-size: 2.1rem; color: var(--primary); line-height: 1; }
.logo p  { color: #888; font-size: .9rem; margin-top: .3rem; }
.sec-lbl { font-weight: 800; color: #555; font-size: .8rem; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .6rem; }

/* Board size picker */
.size-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: .5rem; margin-bottom: 1.4rem; }
.size-btn {
  border: 2px solid #e0e0e0; border-radius: 12px; background: #fafafa;
  padding: .65rem .3rem; cursor: pointer; text-align: center;
  transition: all .2s; font-family: 'Nunito',sans-serif;
}
.size-btn:hover, .size-btn.active {
  border-color: var(--primary); background: #f0eeff;
  box-shadow: 0 0 0 3px rgba(108,99,255,.15);
}
.sb-emoji { font-size: 1.25rem; display: block; margin-bottom: .2rem; }
.sb-label { font-family: 'Fredoka One',cursive; font-size: .95rem; color: var(--primary); display: block; line-height: 1; }
.sb-tiles { font-size: .68rem; color: #888; font-weight: 700; display: block; margin-top: .1rem; }
.sb-time  { font-size: .65rem; color: #aaa; display: block; margin-top: .1rem; }

/* Player count */
.p-count { display: flex; gap: .5rem; justify-content: center; flex-wrap: wrap; margin-bottom: 1.4rem; }
.cnt-btn {
  width: 46px; height: 46px; border-radius: 50%;
  border: 2px solid var(--primary); background: #fff; color: var(--primary);
  font-weight: 800; font-size: 1.05rem; cursor: pointer;
  transition: all .2s; font-family: 'Nunito',sans-serif;
}
.cnt-btn.active, .cnt-btn:hover { background: var(--primary); color: #fff; }

/* Player name inputs */
.p-inputs { display: flex; flex-direction: column; gap: .55rem; margin-bottom: 1.4rem; }
.p-row { display: flex; align-items: center; gap: .6rem; }
.p-row .em { font-size: 1.25rem; width: 30px; text-align: center; }
.p-row input {
  flex: 1; padding: .6rem .85rem; border: 2px solid #e0e0e0; border-radius: 10px;
  font-family: 'Nunito',sans-serif; font-size: .95rem; transition: border-color .2s;
}
.p-row input:focus { outline: none; border-color: var(--primary); }

.btn-start {
  width: 100%; padding: .95rem;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  color: #fff; border: none; border-radius: 14px;
  font-family: 'Fredoka One',cursive; font-size: 1.25rem;
  cursor: pointer; letter-spacing: .05em;
  box-shadow: 0 4px 15px rgba(108,99,255,.4);
  transition: transform .2s, box-shadow .2s;
}
.btn-start:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(108,99,255,.5); }
.err-msg { background: #fee; border: 1px solid #fcc; color: #c00; border-radius: 8px; padding: .65rem .9rem; margin-bottom: 1rem; font-size: .88rem; }

/* ════════════════════════════════════════════════════════════
   GAME SCREEN
════════════════════════════════════════════════════════════ */
.game-body { background: var(--bg); min-height: 100vh; display: flex; flex-direction: column; }

/* Header */
.g-hdr {
  display: flex; align-items: center; justify-content: space-between;
  padding: .7rem 1.4rem;
  background: rgba(0,0,0,.35); backdrop-filter: blur(8px);
  color: #fff; font-weight: 700;
  border-bottom: 1px solid rgba(255,255,255,.08);
  flex-shrink: 0; flex-wrap: wrap; gap: .3rem;
}
.g-title { font-family: 'Fredoka One',cursive; font-size: 1.35rem; letter-spacing: .03em; }
.g-title small { font-size: .72rem; opacity: .65; font-family: 'Nunito',sans-serif; font-weight: 700; margin-left: .3rem; }
.g-turn { font-size: .88rem; color: rgba(255,255,255,.85); }
.g-btns { display: flex; gap: .4rem; }
.hbtn {
  background: rgba(255,255,255,.12); color: #fff;
  border: 1px solid rgba(255,255,255,.25); border-radius: 8px;
  padding: .38rem .85rem; font-size: .82rem; font-weight: 700;
  cursor: pointer; transition: background .2s;
  font-family: 'Nunito',sans-serif; text-decoration: none;
}
.hbtn:hover { background: rgba(255,255,255,.22); }
.hbtn-end {
  background: rgba(231,76,60,.18) !important;
  border-color: rgba(231,76,60,.5) !important;
  color: #ff8a80 !important;
}
.hbtn-end:hover { background: rgba(231,76,60,.32) !important; }

/* Layout */
.g-layout {
  display: flex; flex: 1; gap: 1rem; padding: 1rem;
  align-items: flex-start; overflow: hidden;
}

/* Board */
.board-wrap { flex: 1; min-width: 0; display: flex; justify-content: center; align-items: flex-start; }
.board {
  display: grid;
  grid-template-columns: repeat(10, 1fr); /* overridden by inline style per game */
  width: min(calc(100vw - 320px), calc(100vh - 80px), 580px);
  aspect-ratio: 1;
  border: 4px solid var(--gold);
  border-radius: 12px; overflow: hidden;
  box-shadow: 0 8px 32px rgba(0,0,0,.5);
}
.tile {
  position: relative; display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  background: var(--tile-lt); border: 1px solid rgba(0,0,0,.07);
  padding: 2px; aspect-ratio: 1; overflow: hidden; transition: transform .15s;
}
.tile:hover { z-index: 2; transform: scale(1.06); }
.td  { background: var(--tile-dk); }
.tl  { background: var(--tile-lad) !important; }
.ts  { background: var(--tile-snk) !important; }
.tile.hl { outline: 3px solid var(--primary); z-index: 3; transform: scale(1.1); box-shadow: 0 0 12px var(--primary); }
.tnum { font-size: clamp(7px,1.1vw,11px); font-weight: 800; color: rgba(0,0,0,.4); line-height: 1; position: absolute; top: 2px; left: 3px; }
.tico { font-size: clamp(10px,1.8vw,18px); line-height: 1; }
.ttgt { font-size: clamp(6px,.9vw,9px); color: rgba(0,0,0,.45); font-weight: 700; }
.tokwrap { display: flex; flex-wrap: wrap; gap: 1px; justify-content: center; position: absolute; bottom: 1px; right: 1px; }
.tok {
  font-size: clamp(8px,1.3vw,13px); border-radius: 50%;
  width: clamp(12px,2vw,20px); height: clamp(12px,2vw,20px);
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 1px 3px rgba(0,0,0,.3);
}

/* Side panel */
.side {
  width: 280px; flex-shrink: 0;
  display: flex; flex-direction: column; gap: .8rem;
  position: sticky; top: 1rem;
  max-height: calc(100vh - 80px); overflow-y: auto;
}
.pcard { background: rgba(255,255,255,.95); border-radius: var(--r); padding: 1rem; box-shadow: var(--sh); }

/* Scoreboard */
.sb h3 { font-family: 'Fredoka One',cursive; font-size: 1.1rem; margin-bottom: .7rem; color: var(--primary); }
.srow { display: flex; align-items: center; gap: .4rem; padding: .4rem .5rem; border-radius: 8px; margin-bottom: .3rem; font-size: .88rem; transition: background .2s; }
.srow.cur { background: rgba(108,99,255,.1); font-weight: 700; }
.sdot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.sname { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.spts { font-weight: 800; color: var(--primary); font-size: .95rem; }
.sposn { font-size: .72rem; color: #999; white-space: nowrap; }

/* Dice */
.dice { text-align: center; }
.dface { font-size: 3.5rem; line-height: 1; margin-bottom: .3rem; display: inline-block; }
.dres  { font-size: .9rem; color: #777; margin-bottom: .8rem; font-weight: 600; }
.btn-roll {
  display: block; width: 100%; padding: .9rem;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  color: #fff; border: none; border-radius: 12px;
  font-family: 'Fredoka One',cursive; font-size: 1.2rem; cursor: pointer;
  letter-spacing: .04em; box-shadow: 0 4px 15px rgba(108,99,255,.4);
  transition: transform .15s, box-shadow .15s, opacity .15s;
}
.btn-roll:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(108,99,255,.5); }
.btn-roll:disabled { opacity: .5; cursor: not-allowed; transform: none; }
.curinfo { margin-top: .8rem; padding: .6rem .8rem; background: #f8f8ff; border-radius: 8px; font-size: .82rem; text-align: left; color: #555; }

/* Legend */
.leg h4 { font-size: .82rem; font-weight: 800; color: #888; margin-bottom: .4rem; text-transform: uppercase; letter-spacing: .06em; }
.leg-item { display: flex; align-items: center; gap: .4rem; font-size: .82rem; color: #555; padding: .2rem 0; }

/* ════════════════════════════════════════════════════════════
   MODALS
════════════════════════════════════════════════════════════ */
.moverlay {
  position: fixed; inset: 0; background: rgba(0,0,0,.65);
  display: flex; align-items: center; justify-content: center;
  z-index: 1000; backdrop-filter: blur(4px);
}
.mcard {
  background: #fff; border-radius: 20px; padding: 2rem;
  max-width: 480px; width: 94%;
  box-shadow: 0 24px 80px rgba(0,0,0,.4);
  transform: scale(.85); opacity: 0;
  transition: transform .25s cubic-bezier(.34,1.56,.64,1), opacity .2s;
}
.mcard.min { transform: scale(1); opacity: 1; }
.moverlay .mcard { transform: scale(1); opacity: 1; } /* non-animated modals */

.mcat {
  display: inline-block; color: #fff; padding: .3rem .8rem; border-radius: 20px;
  font-weight: 800; font-size: .82rem; text-transform: uppercase; letter-spacing: .06em; margin-bottom: .8rem;
}
.mq { font-size: 1.1rem; font-weight: 700; color: #333; margin-bottom: 1.2rem; line-height: 1.45; }
.mchoices { display: flex; flex-direction: column; gap: .5rem; }
.cbtn {
  display: flex; align-items: center; gap: .7rem;
  padding: .65rem 1rem; border: 2px solid #e8e8e8; border-radius: 10px;
  background: #fafafa; cursor: pointer;
  font-family: 'Nunito',sans-serif; font-size: .95rem; font-weight: 600;
  text-align: left; transition: all .15s; color: #333;
}
.cbtn:hover:not(:disabled) { border-color: var(--primary); background: #f0eeff; }
.cbtn.ok { border-color: #2ecc71; background: #e8faf0; color: #1a7a3a; }
.cbtn.ng { border-color: var(--danger); background: #fff0f0; color: var(--danger); }
.cbtn:disabled { cursor: not-allowed; }
.cltr {
  min-width: 28px; height: 28px; border-radius: 50%;
  background: var(--primary); color: #fff;
  display: flex; align-items: center; justify-content: center;
  font-size: .8rem; font-weight: 800; flex-shrink: 0;
}
.mfb { margin-top: 1rem; padding: .7rem 1rem; border-radius: 10px; font-weight: 700; font-size: .95rem; min-height: 44px; }
.fb-ok { background: #e8faf0; color: #1a7a3a; }
.fb-ng { background: #fff0f0; color: var(--danger); }

/* Move modal */
.mmove { text-align: center; }
.mve { font-size: 4rem; margin-bottom: .5rem; }
.mvt { font-size: 1rem; color: #444; line-height: 1.6; margin-bottom: 1.2rem; }
.btn-cont {
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  color: #fff; border: none; border-radius: 10px; padding: .7rem 2rem;
  font-family: 'Fredoka One',cursive; font-size: 1.1rem; cursor: pointer;
}

/* Game Over modal */
.mgo { text-align: center; }
.mgo h2 { font-family: 'Fredoka One',cursive; font-size: 2rem; color: var(--primary); margin: .3rem 0 1rem; }
.fsrow { display: flex; align-items: center; gap: .6rem; padding: .5rem .7rem; border-radius: 8px; margin-bottom: .35rem; background: #f8f8f8; font-weight: 700; }
.fpts  { margin-left: auto; color: var(--primary); font-weight: 800; }
.btn-again {
  display: inline-block; margin-top: 1.2rem;
  background: linear-gradient(135deg,var(--primary),var(--secondary));
  color: #fff; padding: .8rem 2rem; border-radius: 12px;
  font-family: 'Fredoka One',cursive; font-size: 1.2rem;
  text-decoration: none; box-shadow: 0 4px 15px rgba(108,99,255,.4);
}

/* ════════════════════════════════════════════════════════════
   RESPONSIVE
════════════════════════════════════════════════════════════ */
@media (min-width: 1200px) {
  .board { width: min(calc(100vw - 340px), calc(100vh - 80px), 660px); }
  .side  { width: 300px; }
}
@media (max-width: 1024px) and (min-width: 769px) {
  .g-layout { flex-direction: row; align-items: flex-start; padding: .8rem; gap: .8rem; }
  .board-wrap { flex: 1; min-width: 0; }
  .board { width: min(calc(100vw - 260px), calc(100vh - 80px), 480px); }
  .side  { width: 230px; flex-shrink: 0; }
  .g-title { font-size: 1.15rem; }
}
@media (max-width: 768px) {
  .g-hdr { padding: .5rem .75rem; flex-wrap: wrap; gap: .3rem; }
  .g-title { font-size: 1rem; order: 1; }
  .g-turn  { font-size: .75rem; order: 3; width: 100%; text-align: center; color: rgba(255,255,255,.75); padding: .2rem 0; border-top: 1px solid rgba(255,255,255,.08); }
  .g-btns  { order: 2; }
  .hbtn { padding: .3rem .6rem; font-size: .75rem; }
  .g-layout { flex-direction: column; padding: .5rem; gap: .6rem; align-items: center; overflow: visible; }
  .board-wrap { width: 100%; }
  .board { width: 96vw; max-width: 420px; margin: 0 auto; }
  .tnum { font-size: 6px; } .tico { font-size: 10px; } .ttgt { display: none; }
  .tok  { font-size: 9px; width: 14px; height: 14px; }
  .side { width: 100%; position: static; max-height: none; }
  .dface { font-size: 2.8rem; } .btn-roll { font-size: 1.05rem; padding: .8rem; }
  .mcard { padding: 1.3rem; border-radius: 16px; } .mq { font-size: .95rem; }
  .mgo h2 { font-size: 1.6rem; }
  .size-grid { grid-template-columns: repeat(2,1fr); }
}
@media (max-width: 400px) {
  .board { width: 98vw; }
  .mcard { padding: 1rem; margin: .5rem; }
  .cbtn  { font-size: .82rem; padding: .5rem .7rem; }
}
</style>
</head>
<body class="<?= $showGame ? 'game-body' : 'setup-body' ?>">

<body class="<?= $showGame ? 'game-body' : ($showAdmin ? 'admin-body' : 'setup-body') ?>"><?php if ($showAdmin): ?>
<!-- ════════════════════════════════════════════════════════
     ⚙️ ADMIN PANEL
════════════════════════════════════════════════════════ -->
<?php
$adminLoggedIn = !empty($_SESSION['admin']);
$adminOk  = $_SESSION['admin_ok']  ?? ''; unset($_SESSION['admin_ok']);
$adminErr = $_SESSION['admin_err'] ?? ''; unset($_SESSION['admin_err']);
?>
<style>
.admin-body{background:#f0f2f7;min-height:100vh;font-family:'Nunito',sans-serif}
.adm-hdr{background:linear-gradient(135deg,#6c63ff,#43b89c);color:#fff;padding:1rem 1.5rem;display:flex;align-items:center;justify-content:space-between;box-shadow:0 2px 12px rgba(0,0,0,.15)}
.adm-hdr h1{font-family:'Fredoka One',cursive;font-size:1.4rem;letter-spacing:.03em}
.adm-hdr-btns{display:flex;gap:.6rem}
.adm-btn{padding:.4rem .9rem;border-radius:8px;border:1px solid rgba(255,255,255,.35);background:rgba(255,255,255,.15);color:#fff;cursor:pointer;font-family:'Nunito',sans-serif;font-size:.85rem;font-weight:700;text-decoration:none;transition:background .2s}
.adm-btn:hover{background:rgba(255,255,255,.28)}
.adm-btn-red{background:rgba(231,76,60,.25);border-color:rgba(231,76,60,.5);color:#ffcdd2}
.adm-body{max-width:900px;margin:0 auto;padding:1.5rem 1rem}
.adm-tabs{display:flex;gap:.4rem;margin-bottom:1.2rem;flex-wrap:wrap}
.adm-tab{padding:.5rem 1.1rem;border-radius:10px;border:2px solid #ddd;background:#fff;cursor:pointer;font-weight:700;font-size:.88rem;color:#666;transition:all .2s;text-decoration:none}
.adm-tab.active,.adm-tab:hover{border-color:#6c63ff;background:#6c63ff;color:#fff}
.adm-card{background:#fff;border-radius:16px;padding:1.4rem;box-shadow:0 2px 12px rgba(0,0,0,.07);margin-bottom:1.2rem}
.adm-card h2{font-family:'Fredoka One',cursive;color:#6c63ff;font-size:1.15rem;margin-bottom:1rem}
.adm-ok{background:#e8f5e9;border:1px solid #a5d6a7;color:#2e7d32;border-radius:8px;padding:.6rem .9rem;margin-bottom:1rem;font-weight:700;font-size:.88rem}
.adm-err{background:#ffebee;border:1px solid #ef9a9a;color:#c62828;border-radius:8px;padding:.6rem .9rem;margin-bottom:1rem;font-weight:700;font-size:.88rem}
.adm-form{display:flex;flex-direction:column;gap:.6rem}
.adm-row{display:flex;gap:.6rem;flex-wrap:wrap}
.adm-field{display:flex;flex-direction:column;gap:.3rem;flex:1;min-width:140px}
.adm-field label{font-size:.78rem;font-weight:800;color:#888;text-transform:uppercase;letter-spacing:.05em}
.adm-field input,.adm-field select,.adm-field textarea{padding:.55rem .8rem;border:2px solid #e0e0e0;border-radius:9px;font-family:'Nunito',sans-serif;font-size:.92rem;transition:border-color .2s;width:100%}
.adm-field input:focus,.adm-field select:focus,.adm-field textarea:focus{outline:none;border-color:#6c63ff}
.adm-submit{padding:.7rem 1.4rem;background:linear-gradient(135deg,#6c63ff,#43b89c);color:#fff;border:none;border-radius:10px;font-family:'Fredoka One',cursive;font-size:1rem;cursor:pointer;align-self:flex-start;letter-spacing:.03em}
.adm-submit:hover{opacity:.9}
.q-table{width:100%;border-collapse:collapse;font-size:.88rem}
.q-table th{background:#f5f3ff;color:#6c63ff;font-weight:800;padding:.6rem .8rem;text-align:left;font-size:.78rem;text-transform:uppercase;letter-spacing:.05em;border-bottom:2px solid #e8e8e8}
.q-table td{padding:.55rem .8rem;border-bottom:1px solid #f0f0f0;vertical-align:middle}
.q-table tr:hover td{background:#fafafa}
.cat-badge{display:inline-block;padding:.15rem .55rem;border-radius:20px;font-size:.73rem;font-weight:800;color:#fff}
.del-btn{background:#ffebee;color:#e74c3c;border:1px solid #ffcdd2;border-radius:6px;padding:.25rem .6rem;font-size:.78rem;font-weight:700;cursor:pointer;font-family:'Nunito',sans-serif}
.del-btn:hover{background:#e74c3c;color:#fff}
.hist-row{display:flex;align-items:center;gap:.8rem;padding:.7rem 1rem;background:#fafafa;border-radius:10px;margin-bottom:.5rem;font-size:.88rem}
.hist-winner{font-weight:800;color:#6c63ff;flex:1}
.hist-badge{font-size:.72rem;background:#fff3cd;color:#856404;padding:.2rem .5rem;border-radius:6px;font-weight:700}
.login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg)}
.login-card{background:#fff;border-radius:20px;padding:2rem;max-width:360px;width:94%;box-shadow:0 20px 60px rgba(0,0,0,.2)}
.login-card h2{font-family:'Fredoka One',cursive;color:#6c63ff;font-size:1.7rem;text-align:center;margin-bottom:1.2rem}
.login-field{margin-bottom:.8rem}
.login-field label{display:block;font-size:.8rem;font-weight:800;color:#888;text-transform:uppercase;margin-bottom:.3rem}
.login-field input{width:100%;padding:.65rem .9rem;border:2px solid #e0e0e0;border-radius:10px;font-family:'Nunito',sans-serif;font-size:.95rem}
.login-field input:focus{outline:none;border-color:#6c63ff}
.login-btn{width:100%;padding:.85rem;background:linear-gradient(135deg,#6c63ff,#43b89c);color:#fff;border:none;border-radius:12px;font-family:'Fredoka One',cursive;font-size:1.15rem;cursor:pointer;margin-top:.4rem}
@media(max-width:600px){.adm-row{flex-direction:column}.hist-row{flex-wrap:wrap}}
</style>

<?php if (!$adminLoggedIn): ?>
<!-- Login form -->
<div class="login-wrap" style="background:linear-gradient(135deg,#1a1a2e,#16213e,#0f3460)">
  <div class="login-card">
    <?php if ($adminErr): ?><div class="adm-err">⚠️ <?= e($adminErr) ?></div><?php endif; ?>
    <h2>⚙️ Admin Login</h2>
    <form method="POST">
      <input type="hidden" name="admin_login" value="1">
      <div class="login-field"><label>Username</label><input type="text" name="u" placeholder="admin" required autofocus></div>
      <div class="login-field"><label>Password</label><input type="password" name="p" placeholder="••••••••" required></div>
      <button type="submit" class="login-btn">🔑 Login</button>
    </form>
    <div style="text-align:center;margin-top:1rem"><a href="<?= $_SERVER['PHP_SELF'] ?>" style="color:#aaa;font-size:.82rem">← Back to Game</a></div>
  </div>
</div>

<?php else: ?>
<!-- Admin panel -->
<div class="adm-hdr">
  <h1>⚙️ Admin Panel</h1>
  <div class="adm-hdr-btns">
    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="adm-btn">🎮 Game</a>
    <a href="?page=admin&logout=1" class="adm-btn adm-btn-red">🚪 Logout</a>
  </div>
</div>
<div class="adm-body">
  <?php if ($adminOk): ?><div class="adm-ok">✅ <?= e($adminOk) ?></div><?php endif; ?>
  <div class="adm-tabs">
    <a class="adm-tab <?= $adminTab==='questions'?'active':'' ?>" href="?page=admin&tab=questions">📝 Questions (<?= count($QUESTIONS) ?>)</a>
    <a class="adm-tab <?= $adminTab==='history'?'active':'' ?>"   href="?page=admin&tab=history">📊 History (<?= count($_SESSION['history'] ?? []) ?>)</a>
  </div>

  <?php if ($adminTab === 'questions'): ?>
  <!-- ── Add Question ── -->
  <div class="adm-card">
    <h2>➕ Add New Question</h2>
    <form method="POST" class="adm-form">
      <input type="hidden" name="aa" value="add">
      <div class="adm-row">
        <div class="adm-field" style="flex:3">
          <label>Question</label>
          <textarea name="q" rows="2" required placeholder="Type your question here..."></textarea>
        </div>
        <div class="adm-field" style="flex:1">
          <label>Category</label>
          <select name="cat">
            <?php foreach ($CATS as $k=>$c): ?>
            <option value="<?= $k ?>"><?= $c['icon'].' '.$c['name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="adm-field" style="flex:0 0 70px">
          <label>Points</label>
          <input type="number" name="pts" value="10" min="5" max="50">
        </div>
      </div>
      <div class="adm-row">
        <?php foreach (['A'=>'c0','B'=>'c1','C'=>'c2','D'=>'c3'] as $lbl=>$nm): ?>
        <div class="adm-field">
          <label>Choice <?= $lbl ?></label>
          <input type="text" name="<?= $nm ?>" required placeholder="Option <?= $lbl ?>">
        </div>
        <?php endforeach; ?>
      </div>
      <div class="adm-field" style="max-width:160px">
        <label>Correct Answer</label>
        <select name="cor">
          <option value="0">A</option><option value="1">B</option>
          <option value="2">C</option><option value="3">D</option>
        </select>
      </div>
      <button type="submit" class="adm-submit">➕ Add Question</button>
    </form>
  </div>
  <!-- ── Question List ── -->
  <div class="adm-card">
    <h2>📋 All Questions</h2>
    <?php if (!$QUESTIONS): ?><p style="color:#aaa">No questions yet.</p>
    <?php else: ?>
    <div style="overflow-x:auto">
    <table class="q-table">
      <thead><tr><th>#</th><th>Category</th><th>Question</th><th>Pts</th><th>Correct</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($QUESTIONS as $q):
        $cat = $CATS[$q['category']] ?? ['name'=>$q['category'],'color'=>'#999','icon'=>''];
      ?>
      <tr>
        <td style="color:#aaa;font-weight:700"><?= $q['id'] ?></td>
        <td><span class="cat-badge" style="background:<?= $cat['color'] ?>"><?= $cat['icon'].' '.$cat['name'] ?></span></td>
        <td><?= e($q['question']) ?></td>
        <td style="font-weight:800;color:#6c63ff"><?= $q['points'] ?></td>
        <td><strong><?= ['A','B','C','D'][(int)$q['correct']] ?></strong>: <?= e($q['choices'][(int)$q['correct']] ?? '') ?></td>
        <td>
          <form method="POST" style="display:inline" onsubmit="return confirm('Delete this question?')">
            <input type="hidden" name="aa" value="del">
            <input type="hidden" name="id" value="<?= $q['id'] ?>">
            <button class="del-btn" type="submit">🗑️</button>
          </form>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
    <?php endif; ?>
  </div>

  <?php elseif ($adminTab === 'history'): ?>
  <!-- ── History ── -->
  <div class="adm-card">
    <h2>📊 Game History</h2>
    <?php $hist = $_SESSION['history'] ?? []; ?>
    <?php if ($hist): ?>
    <form method="POST" style="margin-bottom:1rem" onsubmit="return confirm('Clear ALL history?')">
      <input type="hidden" name="aa" value="clear_hist">
      <button class="adm-btn adm-btn-red" type="submit" style="font-size:.82rem;padding:.4rem .8rem">🗑️ Clear All History</button>
    </form>
    <?php foreach ($hist as $h): ?>
    <div class="hist-row">
      <div>
        <div style="font-size:.75rem;color:#aaa"><?= e($h['date']) ?> &nbsp;|&nbsp; <?= $h['board_size'] ?? '?' ?> tiles &nbsp;|&nbsp; <?= $h['turns'] ?> turns</div>
        <div class="hist-winner">🏆 <?= e($h['winner']) ?></div>
        <div style="font-size:.78rem;color:#888;margin-top:.2rem">
          <?php foreach ($h['players'] as $i=>$p): ?>
          <?= $p['emoji'] ?> <?= e($p['name']) ?> <strong><?= $p['score'] ?>pt</strong><?= $i<count($h['players'])-1 ? ' &nbsp;·&nbsp; ':'' ?>
          <?php endforeach; ?>
        </div>
      </div>
      <?php if (!empty($h['ended_early'])): ?><span class="hist-badge">Ended Early</span><?php endif; ?>
      <form method="POST" style="margin-left:auto" onsubmit="return confirm('Delete this record?')">
        <input type="hidden" name="aa" value="del_hist">
        <input type="hidden" name="id" value="<?= e($h['id']) ?>">
        <button class="del-btn" type="submit">🗑️</button>
      </form>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p style="color:#aaa;text-align:center;padding:2rem 0">No game history yet. Play a game first!</p>
    <?php endif; ?>
  </div>
  <?php endif; ?>

</div><!-- /adm-body -->
<?php endif; // admin logged in ?>

<?php elseif ($showGame): ?>
<!-- ════════════════════════════════════════════════════════
     🎮 GAME SCREEN
════════════════════════════════════════════════════════ -->
<header class="g-hdr">
  <div class="g-title">🐍 Snakes &amp; Ladders <small><?= e($sizeLabel) ?></small></div>
  <div class="g-turn">
    Turn <?= $game['turn'] ?> &nbsp;|&nbsp;
    <span style="color:<?= $curPlayer['color'] ?>"><?= e($curPlayer['emoji'].' '.$curPlayer['name']) ?>'s turn</span>
  </div>
  <div class="g-btns">
    <button class="hbtn hbtn-end" onclick="confirmEnd()">🏁 End Game</button>
    <a href="?reset=1" class="hbtn" onclick="return confirm('Start a new game?')">🔄 New Game</a>
  </div>
</header>

<div class="g-layout">

  <!-- Board -->
  <div class="board-wrap">
    <div class="board" id="board" style="grid-template-columns:repeat(<?= $gridCols ?>,1fr)">
      <?php
      for ($row = 0; $row < $gridCols; $row++):
        $even = ($row % 2 === 0);
        for ($col = 0; $col < $gridCols; $col++):
          $base   = ($gridCols - 1) - $row;
          $num    = $base * $gridCols + ($even ? ($gridCols - $col) : ($col + 1));
          $info   = $tileInfo[$num] ?? null;
          $cls    = 'tile';
          $icon   = '';
          if ($info) {
            if ($info['type'] === 'ladder') { $cls .= ' tl'; $icon = '🪜'; }
            else                            { $cls .= ' ts'; $icon = '🐍'; }
          }
          if (($row + $col) % 2 === 0) $cls .= ' td';
          $here = [];
          foreach ($game['players'] as $p) if ($p['position'] === $num) $here[] = $p;
      ?>
      <div class="<?= $cls ?>" id="tile-<?= $num ?>">
        <span class="tnum"><?= $num ?></span>
        <?php if ($icon): ?><span class="tico"><?= $icon ?></span><?php endif; ?>
        <?php if ($info): ?><span class="ttgt">→<?= $info['end'] ?></span><?php endif; ?>
        <div class="tokwrap">
          <?php foreach ($here as $p): ?>
            <span class="tok" style="background:<?= $p['color'] ?>" title="<?= e($p['name']) ?>"><?= $p['emoji'] ?></span>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endfor; endfor; ?>
    </div>
  </div>

  <!-- Side panel -->
  <aside class="side">

    <!-- Scoreboard -->
    <div class="pcard sb">
      <h3>🏆 Scoreboard</h3>
      <?php
      $ranked = $game['players'];
      usort($ranked, fn($a,$b) => $b['score'] <=> $a['score']);
      $medals = ['🥇','🥈','🥉','4️⃣','5️⃣','6️⃣'];
      ?>
      <?php foreach ($ranked as $ri => $p): ?>
      <div class="srow <?= $p['name'] === $curPlayer['name'] ? 'cur' : '' ?>">
        <span><?= $medals[$ri] ?></span>
        <span class="sdot" style="background:<?= $p['color'] ?>"></span>
        <span class="sname"><?= e($p['name']) ?></span>
        <span class="spts"><?= $p['score'] ?>pt</span>
        <span class="sposn">Tile <?= $p['position'] ?: 'Start' ?></span>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Dice -->
    <div class="pcard dice">
      <div class="dface" id="dface">🎲</div>
      <div class="dres"  id="dres">Roll to play!</div>
      <button class="btn-roll" id="rollBtn" onclick="rollDice()">🎲 ROLL DICE</button>
      <div class="curinfo" style="border-left:4px solid <?= $curPlayer['color'] ?>">
        <?= $curPlayer['emoji'] ?> <strong><?= e($curPlayer['name']) ?></strong><br>
        <small>Tile <?= $curPlayer['position'] ?: 'Start' ?> &nbsp;|&nbsp; <?= $curPlayer['score'] ?>pt</small>
      </div>
    </div>

    <!-- Legend -->
    <div class="pcard leg">
      <h4>Legend</h4>
      <div class="leg-item"><span>🪜</span> Ladder — move forward</div>
      <div class="leg-item"><span>🐍</span> Snake — move backward</div>
    </div>

  </aside>
</div>

<!-- ── Question Modal ─────────────────────────────────── -->
<div class="moverlay" id="qModal" style="display:none">
  <div class="mcard" id="qCard">
    <div class="mcat" id="qCat"></div>
    <div class="mq"   id="qText">Loading...</div>
    <div class="mchoices" id="qChoices"></div>
    <div class="mfb"  id="qFb"></div>
  </div>
</div>

<!-- ── Move Modal (snake / ladder) ────────────────────── -->
<div class="moverlay" id="mvModal" style="display:none">
  <div class="mcard mmove">
    <div class="mve" id="mvE">🎲</div>
    <div class="mvt" id="mvT"></div>
    <button class="btn-cont" onclick="closeMv()">Continue ▶</button>
  </div>
</div>

<!-- ── Game Over Modal ────────────────────────────────── -->
<div class="moverlay" id="goModal" style="display:none">
  <div class="mcard mgo">
    <div id="goE" style="font-size:3rem">🏆</div>
    <h2 id="goTitle">Game Over!</h2>
    <p  id="goSub" style="color:#888;font-size:.9rem;margin-bottom:.8rem"></p>
    <div id="goScores"></div>
    <a href="?reset=1" class="btn-again">🎮 Play Again</a>
  </div>
</div>

<!-- ── Confirm End Modal ──────────────────────────────── -->
<div class="moverlay" id="ceModal" style="display:none">
  <div class="mcard" style="text-align:center;max-width:380px">
    <div style="font-size:3rem;margin-bottom:.5rem">🏁</div>
    <h2 style="font-family:'Fredoka One',cursive;color:#e74c3c;margin-bottom:.5rem">End Game Early?</h2>
    <p style="color:#666;font-size:.95rem;margin-bottom:1.4rem">
      Game will end now and scores saved.<br>
      <strong>Winner = highest score so far.</strong>
    </p>
    <div style="display:flex;gap:.7rem;justify-content:center">
      <button class="btn-cont" style="background:#e74c3c" onclick="doEnd()">🏁 Yes, End</button>
      <button class="btn-cont" style="background:#aaa"    onclick="document.getElementById('ceModal').style.display='none'">Cancel</button>
    </div>
  </div>
</div>

<script>
// ── Constants ────────────────────────────────────────────────
const SELF       = '<?= $_SERVER['PHP_SELF'] ?>';
const BOARD_SIZE = <?= $boardSize ?>;
const CATS_DATA  = <?= json_encode($CATS) ?>;
const SL_DATA    = <?= json_encode($sl) ?>;
const INIT_PL    = <?= json_encode($game['players']) ?>;
const DICE_FACES = ['⚀','⚁','⚂','⚃','⚄','⚅'];
let gameOver     = <?= $game['game_over'] ? 'true' : 'false' ?>;
let rolling      = false;

// ── Dice roll ────────────────────────────────────────────────
function rollDice() {
  if (rolling || gameOver) return;
  rolling = true;
  document.getElementById('rollBtn').disabled = true;
  const result = Math.floor(Math.random() * 6) + 1;
  let c = 0;
  const iv = setInterval(() => {
    document.getElementById('dface').textContent = DICE_FACES[Math.floor(Math.random()*6)];
    if (++c >= 12) {
      clearInterval(iv);
      document.getElementById('dface').textContent = DICE_FACES[result - 1];
      document.getElementById('dres').textContent  = `Rolled a ${result}!`;
      setTimeout(() => processRoll(result), 400);
    }
  }, 80);
}

function processRoll(roll) {
  fetch(`${SELF}?action=dice&roll=${roll}`)
    .then(r => r.json())
    .then(d => {
      if (d.landed_on) hlTile(d.landed_on);
      if (d.snake || d.ladder) showMv(d);
      else showQ(d);
    })
    .catch(() => { rolling = false; });
}

// ── Board highlight ──────────────────────────────────────────
function hlTile(n) {
  document.querySelectorAll('.tile').forEach(t => t.classList.remove('hl'));
  const t = document.getElementById('tile-' + n);
  if (t) t.classList.add('hl');
}

// ── Move modal (snake / ladder) ──────────────────────────────
function showMv(d) {
  document.getElementById('mvE').textContent = d.snake ? '🐍' : '🪜';
  document.getElementById('mvT').innerHTML   = d.snake
    ? `<strong>Oh no! A snake!</strong><br>Tile <b>${d.landed_on}</b> → <b>${d.moved_to}</b>`
    : `<strong>Great! A ladder!</strong><br>Tile <b>${d.landed_on}</b> → <b>${d.moved_to}</b>`;
  document.getElementById('mvModal').style.display = 'flex';
}
function closeMv() {
  document.getElementById('mvModal').style.display = 'none';
  fetch(`${SELF}?action=question`)
    .then(r => r.json())
    .then(q => { if (q) dispQ(q); else endTurn(); })
    .catch(() => endTurn());
}

// ── Question display ─────────────────────────────────────────
function showQ(d) { if (d.question) dispQ(d.question); else endTurn(); }

function dispQ(q) {
  const cat = CATS_DATA[q.category] || { name: q.category, color: '#6c63ff', icon: '' };
  const catEl = document.getElementById('qCat');
  catEl.textContent   = (cat.icon || '') + ' ' + cat.name;
  catEl.style.background = cat.color;
  document.getElementById('qText').textContent = q.question;
  document.getElementById('qFb').textContent   = '';
  document.getElementById('qFb').className     = 'mfb';

  const choices = document.getElementById('qChoices');
  choices.innerHTML = '';
  ['A','B','C','D'].forEach((l, i) => {
    const btn = document.createElement('button');
    btn.className = 'cbtn';
    btn.innerHTML = `<span class="cltr">${l}</span>${q.choices[i]}`;
    btn.onclick = () => ansQ(btn, i, q);
    choices.appendChild(btn);
  });
  document.getElementById('qModal').style.display = 'flex';
  setTimeout(() => document.getElementById('qCard').classList.add('min'), 10);
}

function ansQ(btn, idx, q) {
  document.querySelectorAll('.cbtn').forEach(b => b.disabled = true);
  const ok = (idx === parseInt(q.correct));
  document.querySelectorAll('.cbtn').forEach((b, i) => {
    if (i === parseInt(q.correct)) b.classList.add('ok');
    else if (i === idx && !ok)     b.classList.add('ng');
  });
  const fb = document.getElementById('qFb');
  fb.textContent = ok ? `✅ Correct! +${q.points} points` : `❌ Wrong! You move back.`;
  fb.className   = 'mfb ' + (ok ? 'fb-ok' : 'fb-ng');

  fetch(SELF, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=answer&question_id=${q.id}&answer=${idx}&correct=${ok ? 1 : 0}`
  })
  .then(r => r.json())
  .then(res => {
    setTimeout(() => {
      document.getElementById('qModal').style.display = 'none';
      document.getElementById('qCard').classList.remove('min');
      if (res.game_over) showGO(res.players, false);
      else location.reload();
    }, 2000);
  });
}

function endTurn() {
  fetch(SELF, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=answer&skip=1'
  }).then(() => location.reload());
}

// ── Game over display ────────────────────────────────────────
function showGO(players, early) {
  const s = [...players].sort((a, b) => b.score - a.score);
  const m = ['🥇','🥈','🥉','4️⃣','5️⃣','6️⃣'];
  document.getElementById('goScores').innerHTML = s.map((p, i) => `
    <div class="fsrow">
      <span>${m[i]}</span>
      <span style="background:${p.color};border-radius:50%;width:26px;height:26px;display:inline-flex;align-items:center;justify-content:center">${p.emoji}</span>
      <span>${p.name}</span>
      <span class="fpts">${p.score} pts</span>
      <span style="font-size:.8rem;color:#888">Tile ${p.position}</span>
    </div>`).join('');
  document.getElementById('goE').textContent     = early ? '🏁' : '🏆';
  document.getElementById('goTitle').textContent = early ? 'Game Ended!' : 'Game Over!';
  document.getElementById('goSub').textContent   = early
    ? 'Game ended early. Final scores saved!'
    : (s[0]?.name + ' wins with ' + s[0]?.score + ' points!');
  document.getElementById('goModal').style.display = 'flex';
}

// ── End game early ───────────────────────────────────────────
function confirmEnd() {
  if (!gameOver) document.getElementById('ceModal').style.display = 'flex';
}
function doEnd() {
  document.getElementById('ceModal').style.display = 'none';
  gameOver = true;
  document.getElementById('rollBtn').disabled = true;
  ['qModal','mvModal'].forEach(id => document.getElementById(id).style.display = 'none');
  fetch(SELF, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: 'action=end_game'
  })
  .then(r => r.json())
  .then(res => showGO(res.players, true))
  .catch(() => showGO(INIT_PL, true));
}

// Auto-show game over if already finished
if (gameOver) showGO(INIT_PL, false);
</script>

<?php else: ?>
<!-- ════════════════════════════════════════════════════════
     🎯 SETUP SCREEN
════════════════════════════════════════════════════════ -->
<div class="setup-card">
  <div class="logo">
    <span class="logo-emoji">🐍🎲🪜</span>
    <h1>Snakes &amp; Ladders</h1>
    <p>English Learning Quiz Game</p>
  </div>

  <?php if ($error): ?>
  <div class="err-msg">⚠️ <?= e($error) ?></div>
  <?php endif; ?>

  <form method="POST">

    <div class="sec-lbl">🗺️ Board Size</div>
    <div class="size-grid">
      <?php foreach ($BOARD_SIZES as $sz => $info): ?>
      <button type="button"
        class="size-btn <?= $sz === 36 ? 'active' : '' ?>"
        id="sb-<?= $sz ?>" onclick="setSz(<?= $sz ?>)">
        <span class="sb-emoji"><?= $info['emoji'] ?></span>
        <span class="sb-label"><?= $info['label'] ?></span>
        <span class="sb-tiles"><?= $sz ?> tiles</span>
        <span class="sb-time"><?= $info['desc'] ?></span>
      </button>
      <?php endforeach; ?>
    </div>
    <input type="hidden" name="board_size" id="bsInput" value="36">

    <div class="sec-lbl">👥 Number of Players</div>
    <div class="p-count">
      <?php for ($i = 2; $i <= 6; $i++): ?>
      <button type="button" class="cnt-btn <?= $i === 2 ? 'active' : '' ?>"
        onclick="setP(<?= $i ?>)"><?= $i ?></button>
      <?php endfor; ?>
    </div>
    <input type="hidden" name="num_players" id="npInput" value="2">

    <div class="sec-lbl">✏️ Player Names</div>
    <div class="p-inputs">
      <?php $emList = ['🔴','🔵','🟢','🟡','🟣','🩵']; for ($i = 0; $i < 6; $i++): ?>
      <div class="p-row" id="pr<?= $i ?>" <?= $i >= 2 ? 'style="display:none"' : '' ?>>
        <span class="em"><?= $emList[$i] ?></span>
        <input type="text" name="player_names[]" placeholder="Player <?= $i+1 ?>" maxlength="20">
      </div>
      <?php endfor; ?>
    </div>

    <button type="submit" class="btn-start">🎲 START GAME</button>
  </form>
  <div style="text-align:center;margin-top:1rem">
    <a href="/index.php?page=admin" style="color:#bbb;font-size:.82rem;text-decoration:none;transition:color .2s"
       onmouseover="this.style.color='#6c63ff'" onmouseout="this.style.color='#bbb'">⚙️ Admin Panel</a>
  </div>
</div>

<script>
function setSz(n) {
  document.getElementById('bsInput').value = n;
  document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('sb-' + n).classList.add('active');
}
function setP(n) {
  document.getElementById('npInput').value = n;
  document.querySelectorAll('.cnt-btn').forEach(b => b.classList.toggle('active', +b.textContent === n));
  for (let i = 0; i < 6; i++)
    document.getElementById('pr' + i).style.display = i < n ? '' : 'none';
}
</script>

<?php endif; ?>
</body>
</html>
