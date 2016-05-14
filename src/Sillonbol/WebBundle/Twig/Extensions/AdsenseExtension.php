<?php
/**
 * Created by PhpStorm.
 * User: carlosrevillo
 * Date: 14/05/16
 * Time: 16:40
 */

namespace Sillonbol\WebBundle\Twig\Extensions;

class AdsenseExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'sillonbol.adsense';
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter(
                'add_adsense',
                array($this, 'addAdsense'),
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * Adds addsense in the middle of an article
     *
     * @param $text
     */
    public function addAdSense($text)
    {
        $sentences = explode("</p>", $text);
        $first = array_slice($sentences,0 ,2);
        $last = array_slice($sentences, 2);

        $output = join(". ",$first) . '. ';
        $output .= '<div class="adsense">';
        $output .= '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9948983203859787"
     data-ad-slot="7419513355"
     data-ad-format="auto"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>';
        $output .= '</div>' . "\n";
        $output .= join(". ", $last);

        return $output;
    }
}
