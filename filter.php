<?php
/**
 * Filtra i post potenzialmente imbarazzanti/sconvenienti.
 *
 * @param array $posts    Array di post Facebook (ogni elemento ha almeno 'id', 'message', 'created_time').
 * @param array $config   Configurazione del filtro:
 *                        - 'keywords': array di stringhe (case-insensitive) da cercare entro il testo.
 *                        - 'patterns': array di regex (PCRE) aggiuntivi da applicare al messaggio.
 *                        - 'minMatches': numero minimo di keyword/pattern trovati per classificare il post.
 * @return array          Sottoinsieme di $posts con, per ciascuno, un elemento 'matches' contenente le parole/pattern corrisposti.
 */
function filterEmbarrassingPosts(array $posts, array $config = []): array
{
    // --- DEFAULT CONFIGURATION ---
    $defaultConfig = [
        // 1. Parole chiave in italiano (ma puoi aggiungere termini in inglese o altre lingue)
        'keywords' => [
            // Insulti e volgarità
            'cazzo','vaffanculo','merda','stronzo','porca','puttana','troia','bastardo','idiota','imbecille',
            // Riferimenti sessuali volgari
            'scopare','fanculo','culo','pene','fica','coglione','sfigato',
            // Slur e termini discriminatori
            'finocchio','negro','ebreo',
            // Riferimenti a droghe/alcol
            'cocaina','eroina','hashish','marijuana','ubriaco',
        ],
        // 2. Pattern regex per URL, email, numeri sospetti, INSULTI composti
        'patterns' => [
            '/\b[A-Z]{2,}\b/',                // Parole TUTTO MAIUSCOLO (grida o acronimi sospetti)
            '/[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}/i', // Email
            '/\b(merd[ao]?|ca(ll?o)|(coglione))\b/i',  // Variante di insulti
            '/\b(escort|porno|sex)\b/i',      // Contenuti adulti
            '/\brompi[pb]alle\b/i',
        ],
        'minMatches' => 1,                   // Almeno 1 occorrenza per segnalarlo
    ];

    // Unisci default + override
    $cfg = array_merge($defaultConfig, $config);

    $result = [];

    foreach ($posts as $post) {
        $msg = $post['message'] ?? '';
        if (trim($msg) === '') {
            // Se non c'è testo, skip
            continue;
        }

        $found = [];

        // 1) Cerca le keywords (case-insensitive)
        $lower = mb_strtolower($msg, 'UTF-8');
        foreach ($cfg['keywords'] as $kw) {
            if (mb_stripos($lower, mb_strtolower($kw, 'UTF-8'), 0, 'UTF-8') !== false) {
                $found[] = $kw;
            }
        }

        // 2) Applica i pattern regex
        foreach ($cfg['patterns'] as $re) {
            if (preg_match_all($re, $msg, $matches)) {
                foreach ($matches[0] as $m) {
                    if (!in_array($m, $found, true)) {
                        $found[] = $m;
                    }
                }
            }
        }

        // Se abbiamo raggiunto la soglia minima, includi il post nei risultati
        if (count($found) >= $cfg['minMatches']) {
            $post['matches'] = $found;
            $result[] = $post;
        }
    }

    return $result;
}