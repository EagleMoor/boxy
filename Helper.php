<?php
/**
 * Created by PhpStorm.
 * User: eaglemoor
 * Date: 06.05.15
 * Time: 15:37
 */

namespace yii\boxy;


class Helper {
    public static function arr2pgarr($value) {
        foreach ((array) $value as $inner) {
            if (is_array($inner)) $parts[] = arr2pgarr($inner);
            elseif ($inner === null) {
                $parts[] = 'NULL';
            } else {
                $parts[] = '"' . addcslashes($inner, "\"\\") . '"';
            }
        }
        return '{' . join(",", (array) $parts) . '}';
    }

    public static function pgarr2arr($str, $start = 0) {
        static $p;
        $charAfterSpaces = function ($str, &$p) {
            $p += strspn($str, " \t\r\n", $p);
            return substr($str, $p, 1);
        };

        if ($start == 0) $p = 0;
        $result = array();

        // Leading "{".
        $c = $charAfterSpaces($str, $p);
        if ($c != '{') {
            return;
        }
        $p++;

        // Array may contain:
        // - "-quoted strings
        // - unquoted strings (before first "," or "}")
        // - sub-arrays
        while (1) {
            $c = $charAfterSpaces($str, $p);

            // End of array.
            if ($c == '}') {
                $p++;
                break;
            }

            // Next element.
            if ($c == ',') {
                $p++;
                continue;
            }

            // Sub-array.
            if ($c == '{') {
                $result[] = pgarr2arr($str, $p);
                continue;
            }

            // Unquoted string.
            if ($c != '"') {
                $len = strcspn($str, ",}", $p);
                $v = stripcslashes(substr($str, $p, $len));
                if (!strcasecmp($v, "null")) {
                    $result[] = null;
                } else {
                    $result[] = $v;
                }
                $p += $len;
                continue;
            }

            // Quoted string.
            $m = null;
            if (preg_match('/" ((?' . '>[^"\\\\]+|\\\\.)*) "/Asx', $str, $m, 0, $p)) {
                $result[] = stripcslashes($m[1]);
                $p += strlen($m[0]);
                continue;
            }
        }

        return $result;

    }
}