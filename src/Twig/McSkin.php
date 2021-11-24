<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 06/11/2020
 * Time: 21:56
 */

namespace App\Twig;



use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class McSkin extends AbstractExtension
{

    public function getFunctions()
    {
        return [
        new TwigFunction('getMcSkin', [$this, 'getMcSkin']),
    ];
    }

    public function getMcSkin($userName, $className, $style = ""){
        $skins = file_get_contents('https://minecraft-api.com/api/skins/'.$userName.'/body/10.5/10/0.25/');
        $skins = substr($skins, 0, -1);
        $skins = $skins." class='".$className."' style='".$style."'>";

        return $skins;
    }
}