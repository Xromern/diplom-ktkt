<?php


namespace App\Service;


class Helper
{

   public static function createAlias($alias) {
        $alias = mb_strtolower($alias, 'utf-8');
        $alias = preg_replace('#&\w{2,6};#', ' ', $alias);
        $alias = str_replace(
            array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','і'),
            array('a','b','v','g','d','e','yo','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','ts','ch','sh','sch','','y','','e','yu','ya','i'),
            $alias
        );
        $alias = preg_replace('#[^a-z0-9]#', '-', $alias);
        $alias = trim(preg_replace('#-+#', '-', $alias), '-');
        return $alias;
    }
}