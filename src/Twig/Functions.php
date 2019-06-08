<?php


namespace App\Twig;

class Functions extends \Twig_Extension{

    public function getFunctions()
    {
        $function = function($tree) {
            $result = $this->build_tree($tree);
            return $result;
        };
        return array(
            new \Twig_SimpleFunction('tree', $function),
        );
    }

    /**
     * Строит дерево категорий
     * @param array $cats
     * @return string
     */
    public function build_tree( $cats){
        $tree = '<ul>3453245';
        $tree .= '</ul>';
        return $tree;
    }


    public function getName()
    {
        return 'tree';
    }

}