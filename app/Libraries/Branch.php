<?php

namespace App\Libraries;

class Branch
{
    public static function get($b_code = "", $all_branches = false, $all_platforms = false, $is_list = false)
    {
        $path                = 'app' . DIRECTORY_SEPARATOR . 'Libraries';
        $cred_path           = str_replace($path, 'creds.json', __DIR__);
        $json_config         = json_decode(file_get_contents($cred_path), true);
        $config_key          = env('DB_CONFIG_KEY', 'local');
        $rs                  = [];
        $list                = [];
        if (array_key_exists($config_key, $json_config)) {
            $config          = $json_config[$config_key];
            $branches        = $config['databases']['branches'];
            if ($all_branches) {
                $all_branch  = [['code' => 'all', 'id' => 0, 'name' => 'All Branches', 'school_types' => ['1', '2']]];
                $branches    = array_merge($all_branch, $branches);
            }
            foreach ($branches as $branch) {
                $code        = $branch['code'];
                $sch_id      = $branch['id'];
                $name        = $branch['name'];
                $platforms   = [];
                $cons        = [];
                if ($all_platforms && count($branch['school_types']) > 1) {
                    $platforms[] = [
                        'id'     => $code . '_all',
                        'text'   => 'All Platforms',
                    ];
                }
                if (in_array('1', $branch["school_types"])) {
                    $platforms[] = [
                        'id'     => $code . '_kto12',
                        'text'   => 'Kto12',
                    ];

                    $cons[]      = $code . '_kto12';
                }
                if (in_array('2', $branch['school_types'])) {
                    $platforms[] = [
                        'id'     => $code . '_college',
                        'text'   => 'College',
                    ];
                    $cons[]      = $code . '_college';
                }

                $branch         = [
                    "id"        => $code,
                    "sch_id"    => $sch_id,
                    "text"      => $name,
                    "platforms" => $platforms,
                ];
                if ($b_code !== "" && $code == $b_code) {
                    $rs     = [(object) $branch];
                    $list   = $cons;
                    break;
                }
                $rs[]       = (object) $branch;
                $list       = array_merge($list, $cons);
            }
        }
        return $is_list ? $list : collect($rs);
    }

    public static function connections($platform_id)
    {
        $connections = [];
        if ($platform_id === "all_all") {
            return Branch::get("", false, false, true);
            /** all branches and all platforms */
        } else if ($platform_id === "all_kto12") {
            $cons    = Branch::get("", false, false, true);
            foreach ($cons as $con) {
                if (strpos($con, '_kto12')) {
                    $connections[] = $con;
                }
            }
        } else if ($platform_id === "all_college") {
            $cons    = Branch::get("", false, false, true);
            foreach ($cons as $con) {
                if (strpos($con, '_college')) {
                    $connections[] = $con;
                }
            }
        } else if (strpos($platform_id, '_all')) {
            $tmpko_text = explode('_all', $platform_id);
            if (count($tmpko_text) > 1) {
                $branch_code = $tmpko_text[0];
                $connections = Branch::get($branch_code, false, false, true);
            }
        } else {
            $connections = [$platform_id];
        }
        return $connections;
    }
}
